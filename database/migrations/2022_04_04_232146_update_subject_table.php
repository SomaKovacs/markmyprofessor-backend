<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSubjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("subject", function(Blueprint $table) {
            $table->renameColumn("subject", "subject_name");
            $table->string("subject_type");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("subject", function(Blueprint $table) {
            $table->renameColumn("subject_name", "subject");
            $table->dropColumn("subject_type");
        });
    }
}
