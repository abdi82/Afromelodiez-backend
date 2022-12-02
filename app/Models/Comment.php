<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\Betting;
use App\Model\User;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = ['message','message_type', 'user_id', 'bet_id','round'];
 
  public function bet()
  {
    return $this->belongsTo(Betting::class);
  }
 
  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
