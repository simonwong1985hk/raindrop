php artisan make:auth

# app/User.php
const ADMIN_TYPE = 'admin';
const DEFAULT_TYPE = 'default';
public function isAdmin()    {        
    return $this->type === self::ADMIN_TYPE;    
}

# database/migrations/2014_10_12_000000_create_users_table.php
$table->string('type')->default('default');

# app/Http/Controllers/Auth/RegisterController.php
protected function create(array $data)    {        
    return User::create([            
        'name' => $data['name'],
        'email' => $data['email'],            
        'password' => bcrypt($data['password']),            
        'type' => User::DEFAULT_TYPE,
    ]);    
}

php artisan make:middleware IsAdmin
<?php
namespace App\Http\Middleware;
use Closure;
class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(auth()->user()->isAdmin()) {
            return $next($request);
        }
        return redirect('home');
    }
}

# app/Http/Kernel.php
'is_admin' => \App\Http\Middleware\IsAdmin::class,

php artisan make:controller AdminController
class AdminController extends Controller
{
    public function __construct()
    {
    	$this->middleware(['auth', 'is_admin']);
    }
}
