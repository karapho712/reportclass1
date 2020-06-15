<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsScoresTabel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students_scores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('nis_student');
            $table->integer('totalNilai')->nullable();
            $table->integer('nilaiAkhir')->nullable();
            $table->integer('matematika')->nullable();
            $table->integer('fisika')->nullable();
            $table->integer('kimia')->nullable();
            $table->integer('biologi')->nullable();
            $table->integer('sejarah')->nullable();
            $table->integer('geografi')->nullable();
            $table->string('keteragan')->nullable();;
            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students_scores');
    }
}
