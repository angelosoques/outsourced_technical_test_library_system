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
            $bookLibraryId = Book::find($bookId);
            $memberLibraryId = Member::find($this->memberId);
            
            if ($bookLibraryId->library_id !== $value || $memberLibraryId->library_id !== $value || $bookLibraryId->library_id !== $memberLibraryId->library_id) {
                $fail('member library and book library does not match');
            }
        }
    }
}
