<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class TeacherSubject extends Model
{
    public $timestamps = false;
    protected $table = 'subject_teacher_join';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'subject',
        'teacher',
    ];
}
