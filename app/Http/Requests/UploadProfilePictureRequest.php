<?php

namespace App\Http\Requests;

use App\Rules\ValidateUploadedPhoto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class UploadProfilePictureRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'photo' => [
                'nullable', new ValidateUploadedPhoto
            ], // Max size 2MB
        ];
    }

    /**
     * @override messages
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'photo.file' => 'Uploaded photo must be a file.',
            'photo.image' => 'Uploaded photo must be an image.',
            'photo.max' => 'Photo cannot exceed 2MB.',
        ];
    }

    /**
     * @override validated
     *
     * @param $key
     * @param $default
     *
     * @return array
     */
    public function validated($key = null, $default = null): array
    {
        $validated = parent::validated();

        // Upload and get path of the profile picture
        if ($this->hasFile('photo')) {
            $fileName = Str::uuid() . '.' . $this->file('photo')->getClientOriginalExtension();
            $validated['photo'] = $this->file('photo')->storeAs('photos', $fileName, 'public');
        }

        return [
            ...$validated
        ];
    }
}
