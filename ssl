php artisan make:middleware HttpsProtocol

#app/Http/Middleware/HttpsProtocol.php
class HttpsProtocol
{
    public function handle($request, Closure $next)
    {
        if (!$request->secure() && app()->environment() === 'production') {
            return redirect()->secure($request->getRequestUri());
        }

        return $next($request);
    }
}

#app/Http/Kernel.php
protected $middlewareGroups = [
    'web' => [
        ...
        \App\Http\Middleware\HttpsProtocol::class,
    ],
];

#.env
APP_ENV=production
