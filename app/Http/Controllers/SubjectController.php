<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SubjectController extends Controller
{

    /**
     * Tárgy lekérése
     *
     * @param $id
     * @return Response
     */
    public function getSubject($id): Response
    {
        if ($id === '-1') {
            return response([
                "success" => true,
                "data" => [
                    "id" => 1,
                    "created_at" => "1649206508",
                    "updated_at" => "1649506753",
                    "subject_name" => "Programozás I",
                    "subject_type" => "Kollokvium"
                ]
            ]);
        }

        if (! Subject::where("id", $id)->exists())
            return response([
                "success" => false,
                "message" => "Subject doesn't exists."
            ]);

        $subject = Subject::findOrFail($id);

        return response([
            "success" => true,
            "data" => $subject
        ]);
    }

    /**
     * Több (akár az összes) tárgy lekérése
     *
     * @return Response
     */
    public function getAllSubjects(): Response
    {
        $subjects = Subject::all();

        return response([
            "success" => true,
            "data" => $subjects
        ]);
    }

    /**
     * Tárgy létrehozása
     *
     * @param Request $request
     * @return Response
     */
    public function createSubject(Request $request): Response
    {
        $fields = $this->validateSubject($request);

        $subject = Subject::create([
            "subject_name" => $fields["subject_name"],
            "subject_type" => $fields["subject_type"],
        ]);

        return response([
            "success" => true,
            "data" => $subject
        ], 201);
    }

    /**
     * Meglévő tárgy módosítása
     *
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function updateSubject(Request $request, $id): Response
    {
        if (! Subject::where("id", $id)->exists())
            return response([
                "success" => false,
                "message" => "Subject doesn't exists."
            ]);

        $subject = Subject::findOrFail($id);

        $this->validateSubject($request);

        $subject->subject_name = $request->get('subject_name');
        $subject->subject_type = $request->get('subject_type');

        $subject->save();

        return response([
            "success" => true,
            "data" => $subject
        ]);
    }

    /**
     * Meglévő tárgy törlése
     *
     * @param $id
     * @return Response
     */
    public function deleteSubject($id): Response
    {
        if (! Subject::where("id", $id)->exists())
            return response([
                "success" => false,
                "message" => "Subject doesn't exists."
            ]);
            
        $rating = Subject::findOrFail($id);
        $rating->delete();

        return response([
            "success" => true,
            "data" => Subject::all()
        ]);
    }

    /**
     * Tárgy autentikálása
     *
     * @param Request $request
     * @return array
     */
    private function validateSubject(Request $request): array
    {
        return $request->validate([
            "subject_name" => "required|string",
            "subject_type" => "required|string",
        ]);
    }
}
