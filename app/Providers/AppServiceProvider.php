<?php

namespace App\Providers;

use App\Models\Anggota;
use App\Models\Notifikasi;
use App\Models\SanksiAnggota;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.public_navbar', function ($view) {
            $publicMemberStatusBadge = null;

            if (Auth::check()) {
                $user = Auth::user();
                $user->loadMissing([
                    'role',
                    'anggota.sanksi' => function (Builder|HasMany $query): void {
                        $this->applyActiveSanksiFilter($query);
                        $query->latest('id_sanksi_anggota');
                    },
                ]);

                $unreadNotificationsCount = Notifikasi::where('id_user', $user->id_user)
                    ->whereIn('status_baca', ['belum_dibaca', 'Belum Dibaca'])
                    ->count();

                $latestNotifications = Notifikasi::where('id_user', $user->id_user)
                    ->latest('dikirim_pada')
                    ->latest('id_notifikasi')
                    ->take(5)
                    ->get();

                if ($user->isAnggota()) {
                    $publicMemberStatusBadge = $this->resolvePublicMemberStatusBadge($user->anggota);
                }
            } else {
                $unreadNotificationsCount = 0;
                $latestNotifications = collect();
            }

            $view->with(compact('unreadNotificationsCount', 'latestNotifications', 'publicMemberStatusBadge'));
        });
    }

    /**
     * @return array{label: string, class: string, dot_class: string}|null
     */
    private function resolvePublicMemberStatusBadge(?Anggota $anggota): ?array
    {
        if (! $anggota) {
            return null;
        }

        if (strtolower((string) $anggota->status_anggota) !== 'aktif') {
            return [
                'label' => 'Nonaktif',
                'class' => 'border-rose-400/35 bg-rose-400/10 text-rose-200',
                'dot_class' => 'bg-rose-400',
            ];
        }

        $activeSanksi = $this->activeSanksiForPublicNavbar($anggota);

        if ($activeSanksi && stripos((string) $activeSanksi->jenis_sanksi, 'Blokir') !== false) {
            return [
                'label' => 'Diblokir',
                'class' => 'border-rose-400/35 bg-rose-400/10 text-rose-200',
                'dot_class' => 'bg-rose-400',
            ];
        }

        if ($activeSanksi) {
            return [
                'label' => 'Sedang Sanksi',
                'class' => 'border-amber-300/40 bg-amber-300/10 text-amber-200',
                'dot_class' => 'bg-amber-300',
            ];
        }

        return [
            'label' => 'Aktif',
            'class' => 'border-emerald-400/30 bg-emerald-400/10 text-emerald-300',
            'dot_class' => 'bg-emerald-400',
        ];
    }

    private function activeSanksiForPublicNavbar(Anggota $anggota): ?SanksiAnggota
    {
        return $anggota->sanksi->first(
            fn (SanksiAnggota $sanksi): bool => stripos((string) $sanksi->jenis_sanksi, 'Blokir') !== false
        ) ?? $anggota->sanksi->first();
    }

    private function applyActiveSanksiFilter(Builder|HasMany $query): void
    {
        $query->where('status_sanksi', 'aktif')
            ->where(function (Builder $query): void {
                $query->whereNull('tanggal_selesai')
                    ->orWhereDate('tanggal_selesai', '>=', today());
            });
    }
}
