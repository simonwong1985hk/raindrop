composer require laravel/scout
php artisan vendor:publish --provider="Laravel\Scout\ScoutServiceProvider"
# config/scout.php
'queue' => true,

composer require algolia/algoliasearch-client-php:^2.2
# .env
ALGOLIA_APP_ID=YourApplicationID
ALGOLIA_SECRET=YourAdminAPIKey

# app/User.php
use Laravel\Scout\Searchable;
use Searchable;
public function searchableAs()
{
    return 'users_index';
}

php artisan tinker
factory(App\User::class, 500)->create();

php artisan scout:import "App\User"

php artisan make:controller SearchController
class SearchController extends Controller
{
    public function search(Request $request)
    {
    	if($request->has('search')){
    		$users = User::search($request->input('search'))->paginate(50);	
    	}else{
    		$users = User::paginate(50);
    	}
        return view('index', compact('users'));
    }
}
