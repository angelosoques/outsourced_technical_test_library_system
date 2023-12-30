<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'ISBN',
        'book_title',
        'book_author',
        'number_of_copies',
        'library_id',
    ];
}
