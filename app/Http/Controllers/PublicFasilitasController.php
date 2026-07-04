<?php

namespace App\Http\Controllers;

use App\Models\Fasilitas;
use Illuminate\Http\Request;

class PublicFasilitasController extends Controller
{
    public function index(Request $request)
    {
        $kategori = $request->get('kategori', 'Semua');
        
        $query = Fasilitas::query()->where('tampilkan_publik', true);
        
        if ($kategori !== 'Semua') {
            $query->where('kategori', $kategori);
        }

        $fasilitas = $query->latest('id_fasilitas')->paginate(12);

        return view('landing.fasilitas', compact('fasilitas', 'kategori'));
    }

    public function show($id)
    {
        $fasilita = Fasilitas::where('tampilkan_publik', true)->findOrFail($id);
        
        return view('landing.fasilitas_detail', compact('fasilita'));
    }
}
