<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class featuredArtists extends Model
{
    use HasFactory;
    protected $table = 'featured_artists';
    protected $guarded = [];
}
