<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterAkunRequest;
use App\Http\Requests\Auth\RegisterDataDiriRequest;
use App\Models\Anggota;
use App\Models\EKartuAnggota;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function storeDataDiri(RegisterDataDiriRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $fotoPath = session('registration.foto_path');

        if ($request->hasFile('foto')) {
            if ($fotoPath) {
                Storage::disk('public')->delete($fotoPath);
            }

            $fotoPath = $request->file('foto')->store('registrasi/temp', 'public');
        }

        unset($data['foto']);

        session([
            'registration.data_diri' => $data,
            'registration.foto_path' => $fotoPath,
        ]);

        return redirect()->route('register.akun');
    }

    public function createAkun(): View|RedirectResponse
    {
        if (! session()->has('registration.data_diri')) {
            return redirect()->route('register');
        }

        return view('auth.register-akun', [
            'dataDiri' => session('registration.data_diri'),
            'fotoPath' => session('registration.foto_path'),
        ]);
    }

    public function store(RegisterAkunRequest $request): RedirectResponse
    {
        if (! session()->has('registration.data_diri')) {
            return redirect()->route('register');
        }

        $data = array_merge(
            session('registration.data_diri'),
            $request->validated()
        );
        $fotoTempPath = session('registration.foto_path');
        $fotoPath = null;

        try {
            if ($fotoTempPath) {
                $fotoPath = 'anggota/foto/'.basename($fotoTempPath);
                Storage::disk('public')->copy($fotoTempPath, $fotoPath);
            }

            $user = DB::transaction(function () use ($data, $fotoPath): User {
                $roleAnggota = Role::firstOrCreate(
                    ['nama_role' => 'Anggota'],
                    ['deskripsi' => 'Pengguna umum yang dapat mengakses layanan perpustakaan']
                );

                $user = User::create([
                    'id_role' => $roleAnggota->id_role,
                    'name' => $data['nama_lengkap'],
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
                    'no_telepon' => $data['no_telepon'],
                    'foto' => $fotoPath,
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
        } catch (Throwable $exception) {
            if ($fotoPath) {
                Storage::disk('public')->delete($fotoPath);
            }

            throw $exception;
        }

        event(new Registered($user));

        Auth::login($user);
        session()->forget('registration');

        if ($fotoTempPath) {
            Storage::disk('public')->delete($fotoTempPath);
        }

        return redirect()
            ->route('register.e-kartu')
            ->with('status', 'registration-success');
    }

    public function showEKartu(): View|RedirectResponse
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (! $user || ! $user->isAnggota()) {
            return redirect()->route('login');
        }

        $anggota = $user->anggota()->with('eKartuAnggota')->first();
        if (! $anggota) {
            return redirect()->route('register');
        }

        $eKartu = $anggota->eKartuAnggota;

        return view('auth.register-e-kartu', compact('anggota', 'eKartu'));
    }
}
