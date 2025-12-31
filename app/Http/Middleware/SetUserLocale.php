<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SetUserLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Kiểm tra xem user có đăng nhập không
        if (Auth::check()) {
            // Lấy ngôn ngữ từ DB của user (mặc định là 'en' nếu null)
            $userLanguage = Auth::user()->language ?? config('app.locale');

            // Set ngôn ngữ cho ứng dụng trong request này
            App::setLocale($userLanguage);
        }

        return $next($request);
    }
}