<?php

namespace App\Livewire;

use App\Models\Buku;
use App\Models\EksemplarBuku;
use App\Models\KategoriBuku;
use App\Models\MutasiStokBuku;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class BukuCreate extends Component
{
    use WithFileUploads;

    public string $judul = '';

    public string $isbn = '';

    public string $penulis = '';

    public string $penerbit = '';

    public string $id_kategori = '';

    public string $tahun_terbit = '';

    public string $deskripsi = '';

    public string $lokasi_rak = '';

    public $cover_file;

    public $stok_awal = 1;

    public function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:200'],
            'isbn' => ['required', 'string', 'max:30', 'unique:buku,isbn'],
            'penulis' => ['required', 'string', 'max:150'],
            'penerbit' => ['required', 'string', 'max:150'],
            'id_kategori' => ['required', 'exists:kategori_buku,id_kategori'],
            'tahun_terbit' => ['required', 'integer', 'digits:4', 'min:1800', 'max:'.date('Y')],
            'deskripsi' => ['nullable', 'string'],
            'lokasi_rak' => ['nullable', 'string', 'max:100'],
            'cover_file' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'stok_awal' => ['required', 'integer', 'min:0', 'max:100'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $coverPath = $this->cover_file->store('covers', 'public');

        do {
            $kode_buku = 'BKU-'.Str::upper(Str::random(6));
        } while (Buku::where('kode_buku', $kode_buku)->exists());

        $stokAwal = (int) $this->stok_awal;
        $lokasiRak = trim($this->lokasi_rak) ?: null;

        DB::transaction(function () use ($kode_buku, $coverPath, $stokAwal, $lokasiRak) {
            $book = Buku::create([
                'id_kategori' => $this->id_kategori,
                'kode_buku' => $kode_buku,
                'isbn' => $this->isbn,
                'judul' => $this->judul,
                'penulis' => $this->penulis,
                'penerbit' => $this->penerbit,
                'tahun_terbit' => (int) $this->tahun_terbit,
                'deskripsi' => $this->deskripsi,
                'gambar_cover' => $coverPath,
                'status_katalog' => 'aktif',
            ]);

            // Add initial copies if specified
            if ($stokAwal > 0) {
                for ($i = 0; $i < $stokAwal; $i++) {
                    EksemplarBuku::create([
                        'id_buku' => $book->id_buku,
                        'kode_eksemplar' => sprintf('BK-%04d-%03d', $book->id_buku, $i + 1),
                        'status_eksemplar' => EksemplarBuku::STATUS_TERSEDIA,
                        'kondisi_eksemplar' => 'Baik',
                        'lokasi_rak' => $lokasiRak,
                        'tanggal_masuk' => now(),
                        'sumber_perolehan' => 'Pengadaan Awal',
                        'catatan' => 'Eksemplar awal saat input buku baru.',
                    ]);
                }

                // Log stock mutation
                MutasiStokBuku::create([
                    'id_buku' => $book->id_buku,
                    'id_petugas' => auth()->user()->petugas?->id_petugas,
                    'jenis_mutasi' => 'tambah',
                    'jumlah' => $stokAwal,
                    'stok_total_sebelum' => 0,
                    'stok_total_sesudah' => $stokAwal,
                    'stok_tersedia_sebelum' => 0,
                    'stok_tersedia_sesudah' => $stokAwal,
                ]);
            }
        });

        session()->flash('success', 'Buku baru dan eksemplar berhasil ditambahkan.');
        $this->redirect(route('petugas.koleksi'));
    }

    public function render()
    {
        $categories = KategoriBuku::query()
            ->orderBy('nama_kategori')
            ->get(['id_kategori', 'nama_kategori']);

        return view('livewire.buku-create', compact('categories'))
            ->layout('layouts.petugas');
    }
}
