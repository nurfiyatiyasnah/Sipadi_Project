<?php

namespace App\Livewire;

use App\Models\Buku;
use App\Models\EksemplarBuku;
use App\Models\KategoriBuku;
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

        // Check if there is any active borrowing (copy status is 'dipinjam')
        $hasActiveBorrowing = $book->eksemplar()->where('status_eksemplar', 'dipinjam')->exists();

        if ($hasActiveBorrowing) {
            session()->flash('error', 'Buku "'.$book->judul.'" tidak dapat dihapus atau dinonaktifkan karena sedang dipinjam.');

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
            ->withCount('eksemplar')
            ->withCount([
                'eksemplar as eksemplar_tersedia_count' => fn ($q) => $q
                    ->whereIn('status_eksemplar', ['tersedia', 'Tersedia']),
            ]);

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
            'dipinjam' => EksemplarBuku::whereIn('status_eksemplar', ['dipinjam', 'Dipinjam'])->count(),
            'tersedia' => EksemplarBuku::whereIn('status_eksemplar', ['tersedia', 'Tersedia'])->count(),
        ];
        $stats['persen'] = round($stats['tersedia'] / max($stats['eksemplar'], 1) * 100, 1);

        return view('livewire.koleksi-buku-index', compact('books', 'categories', 'stats')); 
    } 
}