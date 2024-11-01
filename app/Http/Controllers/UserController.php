<?php

namespace App\Http\Controllers;

use App\Enums\UserPrefix;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Traits\Pagination;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

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
            'users' => $this->paginate($model, $request),
            'alertMessage' => session('alertMessage')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::query()->findOrFail($id);

        return Inertia::render('Users/ViewUser', [
            'user' => $user,
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
            'user' => $user,
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
        $model = User::destroy($id);

        return redirect()->route('users.index')->with('alertMessage', 'User has been deleted.');
    }
}
