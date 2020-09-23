<?php
/**
 * php artisan make:factory UserFactory --model=User
 */
use App\Models\User;
use Illuminate\Support\Str;

public function definition()
{
    return [
        'name' => $this->faker->name,
        'email' => $this->faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => bcrypt('PASSWORD'),
        'remember_token' => Str::random(10),
    ];
}




/**
 * php artisan make:seeder UserSeeder
 */
use App\Models\User;

public function run()
{
    User::truncate();

    factory(User::class, 500)->create();
}




/**
 * /database/seeds/DatabaseSeeder.php
 */
use Illuminate\Support\Facades\Schema;

public function run()
{
    Schema::disableForeignKeyConstraints();

    $this->call([
        UserSeeder::class,
    ]);

    Schema::enableForeignKeyConstraints();
}




/**
 * composer dump-autoload
 */




/**
 * php artisan db:seed
 */
