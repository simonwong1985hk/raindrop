/**
 * Structure - /resources/lang/[en,hk]/messages.php
 */
<?php
return [
    'language' => 'English'
];

<?php
return [
    'language' => '繁體中文'
];

/**
 * Middleware - /app/Http/Middleware/LocaleMiddleware.php
 */
php artisan make:middleware LocaleMiddleware

public function handle($request, Closure $next)
{
	if (session()->has('locale')) {
	    app()->setLocale(session('locale'));
	} else {
	    session(['locale' => config('app.locale')]);
	    app()->setLocale(config('app.locale'));
	}

	return $next($request);
}

#/app/Http/Kernel.php
protected $middlewareGroups = [
    'web' => [
    	...
        \App\Http\Middleware\LocaleMiddleware::class,
    ],
];

/**
 * Controller - /app/Http/Controller/LocaleController.php
 */
php artisan make:controller LocaleController

public function index($locale)
{
    session(['locale' => $locale]);

    return back();
}

/**
 * Route - /routes/web.php
 */
Route::get('{locale}', 'LocaleController@index')->name('locale.index');

/**
 * View - /resources/views/welcome.blade.php
 */
<a href="{{ route('locale.index', ['locale' => 'en']) }}">English</a>
<a href="{{ route('locale.index', ['locale' => 'hk']) }}">繁體中文</a>
{{ __('messages.language') }}

