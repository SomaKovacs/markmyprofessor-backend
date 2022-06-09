<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstitutionTeacherJoinTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('institution_teacher_join', function (Blueprint $table) {
            $table->unsignedBigInteger('institution');
            $table->unsignedBigInteger('teacher');

            $table->foreign('institution')->references('id')->on('institution')->onDelete('cascade');
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
        Schema::dropIfExists('institution_teacher_join');
    }
}
