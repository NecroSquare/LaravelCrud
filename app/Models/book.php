<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model 
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['title', 'description', 'authors', 'isbn'];

    public function categories()
    {
        return $this->belongsToMany(Categories::class, 'books_categories', 'book_id', 'category_id');
    }

    public function loans(){
        return $this->hasMany(Loan::class);
    }
}