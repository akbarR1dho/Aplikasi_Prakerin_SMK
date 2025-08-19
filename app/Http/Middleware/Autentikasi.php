<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Autentikasi extends Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return mixed
     */
    public function handle($request, Closure $next, ...$guards)
    {
        // Jika user tidak terautentikasi
        if (!$this->auth->guard(...$guards)->check()) {
            return $this->redirectToCustom($request, $guards);
        }

        return $next($request);
    }

    /**
     * Custom redirect ketika unauthenticated
     */
    protected function redirectToCustom($request, array $guards)
    {
        // Redirect khusus untuk API
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // Redirect berdasarkan guard yang digunakan
        if (in_array('admin', $guards)) {
            return redirect()->guest(route('admin.login'));
        }

        // Redirect default untuk auth biasa
        return redirect()->guest(route('login'))->with('error', 'Anda harus login terlebih dahulu')->withInput();
    }
}
