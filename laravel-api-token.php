php artisan make:migration --table=users adds_api_token_to_users_table

class AddsApiTokenToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('api_token', 60)->after('password')->unique()->nullable()->default(null);
        });
    }
    
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['api_token']);
        });
    }
}

# app/Http/Controllers/Auth/RegisterController.php
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

protected function create(array $data)
{
    return User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password']),
        'api_token' => Str::random(60),
    ]);
}

# routes/api.php
Route::post('register', 'Auth\RegisterController@register');
