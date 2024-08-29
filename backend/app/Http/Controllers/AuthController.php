<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * Register a new user and return a token.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|confirmed|min:8',
                'is_admin' => 'sometimes|boolean', 
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'is_admin' => $request->is_admin ?? false,
            ]);

            return response()->json(['token' => $user->createToken('Personal Access Token')->plainTextToken], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error registering user'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Login a user and return a token.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');
        
            if (!Auth::attempt($credentials)) {
                return response()->json(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
            }

            $token = $request->user()->createToken('Personal Access Token')->plainTextToken;
        
            return response()->json(['token' => $token]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error logging in'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Logout the user by deleting the current access token.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json(['message' => 'Successfully logged out']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error logging out'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
