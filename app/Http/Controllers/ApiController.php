<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ApiController extends Controller
{
    public function userRegister(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6|confirmed',
            ]);

            if ($validator->fails()) {
                return response()->json(["status" => 0, "message" => $validator->errors()]);
            }

            $user = User::create([
                'name' => $req->name,
                'email' => $req->email,
                'password' => Hash::make($req->password),
            ]);

            $token = auth('api')->login($user);
            return response()->json(["status" => 1, "message" => "User registered successfully", "token" => $token]);
        } catch (\Exception $e) {
            return response()->json(["status" => 0, "message" => $e->getMessage()]);
        }
    }

    public function userLogin(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(["status" => 0, "message" => $validator->errors()]);
            }

            $credentials = $req->only('email', 'password');
            $token = auth('api')->attempt($credentials);

            if (!$token) {
                return response()->json(["status" => 0, "message" => "Invalid credentials"], 401);
            }

            return response()->json(["status" => 1, "message" => "User logged in successfully", "token" => $token]);
        } catch (\Exception $e) {
            return response()->json(["status" => 0, "message" => $e->getMessage()]);
        }
    }

    public function profile()
    {
        try {
            $user = auth('api')->user();
            return response()->json(["status" => 1, "message" => "User profile", "user" => $user]);
        } catch (\Exception $e) {
            return response()->json(["status" => 0, "message" => $e->getMessage()]);
        }
    }
}
