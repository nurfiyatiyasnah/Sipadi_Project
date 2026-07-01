<?php

namespace App\Livewire;

use App\Models\Buku;
use App\Models\DetailPeminjaman;
use App\Models\EksemplarBuku;
use Livewire\Component;

class BukuDetail extends Component
{
    public int $bookId;

    public function mount(int $id): void
    {
        $this->bookId = $id;
    }

    public function updateCopyStatus(int $copyId, string $newStatus): void
    {
        $copy = EksemplarBuku::findOrFail($copyId);

        if ($copy->status_eksemplar === 'dipinjam') {
            session()->flash('error', 'Status eksemplar yang sedang dipinjam tidak dapat diubah.');

            return;
        }

        $allowedStatuses = ['tersedia', 'dipinjam', 'rusak', 'hilang', 'nonaktif'];
        if (! in_array(strtolower($newStatus), $allowedStatuses)) {
            session()->flash('error', 'Status eksemplar tidak valid.');

            return;
        }

        $copy->update([
            'status_eksemplar' => strtolower($newStatus),
        ]);

        session()->flash('success', 'Status eksemplar '.$copy->kode_eksemplar.' berhasil diubah menjadi '.$newStatus.'.');
    }

    public function deleteCopy(int $copyId): void
    {
        $copy = EksemplarBuku::findOrFail($copyId);

        if ($copy->status_eksemplar === 'dipinjam') {
            session()->flash('error', 'Eksemplar yang sedang dipinjam tidak dapat dihapus.');

            return;
        }

        $kode = $copy->kode_eksemplar;
        $copy->delete();

        session()->flash('success', 'Eksemplar '.$kode.' berhasil dihapus.');
    }

    public function render()
    {
        $book = Buku::query()
            ->with(['kategori'])
            ->withCount('eksemplar')
            ->withCount([
                'eksemplar as eksemplar_tersedia_count' => fn ($q) => $q
                    ->whereIn('status_eksemplar', ['tersedia', 'Tersedia']),
            ])
            ->findOrFail($this->bookId);

        $copies = $book->eksemplar()
            ->latest('id_eksemplar_buku')
            ->get();

        $borrowingHistory = DetailPeminjaman::query()
            ->with(['peminjaman.anggota'])
            ->where('id_buku', $this->bookId)
            ->latest('id_detail_peminjaman')
            ->limit(10)
            ->get();

        return view('livewire.buku-detail', compact('book', 'copies', 'borrowingHistory'))
            ->layout('layouts.petugas');
    }
}
