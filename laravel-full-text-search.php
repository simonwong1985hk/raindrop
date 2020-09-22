<?php
/**
 * 1
 * database/migrations/2014_10_12_000000_create_users_table.php
 */
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

    // The built-in MySQL full-text parser uses the white space between words as a delimiter to determine where words begin and end, which is a limitation when working with ideographic languages that do not use word delimiters. To address this limitation, MySQL provides an ngram full-text parser that supports Chinese, Japanese, and Korean (CJK). The ngram full-text parser is supported for use with InnoDB and MyISAM.
    DB::statement('ALTER TABLE users ADD FULLTEXT search (name, email) WITH PARSER ngram');
}




/**
 * 2
 * app/FullTextSearch.php
 */
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

        $query->whereRaw("MATCH ({$columns}) AGAINST (? IN NATURAL LANGUAGE MODE)" , $this->fullTextWildcards($term));

        return $query;
    }
}




/**
 * 3
 * app/Models/User.php
 */
use App\FullTextSearch;

class User extends Authenticatable
{
    use FullTextSearch;

    protected $searchable = ['name', 'email'];
}




/**
 * 4
 * app/Http/Controllers/SearchController.php
 */
class SearchController extends Controller
{
    public function search(Request $request)
    {
    	if($request->has('query')){
            $query = $request->input('query');
    		$users = User::search($query)->paginate(50);
            return view('search', ['users' => $users]);
    	}else{
    		$users = User::paginate(50);
            return view('search', ['users' => $users]);
    	}
    }
}




/**
 * 5
 * resources/views/search.blade.php
 */
@foreach ($users as $user)
    <p>This is user {{ $user->id }}</p>
@endforeach

@isset($query)
    {{ $users->appends(['query' => $query])->links() }}
@else
    {{ $users->links() }}
@endisset

