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
        if (!auth()->attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json([
                'message' => 'Email atau password salah.',
            ], 401);
        }

        $user = auth()->user();

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->accessToken;

        $expiresAt = $tokenResult->token->expires_at;
        $expiresIn = $expiresAt ? now()->diffInSeconds($expiresAt, false) : null;

        $data = (object) [
            'username' => $user->name,
            'access_token'  => $token,
            'expires_in'    => $expiresIn,
            'token_type'    => 'Bearer',
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
