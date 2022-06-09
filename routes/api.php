<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NewPasswordController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::group(["middleware" => ["cors"]], function() {
    Route::get("/ping", function() {
        return "Pong!";
    });

    Route::post("/register", [AuthController::class, "register"]);
    Route::post("/login", [AuthController::class, "login"]);

    Route::get("/ratings", [RatingController::class, "getAllRatings"]);
    Route::get("/rating-get/{id}", [RatingController::class, "getRating"]);

    Route::get("/institutions", [InstitutionController::class, "getAllInstitutions"]);
    Route::get("/institution-get/{id}", [InstitutionController::class, "getInstitution"]);

    Route::get("/subjects", [SubjectController::class, "getAllSubjects"]);
    Route::get("/subject-get/{id}", [SubjectController::class, "getSubject"]);

    Route::get("/teachers", [TeacherController::class, "getAllTeachers"]);
    Route::get("/teacher-get/{id}", [TeacherController::class, "getTeacher"]);

    Route::get("/users", [UserController::class, "getAllUsers"]);
    Route::get("/user-get/{id}", [UserController::class, "getUser"]);

    Route::get("/institutions-for/{id}", [TeacherController::class, "listInstitutionsOfTeacher"]);
    Route::get("/subjects-for/{id}", [TeacherController::class, "listSubjectsOfTeacher"]);
    Route::get("/ratings-for/{id}", [TeacherController::class, "listRatingsOfTeacher"]);
    Route::get("/connect-institution/{teacher_id}/to/{institution_id}", [TeacherController::class, "connectInstitutionToTeacher"]);
    Route::get("/connect-subject/{teacher_id}/to/{subject_id}", [TeacherController::class, "connectSubjectToTeacher"]);

    Route::post('forgot-password', [NewPasswordController::class, 'forgotPassword']);
    Route::post('reset-password', [NewPasswordController::class, 'resetPassword']);
});

// Protected routes
Route::group(["middleware" => ["auth:sanctum", "cors"]], function() {
    Route::post('email/verification-notification', [EmailVerificationController::class, 'sendVerificationEmail']);
    Route::get('verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify');

    Route::post("/rating-create", [RatingController::class, "createRating"]);
    Route::post("/rating-update/{id}", [RatingController::class, "updateRating"]);
    Route::post("/rating-delete/{id}", [RatingController::class, "deleteRating"]);

    Route::post("/institution-create", [InstitutionController::class, "createInstitution"]);
    Route::post("/institution-update/{id}", [InstitutionController::class, "updateInstitution"]);
    Route::post("/institution-delete/{id}", [InstitutionController::class, "deleteInstitution"]);

    Route::post("/subject-create", [SubjectController::class, "createSubject"]);
    Route::post("/subject-update/{id}", [SubjectController::class, "updateSubject"]);
    Route::post("/subject-delete/{id}", [SubjectController::class, "deleteSubject"]);

    Route::post("/teacher-create", [TeacherController::class, "createTeacher"]);
    Route::post("/teacher-update/{id}", [TeacherController::class, "updateTeacher"]);
    Route::post("/teacher-delete/{id}", [TeacherController::class, "deleteTeacher"]);

    Route::post("/user-create", [UserController::class, "createUser"]);
    Route::post("/user-update/{id}", [UserController::class, "updateUser"]);
    Route::post("/user-delete/{id}", [UserController::class, "deleteUser"]);

    Route::post("/logout", [AuthController::class, "logout"]);

    Route::get("/test_token", function() {
        return response([
            "success" => true
        ]);
    });
});
