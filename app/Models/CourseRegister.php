<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseRegister extends Model
{
    use HasFactory;

    protected $table = 'course_registrations';
    protected $fillable = [
        'id',
        'user_id',
        'course_id',
        'class_id',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class, 'class_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
