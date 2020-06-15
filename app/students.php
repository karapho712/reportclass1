<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class students extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nis', 'nama', 'kelas'
    ];

    protected $hidden= [

    ];

    public function gradeStudents(){
        return $this->hasOne(studentsScores::class, 'nis_student' ,'nis');
    }

}
