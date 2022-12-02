<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Betting extends Model
{
    use HasFactory;

    protected $fillable = [
        'video_link1',
        'video_link2',
        'emoji_1',
        'tag_1',
        'emoji_2',
        'tag_2',
        'time',
        'money',
        'member_qty',
        'category_id',
        'trending',
        'round',
        'caption_1',
        'caption_2',
        'userid_1',
        'userid_2',
        'publish',
        'trending_time',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

}
