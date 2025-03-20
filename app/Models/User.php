<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, SoftDeletes;

    public const ROLE_ADMIN = 'admin';
    public const ROLE_LIBRARIAN = 'librarian';
    public const ROLE_MEMBER = 'member';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'role'
    ];

    public function memberLoans()
    {
        return $this->hasMany(Loan::class, 'member_id');
    }

    public function librarianLoans()
    {
        return $this->hasMany(Loan::class, 'librarian_id');
    }

    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isLibrarian()
    {
        return $this->role === self::ROLE_LIBRARIAN;
    }


    public function isMember()
    {
        return $this->role === self::ROLE_MEMBER;
    }
}
