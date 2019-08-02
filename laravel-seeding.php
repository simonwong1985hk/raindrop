php artisan make:factory UserFactory --model=User
$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => bcrypt('passw0rd'),
        'remember_token' => Str::random(10),
    ];
});

php artisan make:seeder UsersTableSeeder
public function run()
{
    factory(App\User::class, 500)->create();
}

# /database/seeds/DatabaseSeeder.php
public function run()
{
    $this->call([
        UsersTableSeeder::class,
    ]);
}

composer dump-autoload

php artisan db:seed

php artisan migrate:refresh --seed
