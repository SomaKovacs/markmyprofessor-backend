<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTeacherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teacher', function (Blueprint $table){
            $table->dropColumn(['first_name', 'last_name']);
            $table->string('name');
            $table->string('email');
            $table->string('website');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teacher', function (Blueprint $table){
            $table->dropColumn(['name', 'email', 'website']);
            $table->string('first_name');
            $table->string('last_name');

        });
    }
}
