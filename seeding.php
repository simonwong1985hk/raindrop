php artisan make:factory UserFactory --model=User
# /database/factories/UserFactory.php
$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
    ];
});

php artisan make:seeder UsersTableSeeder
# /database/seeds/UsersTableSeeder
public function run()
{
    factory(App\User::class, 500)->create();
    
    # For relationship
    factory(App\User::class, 50)->create()->each(function ($user) {
        $user->posts()->save(factory(App\Post::class)->make());
    });
}

# /database/seeds/DatabaseSeeder.php
public function run()
{
    $this->call([
        UsersTableSeeder::class,
        PostsTableSeeder::class,
        CommentsTableSeeder::class,
    ]);
}

composer dump-autoload

php artisan db:seed # For all

php artisan db:seed --class=UsersTableSeeder # For one

php artisan migrate:refresh --seed # For all new

php artisan db:seed --force # Force in production
