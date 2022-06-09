<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubjectTeacherJoinTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subject_teacher_join', function (Blueprint $table) {
            $table->unsignedBigInteger('subject');
            $table->unsignedBigInteger('teacher');

            $table->foreign('subject')->references('id')->on('subject')->onDelete('cascade');
            $table->foreign('teacher')->references('id')->on('teacher')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subject_teacher_join');
    }
}
