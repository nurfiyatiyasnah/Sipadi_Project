<?php

namespace App\Http\Controllers;

use App\Models\Aduan;
use App\Models\ArsipAduan;
use App\Models\TanggapanAduan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AduanController extends Controller
{
    public function create(): View|RedirectResponse
    {
        $user = Auth::user();
        if (! $user) {
            return redirect()->route('login')->with('info', 'Silakan masuk terlebih dahulu untuk menyampaikan aduan.');
        }

        $anggota = $user->anggota;
        if (! $anggota) {
            abort(403, 'Akses ditolak. Hanya anggota terdaftar yang dapat mengirim aduan.');
        }

        return view('aduan.create', compact('anggota'));
    }

    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();
        if (! $user) {
            return redirect()->route('login')->with('info', 'Silakan masuk terlebih dahulu untuk menyampaikan aduan.');
        }

        $anggota = $user->anggota;
        if (! $anggota) {
            abort(403, 'Akses ditolak. Hanya anggota terdaftar yang dapat mengirim aduan.');
        }

        $request->validate([
            'kategori_aduan' => ['required', 'string', 'max:50'],
            'isi_aduan' => ['required', 'string'],
            'lampiran' => ['nullable', 'file', 'mimes:png,jpg,jpeg,pdf', 'max:5120'], // Max 5MB
        ], [
            'kategori_aduan.required' => 'Kategori aduan wajib dipilih.',
            'isi_aduan.required' => 'Isi aduan wajib diisi.',
            'lampiran.mimes' => 'Format lampiran harus berupa PNG, JPG, JPEG, atau PDF.',
            'lampiran.max' => 'Ukuran lampiran maksimal 5MB.',
        ]);

        // Generate sequential ticket code AD-YYYY-MM-XXX
        $prefix = 'AD-'.now()->format('Y-m');
        $count = Aduan::where('kode_aduan', 'like', $prefix.'-%')->count();
        $ticketCode = $prefix.'-'.str_pad($count + 1, 3, '0', STR_PAD_LEFT);

        $lampiranPath = null;
        if ($request->hasFile('lampiran')) {
            $lampiranPath = $request->file('lampiran')->store('lampiran_aduan', 'public');
        }

        Aduan::create([
            'kode_aduan' => $ticketCode,
            'id_anggota' => $anggota->id_anggota,
            'subjek' => Str::limit($request->isi_aduan, 100),
            'isi_aduan' => $request->isi_aduan,
            'kategori_aduan' => $request->kategori_aduan,
            'lampiran' => $lampiranPath,
            'status_aduan' => 'terkirim',
            'prioritas' => 'sedang',
        ]);

        return redirect()->route('aduan.track', ['ticket' => $ticketCode])
            ->with('success', 'Aduan berhasil dikirim. Catat nomor tiket Anda: '.$ticketCode);
    }

    public function track(Request $request): View
    {
        $ticketCode = $request->input('ticket');
        $aduan = null;
        $tanggapan = null;

        if ($ticketCode) {
            $aduan = Aduan::with(['anggota.user', 'tanggapan.petugas'])
                ->where('kode_aduan', trim($ticketCode))
                ->first();
        }

        return view('aduan.track', compact('aduan', 'ticketCode'));
    }

    // ==========================================
    // PETUGAS / ADMIN FEATURES
    // ==========================================

    public function indexPetugas(Request $request): View
    {
        $status = $request->input('status');
        $search = $request->input('search');

        // We show all active complaints (or filtered). Image 1 has status filter.
        $query = Aduan::with(['anggota.user', 'arsip']);

        if ($status) {
            if ($status === 'menunggu') {
                $query->where('status_aduan', 'terkirim');
            } elseif ($status === 'ditanggapi') {
                $query->whereIn('status_aduan', ['diproses', 'selesai']);
            } elseif ($status === 'diarsipkan') {
                $query->has('arsip');
            }
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('kode_aduan', 'like', "%{$search}%")
                    ->orWhereHas('anggota', function ($sub) use ($search) {
                        $sub->where('nama_lengkap', 'like', "%{$search}%");
                    });
            });
        }

        $aduanList = $query->latest('id_aduan')->paginate(10);

        return view('petugas.aduan.index', compact('aduanList', 'status', 'search'));
    }

    public function arsipPetugas(Request $request): View
    {
        $search = $request->input('search');
        $query = Aduan::has('arsip')->with(['anggota.user', 'arsip.diarsipkanOleh']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('kode_aduan', 'like', "%{$search}%")
                    ->orWhereHas('anggota', function ($sub) use ($search) {
                        $sub->where('nama_lengkap', 'like', "%{$search}%");
                    });
            });
        }

        $aduanList = $query->latest('id_aduan')->paginate(10);

        return view('petugas.aduan.arsip', compact('aduanList', 'search'));
    }

    public function showPetugas(Aduan $aduan): View
    {
        $aduan->load(['anggota.user', 'tanggapan.petugas.user', 'arsip']);

        $anggota = $aduan->anggota;
        // Count previous complaints by the same member
        $riwayatCount = Aduan::where('id_anggota', $anggota->id_anggota)
            ->where('id_aduan', '!=', $aduan->id_aduan)
            ->count();

        return view('petugas.aduan.show', compact('aduan', 'anggota', 'riwayatCount'));
    }

    public function createTanggapan(Aduan $aduan): View
    {
        $aduan->load(['anggota.user']);

        return view('petugas.aduan.tanggapi', compact('aduan'));
    }

    public function storeTanggapan(Request $request, Aduan $aduan): RedirectResponse
    {
        $petugas = Auth::user()->petugas;
        if (! $petugas) {
            abort(403, 'Akses ditolak. Hanya petugas yang dapat merespon aduan.');
        }

        $request->validate([
            'status_aduan' => ['required', 'string', 'in:diproses,selesai'],
            'isi_tanggapan' => ['required', 'string'],
        ], [
            'status_aduan.required' => 'Status aduan wajib diubah.',
            'isi_tanggapan.required' => 'Pesan tanggapan wajib diisi.',
        ]);

        TanggapanAduan::create([
            'id_aduan' => $aduan->id_aduan,
            'id_petugas' => $petugas->id_petugas,
            'isi_tanggapan' => $request->isi_tanggapan,
            'status_setelah_respon' => $request->status_aduan,
            'ditanggapi_pada' => now(),
        ]);

        $aduan->update([
            'status_aduan' => $request->status_aduan,
        ]);

        return redirect()->route('petugas.aduan.show', $aduan)
            ->with('success', 'Tanggapan berhasil dikirim dan status aduan diperbarui.');
    }

    public function toggleArsip(Request $request, Aduan $aduan): RedirectResponse
    {
        $petugas = Auth::user()->petugas;
        if (! $petugas) {
            abort(403);
        }

        $aduan->load('arsip');

        if ($aduan->arsip) {
            $aduan->arsip->delete();
            $aduan->update(['status_aduan' => 'diproses']);
            $message = 'Aduan berhasil dikembalikan dari arsip.';
        } else {
            ArsipAduan::create([
                'id_aduan' => $aduan->id_aduan,
                'diarsipkan_oleh' => $petugas->id_petugas,
                'diarsipkan_pada' => now(),
            ]);
            $aduan->update(['status_aduan' => 'selesai']); // Set to resolved / finished
            $message = 'Aduan berhasil diarsipkan.';
        }

        return redirect()->route('petugas.aduan.show', $aduan)->with('success', $message);
    }
}
