<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class songsRecord extends Model
{
    use HasFactory;
    protected $table = 'songs_records';
    protected $guarded = [];
}
