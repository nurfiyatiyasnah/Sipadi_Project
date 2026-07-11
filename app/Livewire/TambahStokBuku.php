<?php

namespace App\Livewire;

use App\Models\Buku;
use App\Models\EksemplarBuku;
use App\Models\MutasiStokBuku;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TambahStokBuku extends Component
{
    public int $bookId;

    public $jumlah_stok_tambahan = 1;

    public string $sumber_perolehan = '';

    public string $lokasi_rak = '';

    public string $tanggal_penerimaan = '';

    public string $catatan = '';

    public function mount(int $id): void
    {
        $this->bookId = $id;
        $this->tanggal_penerimaan = date('Y-m-d');
    }

    public function rules(): array
    {
        return [
            'jumlah_stok_tambahan' => ['required', 'integer', 'min:1', 'max:500'],
            'sumber_perolehan' => ['required', 'string', 'max:100'],
            'lokasi_rak' => ['nullable', 'string', 'max:100'],
            'tanggal_penerimaan' => ['required', 'date'],
            'catatan' => ['nullable', 'string'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $bookId = $this->bookId;
        $jumlahTambahan = (int) $this->jumlah_stok_tambahan;
        $lokasiRak = trim($this->lokasi_rak) ?: null;

        DB::transaction(function () use ($bookId, $jumlahTambahan, $lokasiRak) {
            // Lock the book record to prevent concurrency issues
            $book = Buku::where('id_buku', $bookId)->lockForUpdate()->firstOrFail();

            // Fetch existing copies to determine the next sequential sequence number
            $copies = EksemplarBuku::where('id_buku', $bookId)->get();

            $maxSeq = 0;
            foreach ($copies as $copy) {
                $parts = explode('-', $copy->kode_eksemplar);
                $suffix = end($parts);
                if (is_numeric($suffix)) {
                    $maxSeq = max($maxSeq, (int) $suffix);
                }
            }

            $startSeq = $maxSeq + 1;

            // Generate new copies
            for ($i = 0; $i < $jumlahTambahan; $i++) {
                $seq = $startSeq + $i;
                $kode = sprintf('BK-%04d-%03d', $bookId, $seq);

                EksemplarBuku::create([
                    'id_buku' => $bookId,
                    'kode_eksemplar' => $kode,
                    'status_eksemplar' => EksemplarBuku::STATUS_TERSEDIA,
                    'kondisi_eksemplar' => 'Baik',
                    'lokasi_rak' => $lokasiRak,
                    'tanggal_masuk' => $this->tanggal_penerimaan,
                    'sumber_perolehan' => $this->sumber_perolehan,
                    'catatan' => $this->catatan,
                ]);
            }

            // Log stock mutation
            $totalBefore = $copies->count();
            $totalAfter = $totalBefore + $jumlahTambahan;

            $availableBefore = $copies->whereIn('status_eksemplar', EksemplarBuku::AVAILABLE_COPY_STATUSES)->count();
            $availableAfter = $availableBefore + $jumlahTambahan;

            MutasiStokBuku::create([
                'id_buku' => $bookId,
                'id_petugas' => auth()->user()->petugas?->id_petugas,
                'jenis_mutasi' => 'tambah',
                'jumlah' => $this->jumlah_stok_tambahan,
                'stok_total_sebelum' => $totalBefore,
                'stok_total_sesudah' => $totalAfter,
                'stok_tersedia_sebelum' => $availableBefore,
                'stok_tersedia_sesudah' => $availableAfter,
            ]);
        });

        session()->flash('success', 'Berhasil menambahkan '.$this->jumlah_stok_tambahan.' eksemplar baru.');
        $this->redirect(route('petugas.buku.show', $this->bookId));
    }

    public function render()
    {
        $book = Buku::query()
            ->withCount('eksemplar')
            ->findOrFail($this->bookId);

        return view('livewire.tambah-stok-buku', compact('book'))
            ->layout('layouts.petugas');
    }
}
