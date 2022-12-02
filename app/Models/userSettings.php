<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class userSettings extends Model
{
    use HasFactory;
    protected $table = 'user_settings';
    protected $guarded = [];
}
