<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class featuredPlaylists extends Model
{
    use HasFactory;
    protected $table = 'featured_playlists';
    protected $guarded = [];
}
