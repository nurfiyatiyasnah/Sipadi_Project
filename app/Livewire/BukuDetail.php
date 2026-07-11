<?php

namespace App\Livewire;

use App\Models\Buku;
use App\Models\DetailPeminjaman;
use App\Models\EksemplarBuku;
use Illuminate\Database\Eloquent\Builder;
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
        $copy = EksemplarBuku::where('id_buku', $this->bookId)->findOrFail($copyId);

        if ($copy->hasActiveBorrowing()) {
            session()->flash('error', 'Status eksemplar yang masih memiliki peminjaman aktif tidak dapat diubah.');

            return;
        }

        $allowedStatuses = EksemplarBuku::COPY_STATUSES;
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
        $copy = EksemplarBuku::where('id_buku', $this->bookId)->findOrFail($copyId);

        if ($copy->hasActiveBorrowing() || $copy->hasActiveCopyStatus()) {
            session()->flash('error', 'Eksemplar yang sedang dipinjam atau dipesan tidak dapat dihapus.');

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
            ->withKetersediaanCounts()
            ->findOrFail($this->bookId);

        $copies = $book->eksemplar()
            ->withExists([
                'detailPeminjaman as has_active_borrowing' => function (Builder $query): void {
                    $query->whereIn('status_detail', EksemplarBuku::ACTIVE_DETAIL_STATUSES)
                        ->whereHas('peminjaman', function (Builder $query): void {
                            $query->whereIn('status_peminjaman', EksemplarBuku::ACTIVE_BORROWING_STATUSES);
                        });
                },
            ])
            ->latest('id_eksemplar_buku')
            ->get();

        $book->setRelation('eksemplar', $copies);
        $lokasiRak = $book->lokasiRakEksemplarLabel();

        $borrowingHistory = DetailPeminjaman::query()
            ->with(['peminjaman.anggota'])
            ->where('id_buku', $this->bookId)
            ->latest('id_detail_peminjaman')
            ->limit(10)
            ->get();

        return view('livewire.buku-detail', compact('book', 'copies', 'borrowingHistory', 'lokasiRak'))
            ->layout('layouts.petugas');
    }
}
