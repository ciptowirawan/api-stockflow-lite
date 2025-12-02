<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $users   = User::paginate($perPage);

        if ($users->count() > 0) {
            return UserResource::collection($users);
        }

        return response()->json(['message' => 'Data tidak tersedia'], 200);
    }

    public function store(UserRequest $request)
    {
       $user = User::create($request->validated());

        return response()->json([
            'message' => 'User Created Successfully',
            'data'    => new UserResource($user)
        ], 201);
    }

    public function show(User $user)
    {
        return new UserResource($user);
    }

    public function update(UserRequest $request, User $user)
    {
        $updateData = [
            'name'   => $request->name,
            'email'  => $request->email,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return response()->json([
            'message' => 'User Updated Successfully',
            'data'    => new UserResource($user)
        ], 200);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'message' => 'User Deleted Successfully',
        ], 200);
    }
}
