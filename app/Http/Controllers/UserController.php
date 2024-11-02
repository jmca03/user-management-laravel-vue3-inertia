<?php

namespace App\Http\Controllers;

use App\Enums\UserPrefix;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UploadProfilePictureRequest;
use App\Models\User;
use App\Traits\Pagination;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UserController extends Controller
{
    use Pagination;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $model = User::query()->rowNumber();

        return Inertia::render('Users/Users', [
            'users'        => $this->paginate($model, $request),
            'alertMessage' => session('alertMessage')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Users/CreateUser', [
            'userPrefixes' => array_map(function ($prefix) {
                return [
                    'label' => $prefix,
                    'value' => $prefix,
                ];
            }, array_column(UserPrefix::cases(), 'value'))
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        User::create($request->validated());

        return redirect()->route('users.index')->with('alertMessage', 'User has been created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::query()->findOrFail($id);

        return Inertia::render('Users/ViewUser', [
            'user'         => $user,
            'userPrefixes' => array_map(function ($prefix) {
                return [
                    'label' => $prefix,
                    'value' => $prefix,
                ];
            }, array_column(UserPrefix::cases(), 'value'))
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::query()->findOrFail($id);

        return Inertia::render('Users/EditUser', [
            'user'         => $user,
            'userPrefixes' => array_map(function ($prefix) {
                return [
                    'label' => $prefix,
                    'value' => $prefix,
                ];
            }, array_column(UserPrefix::cases(), 'value'))
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateUserRequest $request
     * @param string                               $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateUserRequest $request, string $id): RedirectResponse
    {
        $user = User::query()->findOrFail($id);
        $user->update($request->validated());

        return redirect()->route('users.edit', ['user' => $id]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if ((int) $id === auth()->id()) {
            throw new BadRequestException('You cannot delete yourself.');
        }

        $model = User::destroy($id);

        return redirect()->route('users.index')->with('alertMessage', 'User has been deleted.');
    }

    /**
     * Display a listing of the trashed resources.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Inertia\Response
     */
    public function trashed(Request $request): Response
    {
        $model = User::onlyTrashed()->rowNumber();

        return Inertia::render('Users/TrashedUsers', [
            'users'        => $this->paginate($model, $request),
            'alertMessage' => session('alertMessage')
        ]);
    }

    /**
     * Restore the specified resource
     *
     * @param string $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore(string $id): RedirectResponse
    {
        $model = User::withTrashed()->findOrFail($id);
        $model->restore();

        return redirect()->route('users.index')->with('alertMessage', 'User has been restored.');
    }

    /**
     * Permanently delete the specified resource
     *
     * @param string $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(string $id): RedirectResponse
    {
        $model = User::withTrashed()->findOrFail($id);
        $model->forceDelete();

        return redirect()->route('users.index')->with('alertMessage', 'User has been permanently deleted.');
    }

    /**
     * Upload a profile picture?
     *
     * @param \App\Http\Requests\UploadProfilePictureRequest $request
     * @param string                                         $id
     *
     * @return RedirectResponse
     */
    public function upload(UploadProfilePictureRequest $request, string $id): RedirectResponse
    {
        $user = User::query()->findOrFail($id);

        $user->update($request->validated());

        return redirect()->route('users.edit', ['user' => $user]);
    }
}
