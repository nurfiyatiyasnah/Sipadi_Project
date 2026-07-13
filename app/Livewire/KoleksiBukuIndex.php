<?php

namespace App\Livewire;

use App\Models\Buku;
use App\Models\EksemplarBuku;
use App\Models\KategoriBuku;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class KoleksiBukuIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public string $kategori = '';

    public string $status = 'aktif';

    protected $queryString = [
        'search' => ['except' => ''],
        'kategori' => ['except' => ''],
        'status' => ['except' => ''],
    ];

    public function mount(): void
    {
        $this->kategori = (string) request('kategori', '');
        $this->status = (string) request('status', 'aktif');
        $this->search = (string) request('search', '');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingKategori(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function deleteBook(int $id): void
    {
        $book = Buku::with('eksemplar')->findOrFail($id);

        $hasActiveBorrowing = $book->detailPeminjaman()
            ->whereIn('status_detail', EksemplarBuku::ACTIVE_DETAIL_STATUSES)
            ->whereHas('peminjaman', function (Builder $query): void {
                $query->whereIn('status_peminjaman', EksemplarBuku::ACTIVE_BORROWING_STATUSES);
            })
            ->exists();

        $hasActiveCopyStatus = $book->eksemplar()
            ->whereIn('status_eksemplar', EksemplarBuku::ACTIVE_COPY_STATUSES)
            ->exists();

        if ($hasActiveBorrowing || $hasActiveCopyStatus) {
            session()->flash('error', 'Buku "'.$book->judul.'" tidak dapat dihapus atau dinonaktifkan karena sedang dipinjam atau dipesan.');

            return;
        }

        // Check if book has copies or borrowing history
        $hasCopies = $book->eksemplar()->exists();
        $hasHistory = $book->detailPeminjaman()->exists();

        if ($hasCopies || $hasHistory) {
            // Use archive/deactivate approach
            $book->update(['status_katalog' => 'nonaktif']);
            session()->flash('success', 'Buku "'.$book->judul.'" berhasil dinonaktifkan karena memiliki data eksemplar/riwayat.');
        } else {
            // Hard delete
            DB::transaction(function () use ($book) {
                $book->mutasiStok()->delete();
                $book->delete();
            });
            session()->flash('success', 'Buku "'.$book->judul.'" berhasil dihapus dari sistem.');
        }
    }

    #[Layout('layouts.petugas')]
    public function render()
    {
        $query = Buku::query()
            ->with('kategori')
            ->withKetersediaanCounts();

        if (! empty($this->search)) {
            $query->where(function ($q) {
                $q->where('judul', 'like', '%'.$this->search.'%')
                    ->orWhere('isbn', 'like', '%'.$this->search.'%')
                    ->orWhere('penulis', 'like', '%'.$this->search.'%')
                    ->orWhere('penerbit', 'like', '%'.$this->search.'%')
                    ->orWhere('kode_buku', 'like', '%'.$this->search.'%');
            });
        }

        if (! empty($this->kategori)) {
            $query->where('id_kategori', $this->kategori);
        }

        if (! empty($this->status)) {
            $statusValue = strtolower($this->status);
            $query->whereIn('status_katalog', [$statusValue, ucfirst($statusValue)]);
        }

        $books = $query->latest('id_buku')->paginate(10)->withQueryString();

        $categories = KategoriBuku::query()
            ->orderBy('nama_kategori')
            ->get(['id_kategori', 'nama_kategori']);

        $stats = [
            'judul' => Buku::count(),
            'eksemplar' => EksemplarBuku::count(),
            'dipinjam' => EksemplarBuku::where('status_eksemplar', EksemplarBuku::STATUS_DIPINJAM)->count(),
            'tersedia' => EksemplarBuku::where('status_eksemplar', EksemplarBuku::STATUS_TERSEDIA)->count(),
        ];
        $stats['persen'] = round($stats['tersedia'] / max($stats['eksemplar'], 1) * 100, 1);

        return view('livewire.koleksi-buku-index', compact('books', 'categories', 'stats'));
    }
}
