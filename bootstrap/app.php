<?php

use App\Http\Middleware\EnsureAdmin;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
// use \Throwable;
use Illuminate\Support\Facades\Log;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => EnsureAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();

// ->withExceptions(function (Exceptions $exceptions) {
//     // Merender ulang semua error/exception
//     $exceptions->render(function (Throwable $e) {

//         // 1. Membuat log error secara otomatis dengan pesan detail
//         Log::error('Terjadi error sistem: ' . $e->getMessage(), [
//             'exception' => $e,
//             'url' => request()->fullUrl(),
//             'input' => request()->all(),
//         ]);

//         // 2. Alihkan pengguna ke halaman custom error
//         return redirect()->route('error.wrong');
//     });
// })-> create();
