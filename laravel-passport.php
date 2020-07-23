<?php

/**
 * Refer to https://www.twilio.com/blog/build-secure-api-php-laravel-passport
 */

# $ composer require laravel/passport

# $ php artisan migrate

# $ php artisan passport:install

// app/User.php
use Laravel\Passport\HasApiTokens; // Add this

class User extends Authenticatable
{
    use Notifiable, HasApiTokens; // Update this line
}

// app/Providers/AuthServiceProvider.php
use Laravel\Passport\Passport; // Add this

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
         'App\Model' => 'App\Policies\ModelPolicy', // Uncomment this line
    ];

    public function boot()
    {
        $this->registerPolicies();

        Passport::routes(); // Add this
    }
}

// config/auth.php
return [
    ...

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'api' => [
            'driver' => 'passport', // Set this to passport
            'provider' => 'users',
            'hash' => false,
        ],
    ],

    ...
];

# $ php artisan make:model CEO -m

// database/migrations/****_**_**_******_create_c_e_o_s_table.php
class CreateCEOSTable extends Migration
{
    public function up()
    {
        Schema::create('c_e_o_s', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('company_name');
            $table->year('year');
            $table->string('company_headquarters');
            $table->string('what_company_does');
            $table->timestamps();
        });
    }
}

// app/CEO.php
class CEO extends Model
{
    protected $fillable = [ 'name', 'company_name', 'year', 'company_headquarters', 'what_company_does' ];
}

# $ php artisan migrate

# $ php artisan make:controller Api/AuthController

// app/Http/Controllers/Api/AuthController.php
use App\User; // Add this

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:55',
            'email' => 'email|required|unique:users',
            'password' => 'required|confirmed'
        ]);

        $validatedData['password'] = bcrypt($request->password);

        $user = User::create($validatedData);

        $accessToken = $user->createToken('authToken')->accessToken;

        return response([ 'user' => $user, 'access_token' => $accessToken]);
    }

    public function login(Request $request)
    {
        $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if (!auth()->attempt($loginData)) {
            return response(['message' => 'Invalid Credentials']);
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        return response(['user' => auth()->user(), 'access_token' => $accessToken]);

    }
}

# $ php artisan make:controller Api/CEOController --api --model=CEO

// app/Http/Controllers/Api/CEOController.php
use App\Http\Resources\CEOResource;
use Illuminate\Support\Facades\Validator;

class CEOController extends Controller
{
	// index
    public function index()
    {
        $ceos = CEO::all();
        return response([ 'ceos' => CEOResource::collection($ceos), 'message' => 'Retrieved successfully'], 200);
    }

    // store
    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required|max:255',
            'year' => 'required|max:255',
            'company_headquarters' => 'required|max:255',
            'what_company_does' => 'required'
        ]);

        if($validator->fails()){
            return response(['error' => $validator->errors(), 'Validation Error']);
        }

        $ceo = CEO::create($data);

        return response([ 'ceo' => new CEOResource($ceo), 'message' => 'Created successfully'], 200);
    }

    // show
    public function show(CEO $ceo)
    {
        return response([ 'ceo' => new CEOResource($ceo), 'message' => 'Retrieved successfully'], 200);
    }

    // update
    public function update(Request $request, CEO $ceo)
    {

        $ceo->update($request->all());

        return response([ 'ceo' => new CEOResource($ceo), 'message' => 'Updated successfully'], 200);
    }

    // destroy
    public function destroy(CEO $ceo)
    {
        $ceo->delete();

        return response(['message' => 'Deleted successfully']);
    }
}

# $ php artisan make:resource CEOResource

// routes/api.php
Route::post('/register', 'Api\AuthController@register');
Route::post('/login', 'Api\AuthController@login');
Route::apiResource('/ceo', 'Api\CEOController')->middleware('auth:api');
