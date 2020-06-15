<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTabel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('nis')->unique();
            $table->string('nama');
            $table->string('kelas');
            $table->timestamps();
            $table->softDeletes();
        });

        DB::unprepared('
        CREATE TRIGGER nis_student_insert AFTER INSERT ON `students` FOR EACH ROW
        BEGIN
         INSERT INTO students_scores (`nis_student`) value (new.nis);
        END
        ');
 }
    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students');
        DB::unprepared('DROP TRIGGER `nis_student_insert`');
    }
}
