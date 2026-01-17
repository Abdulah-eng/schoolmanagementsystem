<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;

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
        // Share unread message count with student views
        View::composer(['student.*', 'student.layouts.*', 'student.components.*'], function ($view) {
            $unreadCount = 0;
            if (Auth::check() && Auth::user()->role === 'student') {
                $unreadCount = Message::where('recipient_id', Auth::id())
                    ->where('is_read', '=', 0)
                    ->count();
            }
            $view->with('unreadMessageCount', $unreadCount);
        });
    }
}
