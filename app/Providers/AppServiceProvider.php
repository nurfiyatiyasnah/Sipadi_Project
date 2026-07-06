<?php

namespace App\Providers;

use App\Models\Notifikasi;
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
            if (Auth::check()) {
                $user = Auth::user();
                $unreadNotificationsCount = Notifikasi::where('id_user', $user->id_user)
                    ->whereIn('status_baca', ['belum_dibaca', 'Belum Dibaca'])
                    ->count();

                $latestNotifications = Notifikasi::where('id_user', $user->id_user)
                    ->latest('dikirim_pada')
                    ->latest('id_notifikasi')
                    ->take(5)
                    ->get();
            } else {
                $unreadNotificationsCount = 0;
                $latestNotifications = collect();
            }

            $view->with(compact('unreadNotificationsCount', 'latestNotifications'));
        });
    }
}
