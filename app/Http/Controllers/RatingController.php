<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RatingController extends Controller
{
    /**
     * Értékelés lekérése
     *
     * @param $id
     * @return Response
     */
    public function getRating($id): Response
    {
        if ($id === '-1') {
            return response([
                "success" => true,
                "data" => [
                    "id" => 4,
                    "created_at" => "1649334039",
                    "updated_at" => "1649334039",
                    "rating_message" => "Jófej tanár.",
                    "presentation" => 5,
                    "interactive_tool_usage" => 4,
                    "helpfulness" => 4,
                    "preparation_level" => 4,
                    "subject_utility" => 3,
                    "requirement_difficulty" => 1,
                    "subject" => 1,
                    "teacher" => 1,
                    "author" => 1
                ]
            ]);
        }

        if (! Rating::where("id", $id)->exists())
            return response([
                "success" => false,
                "message" => "Rating doesn't exists."
            ]);

        $rating = Rating::findOrFail($id);

        return response([
            "success" => true,
            "data" => $rating
        ]);
    }

    /**
     * Több értékelés lekérése
     * (akár az összeset is)
     * @return Response
     */
    public function getAllRatings(): Response
    {
        $ratings = Rating::all();

        $avg = $this->calculateRatingsAvg($ratings);

        return response([
            "success" => true,
            "data" => $ratings,
            "avg" => $avg
        ]);
    }

    /**
     * Új értékelés létrehozása
     * @param Request $request
     * @return Response
     */
    public function createRating(Request $request): Response
    {
        $fields = $this->validateRating($request);

        if (! Subject::where("id", $fields["subject"])->exists())
            return response([
                "success" => false,
                "message" => "Subject doesn't exists."
            ]);

        if (! Teacher::where("id", $fields["teacher"])->exists())
            return response([
                "success" => false,
                "message" => "Teacher doesn't exists."
            ]);

        if (! User::where("id", $fields["author"])->exists())
            return response([
                "success" => false,
                "message" => "Author doesn't exists."
            ]);

        $rating = Rating::create([
            "rating_message" => $fields["rating_message"],
            "presentation" => $fields["presentation"],
            "interactive_tool_usage" => $fields["interactive_tool_usage"],
            "helpfulness" => $fields["helpfulness"],
            "preparation_level" => $fields["preparation_level"],
            "subject_utility" => $fields["subject_utility"],
            "requirement_difficulty" => $fields["requirement_difficulty"],
            "subject" => $fields["subject"],
            "teacher" => $fields["teacher"],
            "author" => $fields["author"],
        ]);

        return response([
            "success" => true,
            "data" => $rating
        ], 201);
    }

    /**
     * Meglévő értékelés módosítása
     *
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function updateRating(Request $request, $id): Response
    {
        $rating = Rating::findOrFail($id);

        $this->validateRating($request);

        if (! Subject::where("id", $request->get('subject'))->exists())
            return response([
                "success" => false,
                "message" => "Subject doesn't exists."
            ]);

        if (! User::where("id", $request->get('author'))->exists())
            return response([
                "success" => false,
                "message" => "Author doesn't exists."
            ]);

        $rating->rating_message = $request->get('rating_message');
        $rating->presentation = $request->get('presentation');
        $rating->interactive_tool_usage = $request->get('interactive_tool_usage');
        $rating->helpfulness = $request->get('helpfulness');
        $rating->preparation_level = $request->get('preparation_level');
        $rating->subject_utility = $request->get('subject_utility');
        $rating->requirement_difficulty = $request->get('requirement_difficulty');
        $rating->subject = $request->get('subject');
        $rating->author = $request->get('author');

        $rating->save();

        return response([
            "success" => true,
            "data" => $rating
        ]);
    }

    /**
     * Meglévő értékelés törlése
     *
     * @param $id
     * @return Response
     */
    public function deleteRating($id): Response
    {
        if (! Rating::where("id", $id)->exists())
            return response([
                "success" => false,
                "message" => "Rating doesn't exists."
            ]);

        $rating = Rating::findOrFail($id);
        $rating->delete();

        return response([
            "success" => true,
            "data" => Rating::all()
        ]);
    }

    /**
     * Értékelés autentikálása
     *
     * @param Request $request
     * @return array
     */
    private function validateRating(Request $request): array
    {
        return $request->validate([
            "rating_message" => "string",
            "presentation" => "required|integer",
            "interactive_tool_usage" => "required|integer",
            "helpfulness" => "required|integer",
            "preparation_level" => "required|integer",
            "subject_utility" => "required|integer",
            "requirement_difficulty" => "required|integer",
            "subject" => "required|integer",
            "teacher" => "required|integer",
            "author" => "required|integer"
        ]);
    }

    public static function calculateRatingsAvg($ratings)
    {
        $fields = [ "presentation", "interactive_tool_usage", "helpfulness", "preparation_level", "subject_utility", "requirement_difficulty" ];
        $sum    = [];

        foreach ($fields as $field) {
            $sum[$field] = 0;
        }

        foreach ($ratings as $rating) {
            foreach ($fields as $field) {
                $sum[$field] += $rating[$field];
            }
        }

        $avg   = [];
        $total = count($ratings);

        if ($total === 0) {
            return 0;
        }

        foreach ($fields as $field)
            $avg[$field] = $sum[$field] / $total;

        return $avg;
    }
}
