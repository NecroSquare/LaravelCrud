<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = ['book_id', 'member_id', 'librarian_id', 'loan_at', 'returned_at', 'note'];

    // Disable timestamps
    public $timestamps = false;

    protected $attributes = [
        'librarian_id' => null, // ✅ Set default to NULL
    ];

    // Define the relationship with the Book model
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    // Define the relationship with the User model for the member
    public function member()
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    // Define the relationship with the User model for the librarian
    public function librarian()
    {
        return $this->belongsTo(User::class, 'librarian_id');
    }
}