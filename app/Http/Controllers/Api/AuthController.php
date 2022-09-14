<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'bail|required|string',
                'password' => 'bail|required',
            ]);

            if (!$token = auth('api')->attempt($request->only(['email', 'password']))) {
                return response()->json([
                    'message' => 'Unauthorized'
                ], 401);
            }

            return $this->respondWithToken($token);

        } catch (ValidationException $th) {
            return response()->json([
                'message' => $th->validator->errors()->first()
            ], 422);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function logout()
    {
        auth('api')->logout();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function me(Request $request)
    {
        return response()->json([
            'user' => auth('api')->user()
        ]);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
