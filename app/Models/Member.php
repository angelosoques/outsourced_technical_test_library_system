<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'email_address',
        'password',
        'first_name',
        'last_name',
        'address',
        'contact_no',
        'library_id',
    ];
}
