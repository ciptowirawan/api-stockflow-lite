<?php

namespace App\Http\Controllers\Api;

use DB;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Http\Resources\LoginResource;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $response = Http::asForm()->timeout(60)->post(env("OAUTH_TOKEN_URL"), [
            'grant_type' => 'password',
            'client_id' => env('PASSPORT_PASSWORD_CLIENT_ID'),
            'client_secret' => env('PASSPORT_PASSWORD_CLIENT_SECRET'),
            'username' => $request->email,
            'password' => $request->password,
            'scope' => '',
        ]);

        if ($response->failed()) {
            \Log::error('OAuth Token Request Failed: ' . $response->status());
            \Log::debug('OAuth Response Body: ' . $response->body());

            return response()->json([
                'message' => 'Email atau password salah.',
                'error'   => $response->json(),
            ], 401);
        }

        $tokenData = $response->json();

        $user = User::where('email', $request->email)->first();

        $data = (object) [
            'username' => $user->name,
            'access_token'  => $tokenData['access_token'],
            'expires_in'    => $tokenData['expires_in'],
            'token_type'    => $tokenData['token_type'],
            'user'          => $user,
        ];

        return new LoginResource($data);
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        if (!$user && session()->has('access_token')) {
            $token = session('access_token');
            
            $oauthAccessToken = DB::table('oauth_access_tokens')->where('id', $token)->first();
            
            if ($oauthAccessToken) {
                $user = User::find($oauthAccessToken->user_id);
            }
        }

        if (!$user) {
            return response()->json([
                'message' => 'User not authenticated or token already revoked.',
                'data'    => []
            ], 401);
        }
 
        $accessToken = $user->token();
        $accessToken->revoke();

        DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update(['revoked' => true]);

        session()->forget(['username', 'access_token', 'token_type', 'expires_in']);
    
        return response()->json([
            'message' => 'Logout berhasil. Token telah dicabut.',
            'data'    => []
        ], 200);
    }
}
