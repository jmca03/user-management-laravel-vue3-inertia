<?php

namespace App\Services;

use App\Interfaces\UserServiceInterface;
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

class UserService implements UserServiceInterface
{
    use Pagination;

    /**
     * Constructor to bind model to a repository.
     *
     * @param \App\Models\User $model
     */
    public function __construct(protected User $model)
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
            'firstname' => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'lastname' => 'required|string|max:255',
            'suffixname' => 'nullable|string|max:255',
            'username' => 'required|string|unique:users,username|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Password::defaults()],
            'photo' => 'nullable|file|image|max:2048', // Max size 2MB
        ];

        if ($id) {
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
     * @param  integer $id
     * @param  array   $attributes
     * @return boolean
     */
    public function update(string $id, array $attributes): ?User
    {
        $model = null;

       try {
           DB::beginTransaction();

           $model = $this->model->findOrFail($id);
           $model->update($attributes);

           DB::commit();
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
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $this->model->delete($id);

            DB::commit();
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
     */
    public function restore(string $id)
    {
        try {
            DB::beginTransaction();

            $this->model->restore($id);

            DB::commit();
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
     */
    public function delete(string $id)
    {
        try {
            DB::beginTransaction();

            $this->model->forceDelete($id);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
        }
    }

    /**
     * Generate random hash key.
     *
     * @param  string $key
     * @return string
     */
    public function hash(string $key): string
    {
        return bcrypt($key);
    }

    /**
     * Upload the given file.
     *
     * @param  \Illuminate\Http\UploadedFile $file
     * @return string|null
     */
    public function upload(UploadedFile $file): ?string
    {
        $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
        return $file->storeAs('photos', $fileName, 'public');
    }
}
