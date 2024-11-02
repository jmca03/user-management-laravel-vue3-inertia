<?php

namespace App\Services;

use App\Enums\UserDetailKey;
use App\Enums\UserPrefix;
use App\Interfaces\UserServiceInterface;
use App\Models\Detail;
use App\Models\User;
use App\Rules\ValidateUploadedPhoto;
use App\Traits\Pagination;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class UserService implements UserServiceInterface
{
    use Pagination;

    /**
     * Constructor to bind model to a repository.
     *
     * @param \App\Models\User $model
     */
    public function __construct(protected User $model, protected Detail $detail)
    {
        //
    }

    /**
     * Define the validation rules for the model.
     *
     * @param string|null $id
     *
     * @return array
     */
    public function rules(?string $id = null): array
    {
        $rules = [
            'prefixname' => 'nullable|string|max:255',
            'firstname'  => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'lastname'   => 'required|string|max:255',
            'suffixname' => 'nullable|string|max:255',
            'username'   => 'required|string|unique:users,username|max:255',
            'email'      => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'password'   => ['required', 'confirmed', Password::defaults()],
            'photo'      => 'nullable|file|image|max:2048', // Max size 2MB
        ];

        if ($id) {
            $rules['username'] = 'required|string|max:255|unique:users,username,' . $id;
            $rules['email'] = 'required|string|lowercase|email|max:255|unique:users,email,' . $id;
            $rules['password'] = ['nullable', 'confirmed', Password::defaults()];
            $rules['photo'] = [
                'nullable', new ValidateUploadedPhoto
            ];
        }

        return $rules;
    }

    /**
     * Retrieve all resources and paginate.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function list(Request $request): LengthAwarePaginator
    {
        $model = $this->model->query()->rowNumber();
        return $this->paginate($model, $request);
    }

    /**
     * Create model resource.
     *
     * @param array $attributes
     *
     * @return \App\Models\User|null
     */
    public function store(array $attributes): ?User
    {
        $model = null;
        try {
            $model = $this->model->create($attributes);
        } catch (\Exception $e) {
            report($e);
        } finally {
            return $model;
        }
    }

    /**
     * Retrieve model resource details.
     * Abort to 404 if not found.
     *
     * @param string $id
     *
     * @return \App\Models\User|null
     * @throws ModelNotFoundException
     */
    public function find(string $id): ?User
    {
        $model = null;
        try {
            $model = $this->model->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            report($e);
            abort(404);
        } catch (\Exception $e) {
            report($e);
        } finally {
            return $model;
        }
    }

    /**
     * Update model resource.
     *
     * @param string $id
     * @param array  $attributes
     *
     * @return \App\Models\User|null
     * @throws ModelNotFoundException
     */
    public function update(string $id, array $attributes): ?User
    {
        $model = null;

        try {
            DB::beginTransaction();

            $model = $this->model->findOrFail($id);
            $model->update($attributes);

            DB::commit();
        } catch (ModelNotFoundException $e) {
            abort(404);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
        } finally {
            return $model;
        }
    }

    /**
     * Soft delete model resource.
     *
     * @param string $id
     *
     * @return void
     * @throws ModelNotFoundException
     */
    public function destroy(string $id): void
    {
        try {
            DB::beginTransaction();

            if ((int)$id === auth()?->id()) {
                throw new BadRequestException('You cannot delete yourself.');
            }

            $model = $this->model->findOrFail($id);
            $model->delete();

            DB::commit();
        } catch (ModelNotFoundException $e) {
            abort(404);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
        }
    }

    /**
     * Include only soft deleted records in the results.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     * @throws ModelNotFoundException
     */
    public function listTrashed(Request $request): LengthAwarePaginator
    {
        $model = $this->model->onlyTrashed()->rowNumber();
        return $this->paginate($model, $request);
    }

    /**
     * Restore model resource.
     *
     * @param string $id
     *
     * @return void
     * @throws ModelNotFoundException
     */
    public function restore(string $id): void
    {
        try {
            DB::beginTransaction();

            $model = $this->model->withTrashed()->findOrFail($id);
            $model->restore();

            DB::commit();
        } catch (ModelNotFoundException $e) {
            abort(404);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
        }
    }

    /**
     * Permanently delete model resource.
     *
     * @param string $id
     *
     * @return void
     * @throws ModelNotFoundException
     */
    public function delete(string $id): void
    {
        try {
            DB::beginTransaction();

            $model = $this->model->withTrashed()->findOrFail($id);
            $model->forceDelete();

            DB::commit();
        } catch (ModelNotFoundException $e) {
            abort(404);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
        }
    }

    /**
     * Generate random hash key.
     *
     * @param string $key
     *
     * @return string
     */
    public function hash(string $key): string
    {
        return bcrypt($key);
    }

    /**
     * Upload the given file.
     *
     * @param \Illuminate\Http\UploadedFile $file
     *
     * @return string|null
     * @throws ModelNotFoundException
     */
    public function upload(UploadedFile $file): ?string
    {
        $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
        return $file->storeAs('photos', $fileName, 'public');
    }

    /**
     * Get User Prefixes
     *
     * @return array
     */
    public function userPrefixes(): array
    {
        return array_map(function ($prefix) {
            return [
                'label' => $prefix,
                'value' => $prefix,
            ];
        }, array_column(UserPrefix::cases(), 'value'));
    }

    /**
     * Get initials
     *
     * @param ?string $value
     *
     * @return string
     */
    public function getInitials(?string $value): string
    {
        if (!$value) return '';
        $parts = explode(' ', $value);

        $initials = '';
        foreach ($parts as $part) {
            if (!empty($part)) {
                $initials .= strtoupper($part[0]) . '. '; // Get the first letter and convert to uppercase
            }
        }

        return trim($initials);
    }

    /**
     * Save Details
     *
     * @return void
     */
    public function saveDetails(User $user): void
    {
        $detailKeys = array_column(UserDetailKey::cases(), 'value');

        // Remove details that is no longer use.
        $this->detail->where('user_id', $user?->id)->whereNotIn('key', $detailKeys)->delete();

        $userDetails = $this->detail->where('user_id', $user?->id)->get()->toArray();

        foreach ($detailKeys as $key) {
            $detail = collect($userDetails)->where('key', $key)->first();

            $value = $user->{UserDetailKey::modelAttribute($key)};

            // If attribute does not exist, skip iteration
            if (!$value) {
                info("Attribute does not exist [key => {$key}]");
                continue;
            }

            if (!$detail) {
                $this->detail->create([
                    'user_id' => $user->id,
                    'key'     => $key,
                    'value'   => $value
                ]);
            } else {
                $id = data_get($detail, 'id');

                // If id does not exist, skip iteration
                if (!$id) {
                    info("Detail Id does not exists", $detail->toArray());
                    continue;
                }

                $newDetail = $this->detail->findOrFail(data_get($detail, 'id'));
                $newDetail->update([
                    'value' => $value
                ]);
            }
        }
    }
}
