<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Regisztrál egy felhasználót az adatbázisba
     *
     * @param Request $request a bejövő adatok
     * @return mixed
     */
    public function register(Request $request) {
        $fields = $request->validate([
            "first_name" => "required|string",
            "last_name" => "required|string",
            "email" => "required|string|unique:users,email",
            "password" => "required|string",
            "is_admin" => "nullable|boolean"
        ]);

        $user = User::create([
            "first_name" => $fields["first_name"],
            "last_name" => $fields["last_name"],
            "email" => $fields["email"],
            "password" => bcrypt($fields["password"]),
            "is_admin" => $fields["is_admin"] ?? false
        ]);

        $token = $user->createToken($fields["email"])->plainTextToken;

        return response([
            "success" => true,
            "result" => [
                "user" => $user,
                "token" => $token
            ]
        ]);
    }

    /**
     * Bejelentkezik egy felhasználóba
     *
     * @param Request $request
     * @return mixed
     */
    public function login(Request $request) {
        $fields = $request->validate([
            "email" => "required|string",
            "password" => "required|string",
        ]);

        $user = User::where("email", $fields["email"])->first();

        if (!$user || !Hash::check($fields["password"], $user->password))
            return response([
                "message" => "Wrong credentials or user doesn't exists!"
            ]);

        $token = $user->createToken($fields["email"])->plainTextToken;

        return response([
            "success" => true,
            "result" => [
                "user" => $user,
                "token" => $token,
            ]
        ]);
    }


    /**
     * Kijelentkezés
     *
     * @param Request $request
     * @return mixed
     */
    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();

        return response([
            "success" => true
        ]);
    }
}
