<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class studentsScores extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'nis_student';

    protected $fillable = [
        'nis_student',  'matematika', 'fisika', 'kimia', 'biologi', 'sejarah', 'geografi', 'keteragan', 'totalNilai', 'nilaiAkhir'
    ];

    protected $hidden= [
        
    ];

    public function nisStudents(){
        return $this->belongsTo(students::class, 'nis_student','nis');
    }
}
