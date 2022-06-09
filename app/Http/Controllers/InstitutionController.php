<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InstitutionController extends Controller
{

    /**
     * Intézmény lekérése
     *
     * @param $id
     * @return Response
     */
    public function getInstitution($id): Response
    {
        if ($id === '-1') {
            return response([
                "success" => true,
                "data" => [
                    "id" => 1,
                    "created_at" => "1649579457",
                    "updated_at" => "1649579457",
                    "university_name" => "Szegedi Tudományegyetem",
                    "acronym" => "SZTE"
                ]
            ]);
        }
        
        if (! Institution::where("id", $id)->exists())
            return response([
                "success" => false,
                "message" => "Institution doesn't exists."
            ]);

        $institution = Institution::findOrFail($id);

        return response([
            "success" => true,
            "data" => $institution
        ]);
    }

    /**
     * Több (akár az összes) intézmény lekérése
     *
     * @return Response
     */
    public function getAllInstitutions(): Response
    {
        $institution = Institution::all();

        return response([
            "success" => true,
            "data" => $institution
        ]);
    }

    /**
     * Intézmény létrehozása
     *
     * @param Request $request
     * @return Response
     */

    public function createInstitution(Request $request): Response
    {
        $fields = $this->validateInstitution($request);

        $institution = Institution::create([
            "university_name" => $fields["university_name"],
            "acronym" => $fields["acronym"],
        ]);

        return response([
            "success" => true,
            "data" => $institution
        ], 201);
    }

    /**
     * Meglévő intézmény módosítása
     *
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function updateInstitution(Request $request, $id): Response
    {
        if (! Institution::where("id", $id)->exists())
            return response([
                "success" => false,
                "message" => "Institution doesn't exists."
            ]);

        $institution = Institution::findOrFail($id);

        $this->validateInstitution($request);

        $institution->university_name = $request->get('university_name');
        $institution->acronym = $request->get('acronym');

        $institution->save();

        return response([
            "success" => true,
            "data" => $institution
        ]);
    }

    /**
     * Meglévő intézmény törlése
     *
     * @param $id
     * @return Response
     */
    public function deleteInstitution($id): Response
    {
        if (! Institution::where("id", $id)->exists())
            return response([
                "success" => false,
                "message" => "Institution doesn't exists."
            ]);

        $rating = Institution::findOrFail($id);
        $rating->delete();

        return response([
            "success" => true,
            "data" => Institution::all()
        ]);
    }

    /**
     * Intézmény autentikálása
     *
     * @param Request $request
     * @return array
     */
    private function validateInstitution(Request $request): array
    {
        return $request->validate([
            "university_name" => "required|string",
            "acronym" => "required|string|unique:institution,acronym",
        ]);
    }
}
