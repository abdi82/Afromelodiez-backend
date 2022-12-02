<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Likevideo extends Model
{
    use HasFactory;
    protected $fillable = [
        'vid',
        'betid',
        'userid',
        'likes',
    ];
}
