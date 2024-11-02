<?php

namespace App\Http\Requests;

use App\Services\UserService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserRequest extends FormRequest
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
        return app(UserService::class)->rules($this->route('user'));
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

        if (!$validated['password']) {
            unset($validated['password']);
        }

        if (isset($validated['password'])) {
            $validated['password'] = app(UserService::class)->hash($validated['password']);
        }

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
