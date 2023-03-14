<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Log;

class VideoFile implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $mimeType = $value->getMimeType();
        Log::debug("Mime type is $mimeType");

        if(str_starts_with($mimeType, "video")) {
            $fail('The file must be a video file.');
        }

        if(!$value->isValid()) {
            $fail('The file is invalid.');
        }
    }
}
