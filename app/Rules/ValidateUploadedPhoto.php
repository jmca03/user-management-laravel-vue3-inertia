<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

class ValidateUploadedPhoto implements ValidationRule
{
    /**
     * Create an instance of the rule class.
     *
     * @param int $maxSize
     */
    public function __construct(protected int $maxSize = 2048)
    {

    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value instanceof UploadedFile) {
            // Check if the file is an image
            if (!$value->isValid() || !$value->isFile()) {
                $fail('File is not a valid file.');
            }

            // Check if the file is an image by its MIME type
            $mimeType = $value->getClientMimeType();
            $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];

            if (!in_array($mimeType, $allowedMimeTypes)) {
                $fail('File is not a valid image.');
            }

            // Check the maximum file size
            if ($value->getSize() > $this->maxSize * 1024) { // Convert to bytes
                $fail('File is too large.');
            }
        }
    }
}
