php artisan make:seeder UsersTableSeeder
# database/seeds/UsersTableSeeder.php
public function run()
{
    factory(App\User::class, 500)->create();
}

# database/seeds/DatabaseSeeder.php
public function run()
{
    $this->call(UsersTableSeeder::class);
}

php artisan migrate --seed

php artisan make:controller API/UserController --api --model=User

# routes/api.php
Route::apiResource('users', 'API\UserController');

# app/Http/Controllers/API/UserController.php

# GET: api/users
public function index()
{
    return User::all();
}

# POST: api/users
public function store(Request $request)
{
    return User::create($request->all());
}

# GET: api/users/{user}
public function show(User $user)
{
    return $user;
}

# PUT: api/users/{user}
public function update(Request $request, User $user)
{
    $user->update($request->all());

    return $user;
}

# DELETE: api/users/{user}
public function destroy(User $user)
{
    $user->delete();

    return 204;
}
