<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterAnggotaRequest;
use App\Models\Anggota;
use App\Models\EKartuAnggota;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(RegisterAnggotaRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $user = DB::transaction(function () use ($data): User {
            $roleAnggota = Role::firstOrCreate(
                ['nama_role' => 'Anggota'],
                ['deskripsi' => 'Pengguna umum yang dapat mengakses layanan perpustakaan']
            );

            $user = User::create([
                'id_role' => $roleAnggota->id_role,
                'email' => $data['email'],
                'password' => $data['password'],
                'status_akun' => 'aktif',
            ]);

            $anggota = Anggota::create([
                'id_user' => $user->id_user,
                'no_anggota' => $data['nik'],
                'nik' => $data['nik'],
                'nama_lengkap' => $data['nama_lengkap'],
                'jenis_kelamin' => $data['jenis_kelamin'],
                'tanggal_lahir' => $data['tanggal_lahir'],
                'alamat' => $data['alamat'],
                'tanggal_daftar' => now()->toDateString(),
                'status_anggota' => 'aktif',
            ]);

            EKartuAnggota::create([
                'id_anggota' => $anggota->id_anggota,
                'no_anggota' => $anggota->no_anggota,
                'kalangan' => config('sipadi.keanggotaan.kalangan_default'),
                'barcode' => (string) Str::uuid(),
                'masa_berlaku' => now()->addYears((int) config('sipadi.keanggotaan.masa_berlaku_tahun')),
            ]);

            return $user;
        });

        event(new Registered($user));

        Auth::login($user);

        return redirect()
            ->route('anggota.e-kartu')
            ->with('status', 'registration-success');
    }
}
