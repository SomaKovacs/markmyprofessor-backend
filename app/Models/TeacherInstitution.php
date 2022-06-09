<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class TeacherInstitution extends Model
{
    public $timestamps = false;
    protected $table = 'institution_teacher_join';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'institution',
        'teacher',
    ];
}
