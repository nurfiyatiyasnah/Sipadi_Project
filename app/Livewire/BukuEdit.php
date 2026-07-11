<?php

namespace App\Livewire;

use App\Models\Buku;
use App\Models\KategoriBuku;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class BukuEdit extends Component
{
    use WithFileUploads;

    public int $bookId;

    public string $judul = '';

    public string $isbn = '';

    public string $penulis = '';

    public string $penerbit = '';

    public string $id_kategori = '';

    public string $tahun_terbit = '';

    public string $deskripsi = '';

    public string $existing_cover = '';

    public $cover_file;

    public function mount(int $id): void
    {
        $book = Buku::findOrFail($id);
        $this->bookId = $book->id_buku;
        $this->judul = $book->judul;
        $this->isbn = $book->isbn ?? '';
        $this->penulis = $book->penulis ?? '';
        $this->penerbit = $book->penerbit ?? '';
        $this->id_kategori = (string) $book->id_kategori;
        $this->tahun_terbit = (string) $book->tahun_terbit;
        $this->deskripsi = $book->deskripsi ?? '';
        $this->existing_cover = $book->gambar_cover ?? '';
    }

    public function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:200'],
            'isbn' => ['required', 'string', 'max:30', 'unique:buku,isbn,'.$this->bookId.',id_buku'],
            'penulis' => ['required', 'string', 'max:150'],
            'penerbit' => ['required', 'string', 'max:150'],
            'id_kategori' => ['required', 'exists:kategori_buku,id_kategori'],
            'tahun_terbit' => ['required', 'integer', 'digits:4', 'min:1800', 'max:'.date('Y')],
            'deskripsi' => ['nullable', 'string'],
            'cover_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $book = Buku::findOrFail($this->bookId);
        $coverPath = null;

        if ($this->cover_file) {
            $coverPath = $this->cover_file->store('covers', 'public');

            // Delete old cover if it exists locally
            if ($book->gambar_cover && ! str_starts_with($book->gambar_cover, 'http')) {
                Storage::disk('public')->delete($book->gambar_cover);
            }
        }

        $data = [
            'id_kategori' => $this->id_kategori,
            'isbn' => $this->isbn,
            'judul' => $this->judul,
            'penulis' => $this->penulis,
            'penerbit' => $this->penerbit,
            'tahun_terbit' => (int) $this->tahun_terbit,
            'deskripsi' => $this->deskripsi,
        ];

        if ($coverPath) {
            $data['gambar_cover'] = $coverPath;
        }

        $book->update($data);

        session()->flash('success', 'Buku "'.$book->judul.'" berhasil diperbarui.');
        $this->redirect(route('petugas.koleksi'));
    }

    public function render()
    {
        $categories = KategoriBuku::query()
            ->orderBy('nama_kategori')
            ->get(['id_kategori', 'nama_kategori']);

        return view('livewire.buku-edit', compact('categories'))
            ->layout('layouts.petugas');
    }
}
