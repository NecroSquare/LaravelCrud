<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categories extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name'];

    public function books() 
    {
        return $this->belongsToMany(book::class, 'books_categories', 'category_id', 'book_id');
    }
}