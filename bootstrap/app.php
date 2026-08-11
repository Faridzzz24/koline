<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);

        // Exclude authentication and key action routes from CSRF token validation so they NEVER fail with 419 Page Expired
        $middleware->validateCsrfTokens(except: [
            'login',
            'logout',
            'register',
            'konsultasi/*/konfirmasi',
            'konsultasi/*/selesai',
            'konsultasi/*/batal',
            'pesanan/*/bayar',
            'keranjang/hapus/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (TokenMismatchException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Sesi telah diperbarui, silakan coba lagi.'], 419);
            }
            if (auth()->check()) {
                return redirect()->back()->withInput($request->except('_token', 'password'))->with('error', 'Sesi Anda telah diperbarui. Silakan ulangi tindakan Anda.');
            }
            return redirect()->route('login')->with('status', 'Sesi Anda telah diperbarui. Silakan masuk kembali.');
        });
    })->create();
