<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact_us extends Model
{
    use HasFactory;
     protected $fillable = [
        'name',
        'company_name',
        'message',
        'email',
       
    ];
}
