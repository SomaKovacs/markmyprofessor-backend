<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rating', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->text('rating_message'); // 65,535 characters
            $table->integer('presentation');
            $table->integer('interactive_tool_usage');
            $table->integer('helpfulness');
            $table->integer('preparedness');
            $table->integer('subject_utility');
            $table->integer('requirement_difficulty');

            $table->unsignedBigInteger('subject');
            $table->unsignedBigInteger('author');

            $table->foreign('subject')->references('id')->on('subject')->onDelete('cascade');
            $table->foreign('author')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rating');
    }
}
