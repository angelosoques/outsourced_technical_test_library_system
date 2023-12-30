<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Member;
use App\Models\Book;

class matchLibraryId implements ValidationRule
{
    protected $memberId, $bookIds;

    public function __construct($memberId, $bookIds) {
        $this->memberId = $memberId;
        $this->bookIds = $bookIds;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        foreach ($this->bookIds as $bookId) {
            $bookLibraryId = Book::where('id', $bookId)->select('library_id')->get();
            $memberLibraryId = Book::where('id', $this->memberId)->select('library_id')->get();
            
            if ($bookLibraryId !== $value && $memberLibraryId !== $value && $bookLibraryId !== $memberLibraryId) {
                $fail('member library and book library does not match');
            }
        }
    }
}
