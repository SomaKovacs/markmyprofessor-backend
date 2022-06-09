<?php


namespace App\Http\Controllers;


use App\Models\Institution;
use App\Models\Rating;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherInstitution;
use App\Models\TeacherSubject;
use App\Http\Controllers\RatingController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TeacherController extends Controller
{

    /**
     * Oktató adatainak lekérése
     *
     * @param $id
     * @return Response
     */
    public function getTeacher($id): Response
    {
        if ($id === '-1') {
            return response([
                "success" => true,
                "data" => [
                    "id" => 1,
                    "created_at" => "2022-04-06T00:54:37.000000Z",
                    "updated_at" => "2022-04-06T00:54:37.000000Z",
                    "name" => "Beszédes Árpád",
                    "email" => "beszedes@inf.u-szeged.hu",
                    "website" => "www.inf.u-szeged.hu/~beszedes",
                ]
            ]);
        }

        if (! Teacher::where("id", $id)->exists())
            return response([
                "success" => false,
                "message" => "Teacher doesn't exists."
            ]);

        $teacher = Teacher::findOrFail($id);

        return response([
            "success" => true,
            "data" => $teacher
        ]);
    }

    /**
     * Több oktató (akár az összes) adatainak lekérése
     *
     * @return Response
     */
    public function getAllTeachers(): Response
    {
        $teachers = Teacher::all();

        return response([
            "success" => true,
            "data" => $teachers
            ]);
    }

    /**
     * Új oktató hozzáadása
     *
     * @param Request $request
     * @return Response
     */
    public function createTeacher(Request $request):Response
    {
        $fields = $this->validateTeacher($request);

        $teacher = new Teacher();
        $teacher->name = $fields["name"];
        $teacher->email = $fields["email"];
        $teacher->website = $fields["website"];

        if ($teacher->save()) {
            return response([
                "success" => true,
                "data" => $teacher
            ], 201);
        } else {
            return response(["success" => false], 406);
        }
    }

    /**
     * Meglévő oktató adatainak módosítása
     *
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function updateTeacher(Request $request, $id): Response
    {
        if (! Teacher::where("id", $id)->exists())
            return response([
                "success" => false,
                "message" => "Teacher doesn't exists."
            ]);

        $teacher = Teacher::findOrFail($id);

        $this->validateTeacher($request);

        $teacher->name = $request->get('name');
        $teacher->email = $request->get('email');
        $teacher->website = $request->get('website');
        $teacher->save();

        return response([
            "success" => true,
            "data" => $teacher
        ]);
    }

    /**
     * Oktató törlése
     *
     * @param $id
     * @return Response
     */
    public function deleteTeacher($id): Response
    {
        if (! Teacher::where("id", $id)->exists())
            return response([
                "success" => false,
                "message" => "Teacher doesn't exists."
            ]);

        $teacher = Teacher::findOrFail($id);
        $teacher->delete();

        return response([
            "success" => true,
            "data" => Teacher::all()
        ]);
    }

    /**
     * Oktató intézményének listázása
     *
     * @param $id
     * @return Response
     */
    public function listInstitutionsOfTeacher($id): Response
    {
        if ($id === '-1') {
            return response([
                "success" => true,
                "data" => [
                    [
                        "id" => 1,
                        "created_at" => "1649610831",
                        "updated_at" => "1649610831",
                        "university_name" => "Szegedi Tudományegyetem",
                        "acronym" => "SZTE"
                    ],
                    [
                        "id" => 2,
                        "created_at" => "1649610892",
                        "updated_at" => "1649610892",
                        "university_name" => "Budapesti Műszaki és Gazdaságtudományi Egyetem",
                        "acronym" => "BME"
                    ]
                ]
            ]);
        }

        if (! Teacher::where("id", $id)->exists())
            return response([
                "success" => false,
                "message" => "Teacher doesn't exists."
            ]);

        $teacher_inst = TeacherInstitution::where('teacher', $id)->get();

        $institutions = [];
        foreach ($teacher_inst as $item) {
            $institutions[] = Institution::findOrFail($item->institution);
        }

        return response([
            "success" => true,
            "data" => $institutions
        ]);
    }

    /**
     * Oktató tárgyainak listázása
     *
     * @param $id
     * @return Response
     */
    public function listSubjectsOfTeacher($id): Response
    {
        if ($id === '-1') {
            return response([
                "success" => true,
                "data" => [
                    [
                        "id" => 2,
                        "created_at" => "1649617838",
                        "updated_at" => "1649617838",
                        "subject_name" => "Agilis szoftverfejlesztés",
                        "subject_type" => "Kollokvium"
                    ],
                    [
                        "id" => 3,
                        "created_at" => "1649617843",
                        "updated_at" => "1649617843",
                        "subject_name" => "Agilis szoftverfejlesztés",
                        "subject_type" => "Gyakorlat"
                    ],
                    [
                        "id" => 1,
                        "created_at" => "1649617818",
                        "updated_at" => "1649617818",
                        "subject_name" => "Rendszerfejlesztés II",
                        "subject_type" => "Kollokvium"
                    ]
                ]
            ]);
        }

        if (! Teacher::where("id", $id)->exists())
            return response([
                "success" => false,
                "message" => "Teacher doesn't exists."
            ]);

        $teacher_subj = TeacherSubject::where('teacher', $id)->get();

        $subjects = [];
        foreach ($teacher_subj as $item){
            $subjects[] = Subject::findOrFail($item->subject);
        }

        return response([
            "success" => true,
            "data" => $subjects
        ]);
    }

    public function listRatingsOfTeacher($id): Response
    {
        if (! Teacher::where("id", $id)->exists())
            return response([
                "success" => false,
                "message" => "Teacher doesn't exists."
            ]);

        $ratings = Rating::where('teacher', $id)->get();

        $avg = RatingController::calculateRatingsAvg($ratings);

        return response([
            "success" => true,
            "data" => $ratings,
            "avg" => $avg
        ]);
    }

    public function connectInstitutionToTeacher($teacher_id, $institution_id): Response
    {
        if (! Teacher::where("id", $teacher_id)->exists())
            return response([
                "success" => false,
                "message" => "Teacher doesn't exists."
            ]);

        if (! Institution::where("id", $institution_id)->exists())
            return response([
                "success" => false,
                "message" => "Institution doesn't exists."
            ]);

        $teacherInst = new TeacherInstitution();
        $teacherInst->institution = $institution_id;
        $teacherInst->teacher = $teacher_id;

        if (!$teacherInst->save()) {
            return response([
                "success" => false,
            ]);
        }

        return response([
            "success" => true,
        ]);
    }

    public function connectSubjectToTeacher($teacher_id, $subject_id): Response
    {
        if (! Teacher::where("id", $teacher_id)->exists())
            return response([
                "success" => false,
                "message" => "Teacher doesn't exists."
            ]);

        if (! Subject::where("id", $subject_id)->exists())
            return response([
                "success" => false,
                "message" => "Subject doesn't exists."
            ]);

        $teacherSubject = new TeacherSubject();
        $teacherSubject->subject = $subject_id;
        $teacherSubject->teacher = $teacher_id;

        if (!$teacherSubject->save()) {
            return response([
                "success" => false,
            ]);
        }

        return response([
            "success" => true,
        ]);
    }

    /**
     * Oktató validálása
     *
     * @param Request $request
     * @return array
     */
    private function validateTeacher(Request $request): array
    {
        return $request->validate([
            "name" => "required|string",
            "email" => "required|string",
            "website" => "string",
        ]);
    }
}
