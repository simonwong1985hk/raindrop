<?php
/**
 * 1 Scout
 * composer require laravel/scout
 * php artisan vendor:publish --provider="Laravel\Scout\ScoutServiceProvider"
 * config/scout.php
 */
'queue' => true,




/**
 * 2 Algolia
 * composer require algolia/algoliasearch-client-php
 * https://www.algolia.com
 * .env
 */
ALGOLIA_APP_ID=YourApplicationID
ALGOLIA_SECRET=YourAdminAPIKey




/**
 * 3 Model
 * php artisan tinker
 * factory(App\User::class, 500)->create();
 * app/Models/User.php
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class User extends Model
{
    use Searchable;
}

php artisan scout:import "App\Models\User"




/**
 * 4 Controller
 * php artisan make:controller SearchController
 */
class SearchController extends Controller
{
    public function search(Request $request)
    {
    	if($request->has('query')){
    		$users = User::search($request->input('query'))->paginate(10);
    	}else{
    		$users = User::paginate(10);
    	}
        return view('index', ['users' => $users]);
    }
}




/**
 * 5 View
 * resources/views/search.blade.php
 */
@foreach ($users as $user)
    {{ $user->email }}
@endforeach

{{ $users->links() }}
