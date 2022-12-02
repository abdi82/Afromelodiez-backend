<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class episodesRecord extends Model
{
    use HasFactory;
    protected $table = 'episodes_records';
    protected $guarded = [];
}
