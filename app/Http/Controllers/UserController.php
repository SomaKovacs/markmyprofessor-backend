<?php


namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{

    /**
     * Felhasználó adatainak lekérése
     *
     * @param $id
     * @return Response
     */
    public function getUser($id): Response
    {
        if (! User::where("id", $id)->exists())
            return response([
                "success" => false,
                "message" => "User doesn't exists."
            ]);

        $user = User::findOrFail($id);

        return response([
            "success" => true,
            "data" => $user
        ]);
    }

    /**
     * Több (akár az összes) felhasználó adatainak lekérése
     *
     * @return Response
     */
    public function getAllUsers(): Response
    {
        $users = User::all();

        return response([
            "success" => true,
            "data" => $users,
        ]);
    }

    /**
     * Felhasználó létrehozása
     *
     * @param Request $request
     * @return Response
     */
    public function createUser(Request $request): Response
    {
        $fields = $this->validateUser($request);

        $user = User::create([
            "first_name" => $fields["first_name"],
            "last_name" => $fields["last_name"],
            "email" => $fields["email"],
            "password" => $fields["password"],
            "is_admin" => $fields["is_admin"]
        ]);

        return response([
            "success" => true,
            "data" => $user,
        ]);
    }

    /**
     * Meglévő felhasználó adatainak módosítása
     *
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function updateUser(Request $request, $id): Response
    {
        if (! User::where("id", $id)->exists())
            return response([
                "success" => false,
                "message" => "User doesn't exists."
            ]);

        $user = User::findOrFail($id);

        $this->validateUser($request);

        $user->first_name = $request->get('first_name');
        $user->last_name = $request->get('last_name');
        $user->email = $request->get('email');
        $user->password = $request->get('password');

        $user->save();

        return response([
            "success" => true,
            "data" => $user
        ]);
    }

    /**
     * Meglévő felhasználó törlése
     *
     * @param $id
     * @return Response
     */
    public function deleteUser($id): Response
    {
        if (! User::where("id", $id)->exists())
            return response([
                "success" => false,
                "message" => "User doesn't exists."
            ]);

        $user = User::findOrFail($id);
        $user->delete();

        return response([
            "success" => true,
            "data" => User::all()
        ]);
    }

    /**
     * Felhasználó autentikálása
     *
     * @param Request $request
     * @return array
     */
    private function validateUser(Request $request): array
    {
        return $request->validate([
            "first_name" => "required|string",
            "last_name" => "required|string",
            "email" => "required|string|unique:users,email",
            "password" => "required|string"
        ]);
    }

}
