<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Countries extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'apps_countries';
    protected $fillable = [
        'id',
        'country_code',
        'country_name'
    ];
}
