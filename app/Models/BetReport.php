<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class BetReport extends Model
{
    use HasFactory;

    protected $fillable = ['report_type','bet_id','user_id','video_link'];

    public function user() {
        return $this->belongsTo(User::class,'user_id');
    }
}
