<?php

namespace App\Http\Controllers\Siswa;

// Letak file: app/Http/Controllers/Siswa/PanduanController.php

use App\Http\Controllers\Controller;

class PanduanController extends Controller
{
    public function index()
    {
        return view('siswa.panduan');
    }
}