<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentActivityRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_activity_records', function (Blueprint $table) {
            /*
             * 1. Joiend
             * 2. Left
             * 3. Toggle Mic
             * 4. Toggle Screen
             * 5. Toggle Camera
             */
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('meeting_id');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('meeting_id')->references('id')->on('meetings')->onDelete('cascade');
            $table->string('activity_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_activity_records');
    }
}
