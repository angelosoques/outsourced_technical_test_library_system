<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //insert
        '/insert/book',
        '/insert/member',
        '/insert/library',
        '/insert/book/borrow',
        //update
        '/update/book',
        '/update/book/borrow',
        '/update/library',
        '/update/member',
    ];
}
