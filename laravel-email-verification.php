# app/User.php
use Illuminate\Contracts\Auth\MustVerifyEmail;
class User extends Authenticatable implements MustVerifyEmail {
  ....
}

php artisan migrate

# routers/web.php
Auth::routes(['verify' => true]);

# 'auth' => \App\Http\Middleware\Authenticate::class,
# 'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
public function __construct()
{
  $this->middleware(['auth', 'verified']);
}

php artisan make:auth
# resources/views/auth/verify.blade.php
