<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\DetailPeminjaman;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user()->load(['anggota', 'petugas']);

        if ($user->isAnggota() && $user->anggota) {
            $anggota = $user->anggota;

            // 1. Buku Dipinjam: Count total books in active loans
            $bukuDipinjamCount = DetailPeminjaman::whereHas('peminjaman', function ($q) use ($anggota) {
                $q->where('id_anggota', $anggota->id_anggota)->where('status_peminjaman', 'aktif');
            })->count();

            // 2. Keterlambatan: Count total overdue records
            $keterlambatanCount = $anggota->keterlambatan()->count();

            return view('profile.anggota', compact(
                'user',
                'anggota',
                'bukuDipinjamCount',
                'keterlambatanCount'
            ));
        }

        return view('profile.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($request, $data): void {
            /** @var User $user */
            $user = $request->user();
            $user->fill(['email' => $data['email']]);

            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }

            $user->save();

            $user->anggota?->update(['nama_lengkap' => $data['name']]);
            $user->petugas?->update(['nama_petugas' => $data['name']]);
        });

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        /** @var User $user */
        $user = $request->user();

        Auth::logout();

        DB::transaction(function () use ($user): void {
            $user->anggota?->eKartuAnggota()->delete();
            $user->anggota?->delete();
            $user->petugas?->delete();
            $user->delete();
        });

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
