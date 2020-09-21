# database/migrations/2014_10_12_000000_create_users_table.php
<?php

public function up()
{
    Schema::create('users', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->string('name');
        $table->string('email')->unique();
        $table->timestamp('email_verified_at')->nullable();
        $table->string('password');
        $table->rememberToken();
        $table->timestamps();
    });
    
    DB::statement('ALTER TABLE users ADD FULLTEXT search (name, email)');
}

?>

php artisan migrate

# app/FullTextSearch.php
<?php

namespace App;

trait FullTextSearch
{
    // Replaces spaces with full text search wildcards
    protected function fullTextWildcards($term)
    {

        $reservedSymbols = ['-', '+', '<', '>', '@', '(', ')', '~'];
        $term = str_replace($reservedSymbols, '', $term);

        $words = explode(' ', $term);

        foreach($words as $key => $word) {
            if(strlen($word) >= 3) {
                $words[$key] = '+' . $word . '*';
            }
        }

        $searchTerm = implode(' ', $words);

        return $searchTerm;
    }

    // Scope a query that matches a full text search of term
    public function scopeSearch($query, $term)
    {
        $columns = implode(',', $this->searchable);

        $query->whereRaw("MATCH ({$columns}) AGAINST (? IN BOOLEAN MODE)" , $this->fullTextWildcards($term));
        // $query->whereRaw("MATCH ({$columns}) AGAINST (? IN NATURAL LANGUAGE MODE)" , $this->fullTextWildcards($term));

        return $query;
    }
}

?>

# app/Models/User.php
<?php
use App\FullTextSearch;

class User extends Authenticatable
{
    use FullTextSearch;

    protected $searchable = ['name', 'email'];
}

?>

php artisan make:controller SearchController

<?php

class SearchController extends Controller
{
    public function search(Request $request)
    {
    	if($request->has('query')){
            $query = $request->input('query');
    		$users = User::search($query)->paginate(50);
            return view('index', ['users' => $users]);
    	}else{
    		$users = User::paginate(50);
            return view('index', ['users' => $users]);
    	}
    }
}

?>

<?php

@foreach ($users as $user)
    <p>This is user {{ $user->id }}</p>
@endforeach

@isset($query)
    {{ $users->appends(['query' => $query])->links() }}
@else
    {{ $users->links() }}
@endisset

?>
