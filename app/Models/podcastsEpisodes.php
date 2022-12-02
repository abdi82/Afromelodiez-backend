<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class podcastsEpisodes extends Model
{
    use HasFactory;
    protected $table = 'podcasts_episodes';
    protected $guarded = [];
}
