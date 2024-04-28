<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    use HasFactory;

    protected $table = 'classes';
    protected $fillable = [
        'id',
        'name',
        'code',
        'start_date',
        'end_date',
        'schedule',
        'description',
        'created_at',
        'updated_at',
        'course_id'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
