<?php

namespace App\Http\Controllers\api;

use Edujugon\PushNotification\PushNotification;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Song;
use App\Models\songsRecord;
use App\Models\User;
use App\Models\Artist;
use App\Models\Category;
use App\Models\Language;
use App\Models\featuredPlaylists;
use Illuminate\Support\Facades\Auth;
use Thumbnail;
use DB;
use Artisan;
use Illuminate\Routing\UrlGenerator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\mailfromapp;
use Symfony\Component\HttpFoundation\Response;

class EmailController extends Controller
{
    public function mail()
	{
	   $name = 'Test';
	   //$mail= Mail::to('developer1607@gmail.com')->send(new MailNotify($user));
	    $mail= Mail::to('developer1607@gmail.com')->send(new mailfromapp($name));
	   
	   return 'Email sent Successfully';
	}

	protected function resend(Request $request)
	{
	    $user = User::where('email', $request->input('email'))->first();
	    $user->verifyToken = Str::random(40);
	    $user->save();

	    $this->sendEmail($user);

	    return $user;
	}
}