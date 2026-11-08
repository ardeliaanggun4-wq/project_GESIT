<?php

namespace App\Http\Controllers;

// Letak file: app/Http/Controllers/BuktiPinjamController.php (FILE BARU)
// Bukti peminjaman (PDF) yang bisa dibuka peminjamnya sendiri dan admin.

use App\Models\Peminjaman;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class BuktiPinjamController extends Controller
{
    public function tampil(Peminjaman $peminjaman)
    {
        $user = Auth::user();

        // Hanya pemilik peminjaman atau admin
        abort_unless($user->isAdmin() || $peminjaman->user_id === $user->id, 403);

        // Bukti baru ada setelah pengajuan disetujui petugas
        if (! in_array($peminjaman->status, ['disetujui', 'dipinjam', 'selesai'], true)) {
            return back()->withErrors(['bukti' => 'Bukti pinjam baru tersedia setelah pengajuan disetujui petugas GESIT.']);
        }

        $peminjaman->load(['user', 'alat']);

        // Foto alat disematkan langsung ke PDF sebagai bukti rupa alat yang dipinjam.
        // DomPDF hanya mendukung JPG/PNG, jadi WEBP diubah dulu ke JPG (butuh ekstensi GD).
        $foto = null;
        $path = $peminjaman->alat->foto ? storage_path('app/public/' . $peminjaman->alat->foto) : null;
        if ($path && is_file($path)) {
            $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            $isi = null;
            $mime = 'image/jpeg';

            if (in_array($ext, ['jpg', 'jpeg', 'png'], true)) {
                $isi = file_get_contents($path);
                $mime = $ext === 'png' ? 'image/png' : 'image/jpeg';
            } elseif ($ext === 'webp' && function_exists('imagecreatefromwebp')) {
                $gambar = @imagecreatefromwebp($path);
                if ($gambar) {
                    ob_start();
                    imagejpeg($gambar, null, 85);
                    $isi = ob_get_clean();
                    imagedestroy($gambar);
                }
            }

            if ($isi) {
                $foto = 'data:' . $mime . ';base64,' . base64_encode($isi);
            }
        }

        // QR menuju kartu pengajuan ini di sisi admin. Hanya dibuat bila paket bacon/qr-code sudah terpasang,
        // jadi PDF tetap jalan tanpa QR kalau paketnya belum ada.
        $qr = null;
        if (class_exists(\BaconQrCode\Writer::class)) {
            try {
                $penulis = new \BaconQrCode\Writer(new \BaconQrCode\Renderer\ImageRenderer(
                    new \BaconQrCode\Renderer\RendererStyle\RendererStyle(160, 0),
                    new \BaconQrCode\Renderer\Image\SvgImageBackEnd()
                ));
                $qr = 'data:image/svg+xml;base64,' . base64_encode($penulis->writeString(route('admin.scan', $peminjaman)));
            } catch (\Throwable $e) {
                report($e);
                $qr = null;
            }
        }

        $nama = 'bukti-pinjam-' . $peminjaman->kode_pengajuan . '.pdf';

        return Pdf::loadView('bukti-pinjam', [
            'p' => $peminjaman,
            'foto' => $foto,
            'qr' => $qr,
            'dicetak' => now(),
        ])->setPaper('a4')->stream($nama);
    }
}