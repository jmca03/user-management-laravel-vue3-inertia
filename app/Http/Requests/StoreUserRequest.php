<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
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
            'prefixname' => 'nullable|string|max:255',
            'firstname' => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'lastname' => 'required|string|max:255',
            'suffixname' => 'nullable|string|max:255',
            'username' => 'required|string|unique:users,username|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Password::defaults()],
            'photo' => 'nullable|file|image|max:2048', // Max size 2MB
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
            'prefixname.max' => 'Prefix name cannot exceed 255 characters.',
            'firstname.required' => 'First name is required.',
            'middlename.max' => 'Middle name cannot exceed 255 characters.',
            'lastname.required' => 'Last name is required.',
            'suffixname.max' => 'Suffix name cannot exceed 255 characters.',
            'username.required' => 'Username is required.',
            'username.unique' => 'This username is already taken.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email is already registered.',
            'password.required' => 'Password is required.',
            'password.confirmed' => 'Passwords do not match.',
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
            $validated['photo'] = app(UserService::class)->upload($this->file('photo'));
        }

        return [
            ...$validated
        ];
    }
}
