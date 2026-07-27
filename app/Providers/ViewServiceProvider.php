<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Notification;

class ViewServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {

            $notifications = collect();

            if (Auth::check()) {
                $notifications = Notification::where('user_id', Auth::id())->where('is_read', 0)->get();
            }

            $view->with([
                'notifications' => $notifications,
                'notificationCounter' => $notifications->count(),
            ]);
        });
    }
}