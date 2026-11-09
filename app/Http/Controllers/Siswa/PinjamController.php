<?php

namespace App\Http\Controllers\Siswa;

// Letak file: app/Http/Controllers/Siswa/PinjamController.php

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class PinjamController extends Controller
{
    public function create()
    {
        $daftarAlat = Alat::orderBy('nama')->get();

        return view('siswa.ajukan', compact('daftarAlat'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'alat_id' => ['required', 'exists:alat,id'],
            'tanggal_pinjam' => ['required', 'date'],
            'tanggal_kembali_rencana' => ['required', 'date', 'after_or_equal:tanggal_pinjam'],
            'jumlah' => ['required', 'integer', 'min:1'],
            'keperluan' => ['nullable', 'string', 'max:500'],
        ]);

        // Maksimal lama peminjaman mengikuti batas tiap alat
        $alat = Alat::findOrFail($validated['alat_id']);
        $batas = $alat->batasHariPinjam();
        $lama = Carbon::parse($validated['tanggal_pinjam'])->startOfDay()
            ->diffInDays(Carbon::parse($validated['tanggal_kembali_rencana'])->startOfDay());
        if ($lama > $batas) {
            throw ValidationException::withMessages([
                'tanggal_kembali_rencana' => 'Maksimal peminjaman ' . $alat->nama . ' adalah ' . $batas . ' hari. Pilih tanggal kembali paling lambat ' .
                    Carbon::parse($validated['tanggal_pinjam'])->addDays($batas)->translatedFormat('j F Y') . '.',
            ]);
        }

        // Jumlah tidak boleh melebihi stok (alat yang stoknya habis tetap bisa diajukan 1 unit, sesuai tampilan form)
        $maksJumlah = max((int) $alat->stok, 1);
        if ((int) $validated['jumlah'] > $maksJumlah) {
            throw ValidationException::withMessages([
                'jumlah' => 'Jumlah melebihi stok. Maksimal ' . $maksJumlah . ' unit untuk ' . $alat->nama . '.',
            ]);
        }

        // Belum boleh meminjam lagi bila ada alat yang terlambat melewati batas toleransi
        $tertahan = Auth::user()->peminjaman()
            ->whereIn('status', ['dipinjam', 'disetujui'])
            ->whereDate('tanggal_kembali_rencana', '<', today()->subDays(Peminjaman::MAKS_HARI_TELAT))
            ->exists();
        if ($tertahan) {
            throw ValidationException::withMessages([
                'alat_id' => 'Kamu belum bisa mengajukan peminjaman baru karena ada alat yang terlambat dikembalikan lebih dari ' .
                    Peminjaman::MAKS_HARI_TELAT . ' hari. Kembalikan ke ruang Sarpras terlebih dahulu.',
            ]);
        }

        // Belum boleh meminjam lagi bila masih punya kewajiban memperbaiki/mengganti alat rusak atau hilang
        if (Auth::user()->peminjaman()->kewajibanBelumSelesai()->exists()) {
            throw ValidationException::withMessages([
                'alat_id' => 'Kamu belum bisa mengajukan peminjaman baru karena masih ada kewajiban memperbaiki atau mengganti alat yang rusak atau hilang. Temui petugas GESIT di ruang Sarpras.',
            ]);
        }

        Peminjaman::create([
            'kode_pengajuan' => Peminjaman::generateKode(),
            'user_id' => Auth::id(),
            'alat_id' => $validated['alat_id'],
            'jumlah' => $validated['jumlah'],
            'tanggal_pinjam' => $validated['tanggal_pinjam'],
            'tanggal_kembali_rencana' => $validated['tanggal_kembali_rencana'],
            'keperluan' => $validated['keperluan'] ?? null,
            'status' => 'menunggu',
        ]);

        return redirect()->route('siswa.beranda')->with('status', 'Pengajuan peminjaman berhasil dikirim, menunggu verifikasi petugas GESIT.');
    }
}