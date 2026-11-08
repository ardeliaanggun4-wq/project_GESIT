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

        $nama = 'bukti-pinjam-' . $peminjaman->kode_pengajuan . '.pdf';

        return Pdf::loadView('bukti-pinjam', [
            'p' => $peminjaman,
            'foto' => $foto,
            'dicetak' => now(),
        ])->setPaper('a4')->stream($nama);
    }
}