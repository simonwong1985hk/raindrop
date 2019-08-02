# database/migrations/2014_10_12_000000_create_users_table.php
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
    
    DB::statement('ALTER TABLE users ADD FULLTEXT fulltext_index (name, email)');
}

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

# app/User.php
use FullTextSearch;
protected $searchable = ['name', 'email'];
