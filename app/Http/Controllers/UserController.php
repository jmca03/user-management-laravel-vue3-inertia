<?php

namespace App\Http\Controllers;

use App\Enums\UserPrefix;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UploadProfilePictureRequest;
use App\Http\Requests\UserRequest;
use App\Interfaces\UserServiceInterface;
use App\Models\User;
use App\Services\UserService;
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
    /**
     * Create an instance of the controller class.
     *
     * @param \App\Interfaces\UserServiceInterface $userService
     */
    public function __construct(protected UserServiceInterface $userService)
    {

    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        return Inertia::render('Users/Users', [
            'users'        => $this->userService->list($request),
            'alertMessage' => session('alertMessage')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Users/CreateUser', [
            'userPrefixes' => $this->userService->userPrefixes(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $user = $this->userService->store($request->validated());

        return redirect()->route('users.index')->with('alertMessage', 'User has been created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Inertia::render('Users/ViewUser', [
            'user'         => $this->userService->find($id),
            'userPrefixes' => $this->userService->userPrefixes(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return Inertia::render('Users/EditUser', [
            'user'         => $this->userService->find($id),
            'userPrefixes' => $this->userService->userPrefixes(),
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
    public function update(UserRequest $request, string $id): RedirectResponse
    {
        $user = $this->userService->update($id, $request->validated());
        return redirect()->route('users.edit', ['user' => $id]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->userService->destroy($id);
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
        return Inertia::render('Users/TrashedUsers', [
            'users'        => $this->userService->listTrashed($request),
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
        $this->userService->restore($id);
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
        $this->userService->delete($id);
        return redirect()->route('users.index')->with('alertMessage', 'User has been permanently deleted.');
    }
}
