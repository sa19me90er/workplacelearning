<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditFeedbackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('feedback', function (Blueprint $table) {
            $table->string('notfinished', 100)->nullable()->default(null)->change();
            $table->integer('progress_satisfied')->default(0)->change();
            $table->integer('support_requested')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('feedback', function (Blueprint $table) {
            $table->string('notfinished', 100)->change();
            $table->integer('progress_satisfied')->change();
            $table->integer('support_requested')->change();
        });
    }
}
