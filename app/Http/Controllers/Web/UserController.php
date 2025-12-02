<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Controllers\Api\UserController as ApiUserController;
use App\Models\User;
use App\Http\Requests\UserRequest;

class UserController extends ApiUserController
{
    public function index(Request $request)
    {
        $response = parent::index($request);

        if ($response instanceof \Illuminate\Http\JsonResponse && $response->getStatusCode() >= 400) {
            $data = $response->getData(true);
            Alert::error('Error', $data['message'] ?? 'Something went wrong');
            return redirect()->back();
        }

        $data = $response->getData(true);
        $users = collect($data['data'] ?? [])->map(function ($item) {
            return (object) $item;
        });

        return view('masters.users.index', [
            'users' => $users
        ]);
    }

    public function store(UserRequest $request)
    {
        $response = parent::store($request);

        if ($response instanceof \Illuminate\Http\JsonResponse && $response->getStatusCode() >= 400) {
            $data = $response->getData(true);
            Alert::error('Error', $data['message'] ?? 'Validation failed');
            return redirect()->back()->withInput();
        }

        Alert::success('Success', 'User created successfully');
        return redirect()->route('users.index');
    }

    public function show(User $user)
    {
        $response = parent::show($user);

        if ($response instanceof \Illuminate\Http\JsonResponse && $response->getStatusCode() >= 400) {
            $data = $response->getData(true);
            Alert::error('Error', $data['message'] ?? 'Resource not found');
            return redirect()->back();
        }

        $data = $response->getData(true);
        $userObject = (object) ($data['data'] ?? []);

        return view('masters.users.show', [
            'user' => $userObject
        ]);
    }

    public function edit(User $user)
    {
        $response = parent::show($user);

        if ($response instanceof \Illuminate\Http\JsonResponse && $response->getStatusCode() >= 400) {
            $data = $response->getData(true);
            Alert::error('Error', $data['message'] ?? 'Resource not found');
            return redirect()->back();
        }

        $data = $response->getData(true);
        $userObject = (object) ($data['data'] ?? []);

        return view('masters.users.edit', [
            'user' => $userObject
        ]);
    }

    public function update(UserRequest $request, User $user)
    {
        $response = parent::update($request, $user);

        if ($response instanceof \Illuminate\Http\JsonResponse && $response->getStatusCode() >= 400) {
            $data = $response->getData(true);
            Alert::error('Error', $data['message'] ?? 'Update failed');
            return redirect()->back()->withInput();
        }

        Alert::success('Success', 'User updated successfully');
        return redirect()->route('users.index');
    }

    public function destroy(User $user)
    {
        $response = parent::destroy($user);

        if ($response instanceof \Illuminate\Http\JsonResponse && $response->getStatusCode() >= 400) {
            $data = $response->getData(true);
            Alert::error('Error', $data['message'] ?? 'Delete failed');
            return redirect()->back();
        }

        Alert::success('Success', 'User deleted successfully');
        return redirect()->route('users.index');
    }
}
