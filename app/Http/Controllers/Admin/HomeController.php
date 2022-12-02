<?php

namespace App\Http\Controllers\Admin;

use Edujugon\PushNotification\PushNotification;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Song;
use App\Models\songsRecord;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use App\Models\Artist;
use App\Models\Category;
use App\Models\Language;
use Illuminate\Support\Facades\Auth;
use Thumbnail;
use DB;
use Artisan;
use Illuminate\Routing\UrlGenerator;
use Carbon\Carbon;



class HomeController extends Controller
{
   public function home()
   {
   	  $song=Song::get();
   	  $songTotal=$song->count();

   	  return view('admin.indexArtist',compact('songTotal'));
   }
}