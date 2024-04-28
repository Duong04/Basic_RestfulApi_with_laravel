<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class course extends Model
{
    use HasFactory;
    protected $table = 'courses';
    protected $fillable = [
        'id',
        'course_code',
        'course_name',
        'course_image',
        'created_at',
        'updated_at',
    ];

    public function classes()
    {
        return $this->hasMany(Classe::class);
    }
}
