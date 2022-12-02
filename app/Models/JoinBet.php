<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JoinBet extends Model
{
    use HasFactory;

     protected $fillable = [
        'userid',
        'betid',
        'status',
        'round'
    ];

    public function betname() {
    return $this->belongsTo(Betting::class); // don't forget to add your full namespace
}
}
