<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePengumumanRequest;
use App\Http\Requests\UpdatePengumumanRequest;
use App\Models\Pengumuman;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PengumumanController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $statusFilter = $request->query('status');
        $today = now()->toDateString();
        $startOfToday = $today.' 00:00:00';
        $endOfToday = $today.' 23:59:59';

        $query = Pengumuman::query()->with('petugas');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('isi', 'like', "%{$search}%");
            });
        }

        if ($statusFilter) {
            if ($statusFilter === 'Aktif') {
                $query->where('status_pengumuman', 'terbit')
                    ->where('tanggal_mulai', '<=', $endOfToday)
                    ->where('tanggal_selesai', '>=', $startOfToday);
            } elseif ($statusFilter === 'Mendatang') {
                $query->where('status_pengumuman', 'terbit')
                    ->where('tanggal_mulai', '>', $endOfToday);
            } elseif ($statusFilter === 'Selesai') {
                $query->where('status_pengumuman', 'terbit')
                    ->where('tanggal_selesai', '<', $startOfToday);
            } elseif ($statusFilter === 'Draf') {
                $query->where('status_pengumuman', 'draf');
            }
        }

        $pengumuman = $query->latest('id_pengumuman')->paginate(10)->withQueryString();

        $stats = [
            'total' => Pengumuman::count(),
            'aktif' => Pengumuman::where('status_pengumuman', 'terbit')
                ->where('tanggal_mulai', '<=', $endOfToday)
                ->where('tanggal_selesai', '>=', $startOfToday)
                ->count(),
            'mendatang' => Pengumuman::where('status_pengumuman', 'terbit')
                ->where('tanggal_mulai', '>', $endOfToday)
                ->count(),
        ];

        return view('pengumuman.index', compact('pengumuman', 'stats', 'search', 'statusFilter'));
    }

    public function create(): View
    {
        return view('pengumuman.create');
    }

    public function store(StorePengumumanRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['id_petugas'] = $request->user()->petugas?->id_petugas;
        $data['slug'] = $this->uniqueSlug($data['judul']);

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('pengumuman', 'public');
        }

        if ($request->hasFile('file_lampiran')) {
            $lampiran = [];
            foreach ($request->file('file_lampiran') as $file) {
                $path = $file->store('pengumuman/lampiran', 'public');
                $lampiran[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $this->formatBytes($file->getSize()),
                ];
            }
            $data['file_lampiran'] = $lampiran;
        }

        Pengumuman::create($data);

        return redirect()->route('petugas.pengumuman.index')
            ->with('success', 'Pengumuman berhasil dibuat.');
    }

    public function show(Pengumuman $pengumuman): View
    {
        $pengumuman->increment('total_views');

        $latestPengumuman = Pengumuman::whereKeyNot($pengumuman->id_pengumuman)
            ->where('status_pengumuman', 'terbit')
            ->latest('id_pengumuman')
            ->limit(3)
            ->get();

        return view('pengumuman.show', compact('pengumuman', 'latestPengumuman'));
    }

    public function edit(Pengumuman $pengumuman): View
    {
        return view('pengumuman.edit', compact('pengumuman'));
    }

    public function update(UpdatePengumumanRequest $request, Pengumuman $pengumuman): RedirectResponse
    {
        $data = $request->validated();

        if ($pengumuman->judul !== $data['judul']) {
            $data['slug'] = $this->uniqueSlug($data['judul'], $pengumuman);
        }

        if ($request->hasFile('gambar')) {
            if ($pengumuman->gambar) {
                Storage::disk('public')->delete($pengumuman->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('pengumuman', 'public');
        }

        if ($request->hasFile('file_lampiran')) {
            if ($pengumuman->file_lampiran) {
                foreach ($pengumuman->file_lampiran as $item) {
                    Storage::disk('public')->delete($item['path']);
                }
            }

            $lampiran = [];
            foreach ($request->file('file_lampiran') as $file) {
                $path = $file->store('pengumuman/lampiran', 'public');
                $lampiran[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $this->formatBytes($file->getSize()),
                ];
            }
            $data['file_lampiran'] = $lampiran;
        }

        $pengumuman->update($data);

        return redirect()->route('petugas.pengumuman.index')
            ->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy(Pengumuman $pengumuman): RedirectResponse
    {
        if ($pengumuman->gambar) {
            Storage::disk('public')->delete($pengumuman->gambar);
        }

        if ($pengumuman->file_lampiran) {
            foreach ($pengumuman->file_lampiran as $item) {
                Storage::disk('public')->delete($item['path']);
            }
        }

        $pengumuman->delete();

        return redirect()->route('petugas.pengumuman.index')
            ->with('success', 'Pengumuman berhasil dihapus.');
    }

    private function uniqueSlug(string $title, ?Pengumuman $ignore = null): string
    {
        $base = Str::slug($title) ?: Str::random(8);
        $slug = $base;
        $counter = 2;

        while (Pengumuman::query()
            ->where('slug', $slug)
            ->when($ignore, fn ($query) => $query->whereKeyNot($ignore->getKey()))
            ->exists()) {
            $slug = $base.'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    private function formatBytes(int $bytes, int $precision = 1): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision).' '.$units[$pow];
    }
}
