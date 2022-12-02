<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class userPlaylist extends Model
{
    use HasFactory;
    protected $table = 'user_playlists';
    protected $guarded = [];
}
