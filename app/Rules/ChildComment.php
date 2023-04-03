<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Comment;
use Illuminate\Support\Facades\Log;

class ChildComment implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if($value == null) {
            return;
        }

        $comment = Comment::find($value)->first();

        if($comment == null) {
            $fail('Comment does not exist.');
        }

        if($comment->parent != null) {
            $fail('Comment is a reply.');
        }
    }
}
