<?php

namespace App\Http\Controllers\Admin;

// Letak file: app/Http/Controllers/Admin/PenggunaController.php (FILE BARU)
// Kelola data induk siswa/guru (daftar NIPD/NIP yang boleh mendaftar), setujui akun baru,
// dan atur ulang kata sandi akun siswa/guru.

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PenggunaController extends Controller
{
    // Tabel dan kolom data induk menurut jenisnya
    private const JENIS = [
        'siswa' => ['tabel' => 'data_siswa', 'nomor' => 'nipd', 'info' => 'kelas'],
        'guru'  => ['tabel' => 'data_guru',  'nomor' => 'nip',  'info' => 'mata_pelajaran'],
    ];

    public function index(Request $request)
    {
        $jenis = $request->query('jenis') === 'guru' ? 'guru' : 'siswa';
        $m = self::JENIS[$jenis];

        $query = DB::table($m['tabel']);
        if ($request->filled('cari')) {
            $cari = $request->query('cari');
            $query->where(function ($w) use ($m, $cari) {
                $w->where('nama', 'like', "%{$cari}%")
                  ->orWhere($m['nomor'], 'like', "%{$cari}%")
                  ->orWhere($m['info'], 'like', "%{$cari}%");
            });
        }
        $data = $query->orderBy('nama')->paginate(15)->withQueryString();

        // Akun yang sudah terdaftar untuk baris-baris di halaman ini
        $nomorHalaman = $data->getCollection()->pluck($m['nomor'])->all();
        $akun = User::where('role', $jenis)->whereIn('nisn_nip', $nomorHalaman)->get()->keyBy('nisn_nip');

        $menunggu = User::where('status_akun', 'menunggu')->orderBy('created_at')->get();

        $jumlah = [
            'siswa' => DB::table('data_siswa')->count(),
            'guru'  => DB::table('data_guru')->count(),
        ];
        $tanpaLahir = DB::table($m['tabel'])->whereNull('tanggal_lahir')->count();

        return view('admin.pengguna', [
            'jenis'      => $jenis,
            'kolom'      => $m['nomor'],
            'kolomInfo'  => $m['info'],
            'data'       => $data,
            'akun'       => $akun,
            'menunggu'   => $menunggu,
            'jumlah'     => $jumlah,
            'tanpaLahir' => $tanpaLahir,
        ]);
    }

    // Tambah satu siswa/guru ke data induk
    public function store(Request $request, string $jenis)
    {
        $m = self::JENIS[$jenis];
        $request->merge(['nomor' => preg_replace('/\D+/', '', (string) $request->input('nomor'))]);

        $v = $request->validate([
            'nomor'         => ['required', 'regex:/^[0-9]{5,20}$/', Rule::unique($m['tabel'], $m['nomor'])],
            'nama'          => ['required', 'string', 'max:255'],
            'info'          => ['nullable', 'string', 'max:100'],
            'tanggal_lahir' => ['nullable', 'date_format:Y-m-d'],
        ], $this->pesan($jenis));

        DB::table($m['tabel'])->insert([
            $m['nomor']     => $v['nomor'],
            'nama'          => trim($v['nama']),
            $m['info']      => trim((string) ($v['info'] ?? '')) ?: null,
            'tanggal_lahir' => $v['tanggal_lahir'] ?? null,
        ]);

        return redirect()->route('admin.pengguna', ['jenis' => $jenis])
            ->with('status', ucfirst($jenis) . ' baru berhasil ditambahkan ke data sekolah.');
    }

    // Ubah nama, kelas/mata pelajaran, atau tanggal lahir (nomor tidak diubah karena terhubung ke akun)
    public function update(Request $request, string $jenis, int $id)
    {
        $m = self::JENIS[$jenis];
        $baris = DB::table($m['tabel'])->where('id', $id)->first();
        abort_if(! $baris, 404);

        $v = $request->validate([
            'nama'          => ['required', 'string', 'max:255'],
            'info'          => ['nullable', 'string', 'max:100'],
            'tanggal_lahir' => ['nullable', 'date_format:Y-m-d'],
        ], $this->pesan($jenis));

        $info = trim((string) ($v['info'] ?? '')) ?: null;
        DB::table($m['tabel'])->where('id', $id)->update([
            'nama'          => trim($v['nama']),
            $m['info']      => $info,
            'tanggal_lahir' => $v['tanggal_lahir'] ?? null,
        ]);
        $this->selaraskanAkun($jenis, $baris->{$m['nomor']}, trim($v['nama']), $info);

        return back()->with('status', 'Data ' . $jenis . ' berhasil diperbarui.');
    }

    // Hapus dari data induk, hanya bila belum punya akun
    public function destroy(string $jenis, int $id)
    {
        $m = self::JENIS[$jenis];
        $baris = DB::table($m['tabel'])->where('id', $id)->first();
        abort_if(! $baris, 404);

        if (User::where('role', $jenis)->where('nisn_nip', $baris->{$m['nomor']})->exists()) {
            return back()->withErrors(['hapus' => $baris->nama . ' sudah punya akun, jadi tidak dihapus dari data sekolah.']);
        }

        DB::table($m['tabel'])->where('id', $id)->delete();

        return back()->with('status', $baris->nama . ' dihapus dari data sekolah.');
    }

    // Impor CSV: kolom 1 nomor, 2 nama, 3 kelas/mata pelajaran, 4 tanggal lahir (opsional).
    // Nomor yang sudah ada diperbarui (cocok untuk kenaikan kelas), nomor baru ditambahkan.
    public function impor(Request $request, string $jenis)
    {
        $m = self::JENIS[$jenis];
        $request->validate([
            'berkas' => ['required', 'file', 'mimes:csv,txt', 'max:2048'],
        ], [
            'berkas.required' => 'Pilih berkas CSV terlebih dahulu.',
            'berkas.mimes'    => 'Berkas harus berformat CSV.',
            'berkas.max'      => 'Ukuran berkas maksimal 2 MB.',
        ]);

        $jalur = $request->file('berkas')->getRealPath();
        $pertama = (string) fgets(fopen($jalur, 'r'));
        $pemisah = substr_count($pertama, ';') > substr_count($pertama, ',') ? ';' : ',';

        $baru = $diperbarui = $dilewati = 0;
        $h = fopen($jalur, 'r');

        DB::transaction(function () use ($h, $pemisah, $m, $jenis, &$baru, &$diperbarui, &$dilewati) {
            $urut = 0;
            while (($r = fgetcsv($h, 0, $pemisah)) !== false) {
                $urut++;
                if (! array_filter($r, fn ($c) => trim((string) $c) !== '')) {
                    continue; // baris kosong
                }

                $nomor = preg_replace('/\D+/', '', (string) ($r[0] ?? ''));
                $nama = trim((string) ($r[1] ?? ''));
                if (! preg_match('/^[0-9]{5,20}$/', $nomor) || $nama === '') {
                    if ($urut > 1) { $dilewati++; } // baris judul di urutan pertama tidak dihitung
                    continue;
                }

                $info = trim((string) ($r[2] ?? '')) ?: null;
                $lahir = $this->bacaTanggal($r[3] ?? null);

                $ada = DB::table($m['tabel'])->where($m['nomor'], $nomor)->first();
                if ($ada) {
                    $set = ['nama' => $nama, $m['info'] => $info];
                    if ($lahir) {
                        $set['tanggal_lahir'] = $lahir; // kolom kosong tidak menghapus tanggal lahir yang sudah ada
                    }
                    DB::table($m['tabel'])->where('id', $ada->id)->update($set);
                    $diperbarui++;
                } else {
                    DB::table($m['tabel'])->insert([
                        $m['nomor']     => $nomor,
                        'nama'          => $nama,
                        $m['info']      => $info,
                        'tanggal_lahir' => $lahir,
                    ]);
                    $baru++;
                }
                $this->selaraskanAkun($jenis, $nomor, $nama, $info);
            }
        });
        fclose($h);

        return redirect()->route('admin.pengguna', ['jenis' => $jenis])
            ->with('status', "Impor selesai: {$baru} baru, {$diperbarui} diperbarui, {$dilewati} baris dilewati.");
    }

    public function setujui(User $user)
    {
        abort_unless($user->menunggu() && in_array($user->role, ['siswa', 'guru'], true), 404);

        $user->forceFill(['status_akun' => 'aktif'])->save();

        return back()->with('status', 'Akun ' . $user->name . ' disetujui dan sudah bisa masuk.');
    }

    // Tolak = hapus akun yang menunggu, sehingga nomornya bebas didaftarkan oleh pemilik aslinya
    public function tolak(User $user)
    {
        abort_unless($user->menunggu() && in_array($user->role, ['siswa', 'guru'], true), 404);

        $nama = $user->name;
        $user->delete();

        return back()->with('status', 'Pendaftaran ' . $nama . ' ditolak. Nomornya bisa didaftarkan ulang oleh pemilik aslinya.');
    }

    // Buat kata sandi sementara untuk siswa/guru yang lupa; ditampilkan sekali saja
    public function resetSandi(User $user)
    {
        abort_unless(in_array($user->role, ['siswa', 'guru'], true), 403);

        $huruf = 'abcdefghjkmnpqrstuvwxyz23456789'; // tanpa karakter yang mirip (l, 1, o, 0, i)
        $sementara = '';
        for ($i = 0; $i < 8; $i++) {
            $sementara .= $huruf[random_int(0, strlen($huruf) - 1)];
        }

        $user->forceFill([
            'password'       => $sementara, // di-hash otomatis oleh cast "hashed"
            'remember_token' => Str::random(60),
        ])->save();

        // Keluarkan sesi lama akun ini
        DB::table('sessions')->where('user_id', $user->id)->delete();

        return back()->with('sandi_sementara', ['nama' => $user->name, 'sandi' => $sementara]);
    }

    // Nama dan kelas di akun yang sudah ada ikut diperbarui
    private function selaraskanAkun(string $jenis, string $nomor, string $nama, ?string $info): void
    {
        User::where('role', $jenis)->where('nisn_nip', $nomor)->update([
            'name'          => $nama,
            'kelas_jabatan' => $jenis === 'siswa' ? $info : ($info ? 'Guru ' . $info : 'Guru'),
        ]);
    }

    // Terima Y-m-d, d/m/Y, d-m-Y, atau d.m.Y
    private function bacaTanggal(?string $teks): ?string
    {
        $teks = trim((string) $teks);
        if ($teks === '') {
            return null;
        }
        foreach (['Y-m-d', 'd/m/Y', 'd-m-Y', 'd.m.Y'] as $format) {
            $t = \DateTime::createFromFormat('!' . $format, $teks);
            $galat = \DateTime::getLastErrors();
            $bersih = ! $galat || ($galat['warning_count'] === 0 && $galat['error_count'] === 0);
            if ($t && $bersih && (int) $t->format('Y') >= 1940 && $t <= new \DateTime('today')) {
                return $t->format('Y-m-d');
            }
        }

        return null;
    }

    private function pesan(string $jenis): array
    {
        $label = $jenis === 'siswa' ? 'NIPD' : 'NIP';

        return [
            'nomor.required'         => $label . ' wajib diisi.',
            'nomor.regex'            => $label . ' hanya boleh berisi angka (5 sampai 20 digit).',
            'nomor.unique'           => $label . ' ini sudah ada di data sekolah.',
            'nama.required'          => 'Nama wajib diisi.',
            'tanggal_lahir.date_format' => 'Format tanggal lahir belum benar.',
        ];
    }
}