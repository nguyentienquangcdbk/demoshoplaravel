<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    //
    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        // Check email
        $checkLogin = Auth::attempt([
            'email' => $fields['email'],
            'password' => $fields['password']
        ]);

        $user = User::where('email', $fields['email'])->first();

        // Check password
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Bad creds'
            ], 401);
        }
        // if ($checkLogin) {
        //     $user = Auth::user();

        $token = $user->createToken('myapptoken')->plainTextToken;

        //     $response = [
        //         'status' => 201,
        //         'user' => $user,
        //         'token' => $token
        //     ];
        // } else {
        //     $response = [
        //         'status' => 401,
        //         'title' => 'Unauthorized'
        //     ];
        // }
        // return $response;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string'
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password'])
        ]);
        // dd($user);

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }
    public function logout(Request $request)
    {
        // return 'ok';
        // $access_token = $request->header('Authorization');
        // $auth_header = explode(' ', $access_token);
        // $token = $auth_header[1];
        // $hastoken = PersonalAccessToken::findToken($token)->delete();
        $request->user()->tokens()->delete();
        // dd($request->header('Authorization'));
        // auth()->user()->tokens()->delete();
        // $request->user()->currentAccessToken()->delete();
        return [
            'message' => 'Logged out',
            // 'xoa' => $hastoken
        ];
    }
    public function getUser(Request $request)
    {
        $access_token = $request->header('Authorization');
        $auth_header = explode(' ', $access_token);
        $token = $auth_header[1];

        // $token_parts = explode('.', $token);

        // $token_header = $token_parts[0];
        $hastoken = PersonalAccessToken::findToken($token);
        $userId = $hastoken->tokenable_id;
        $user = User::find($userId);

        return $user;
    }
}
