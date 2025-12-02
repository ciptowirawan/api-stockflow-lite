<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Api\AuthController as ApiAuthController;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class AuthController extends ApiAuthController
{
    public function login(LoginRequest $request)
    {
        $response = parent::login($request);

        if ($response instanceof \Illuminate\Http\JsonResponse && $response->getStatusCode() !== 200) {
            $data = $response->getData(true);
            Alert::error('Login Failed', $data['message'] ?? 'Email atau password salah.');
            return redirect()->back()->withInput();
        }

        Alert::success('Login Successful', 'Welcome back!');
        return redirect('/dashboard');
    }

    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $response = parent::logout($request);

        Alert::success('Logout Successful', 'You have been logged out.');
        return redirect('/');
    }
}
