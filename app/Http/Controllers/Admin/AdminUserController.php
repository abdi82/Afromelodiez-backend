<?php

namespace App\Http\Controllers\Admin;

use Edujugon\PushNotification\PushNotification;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Betting;
use App\Models\BetReport;
use App\Models\Category;
use App\Models\Emoji;
use App\Models\User;
use App\Models\Artist;
use Illuminate\Support\Facades\Auth;
use Thumbnail;
use DB;
use Artisan;
use Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\songsRecord;
use Carbon\Carbon;


class AdminUserController extends Controller
{
   
    /*
    |--------------------------------------------------------------------------
    | Betting Form Page In admin Panel
    |--------------------------------------------------------------------------
    */

    public function index()
    {
         if (Auth::user()->user_role == 'artist') {
               return redirect('/song');
           }
           else if (Auth::user()->user_role == 'playlist') {
               return redirect('/featuredlist');
           }
           else if (Auth::user()->user_role == 'album') {
               return redirect('/albumlist');
           }
           else if (Auth::user()->user_role == 'podcast') {
               return redirect('/podcastlist');
           }
        $category = Category::all();
        $emoji = Emoji::all();
      
        return view('admin.index',compact('category','emoji'));
    }
    public function Register()
    {
        
        return view('admin.adminRegister.register');
    }
 
    public function artist_admin()
    {
        return view('admin.indexArtist');
    }

    public function Register_admin(Request $request)
    {
        $rules = array(
         'name' =>'required|string|max:255',
         'email'=>'required|email',
          'password' => 'required',
//         'image' => 'required|image:jpeg,png,jpg,gif,svg|max:2048'
        );
          $message='';
         $validator = Validator::make( $request->all(), $rules);
            if ( $validator->fails() ) 
            {
                    $message = [
                        'message' => $validator->errors()->first()
                    ];
                     return response()->json($message);

                     return redirect()->route('user.list')->with($message);   
            }
            else
            {
                if (User::where('email', '=', $request->email)->count() > 0)
                {
                return redirect()->route('user.list')->with('message','User is already Exist');                  
                }
                else
                {   

                        $user = new User;
                        $user->name = $request->name;
                        $user->email = strtolower($request->email);
                        $user->password = Hash::make($request->password);
                        //$user->country = $request->country;
                        if($request->user_role != '')
                        {
                          $user->user_role = $request->user_role;
                        }
                        //$user->artist_id = $artist->id;
                        $user->save();

                        //$message='Admin Manager user is sucessfully added';

                        return redirect()->route('user.list')->with('message','Admin Manager user is sucessfully added');
                }
               
                 
            }
    
    }

     /*
    |--------------------------------------------------------------------------
    | Get Form Page In admin Panel
    |--------------------------------------------------------------------------
    */
    public function getform()
    {
        $category = Category::all();
        $emoji = Emoji::all();
        return view('admin.index',compact('category','emoji'));
    }

    /*
    |--------------------------------------------------------------------------
    | Store Betting form data in Database 
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {

      $validated = $request->validate([

        'video_link1' => 'required|max:15000',
        'video_link2' => 'required|max:15000',
        'tag_1' => 'required|max:255',
        'tag_2' => 'required|max:255',
        'caption_1' => 'required|max:255',
        'caption_2' => 'required|max:255',

      ]);

        $video1 = time().'_1.'.$request->file('video_link1')->extension(); 
        $video2 = time().'_2.'.$request->file('video_link2')->extension();
        $request->video_link1->move(public_path('betting_videoes'), $video1);
        $request->video_link2->move(public_path('betting_videoes'), $video2);
    
        $video_path = public_path('betting_videoes/'.$video1.'');
        $video_path2 = public_path('betting_videoes/'.$video2.'');
        $thumbnail_path = public_path('thumbnail');
        $thumbnail_image = $video1.'.jpg';
        $thumbnail_image2 = $video2.'.jpg';
       // $time_to_image = 2; // time to take snapshot of video
        $thumbnail_status = Thumbnail::getThumbnail($video_path,$thumbnail_path,$thumbnail_image);
        $thumbnail_status2 = Thumbnail::getThumbnail($video_path2,$thumbnail_path,$thumbnail_image2);
   

        $betting = Betting::create([

            'video_link1' => $video1,
            'video_link2' => $video2,
            'time' => $request->time,
            'money' => $request->money,
            'member_qty' => $request->member_qty,
            'emoji_1' => $request->emoji_1,
            'emoji_2' => $request->emoji_2,
            'tag_1' => $request->tag_1,
            'tag_2' => $request->tag_2,
            'trending' => $request->trending,
            'category_id' => $request->category_id,
            'caption_1' => $request->caption_1,
            'caption_2' => $request->caption_2,
            'userid_1' =>  $userid = Auth::user()->id,
            'userid_2' =>  $userid = Auth::user()->id,

        ]);

        return back()->with('status', 'Betting created successfully');

    }

    /*
    |--------------------------------------------------------------------------
    | Get Youtube video Likes via Api
    |--------------------------------------------------------------------------
    */

    function get_youtube(){

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://www.googleapis.com/youtube/v3/videos?id=20evunLzSgk&key=AIzaSyCjphBRu3_Qq9zyWYXwir5gEn56iiCTNBU&part=statistics',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    echo $response;
     }

    /*
    |--------------------------------------------------------------------------
    | Users Listing in Admin Panel 
    |--------------------------------------------------------------------------
    */

    public function managerList()
    {
        Artisan::call('cache:clear');
    $users = User::where('user_role','manager')
    ->paginate(10);
    return view('admin.users.index',compact('users'));
    }
   
    public function userList()
    {
        Artisan::call('cache:clear');
    $users = User::where('user_role','user')->orderBy('id', 'DESC')
    ->paginate(10);
    return view('admin.users.index',compact('users'));
    }

    public function CurrentListenersUsers()
    {
        Artisan::call('cache:clear');

        $users= songsRecord::leftJoin('users','users.id','=','songs_records.user_id')
          ->select('users.*')
          ->where('songs_records.created_at','>',Carbon::now()->subMinutes(5)->toDateTimeString())
         ->paginate(10);


        return view('admin.users.index',compact('users'));
    }

    /*
    |--------------------------------------------------------------------------
    | Change User Status via ajax Admin Panel 
    |--------------------------------------------------------------------------
    */

    public function changeStatus(Request $request)
        {
            $user = User::find($request->user_id);
            $user->status = $request->status;
            $user->save();
    
            return response()->json(['success'=>'Status change successfully.']);
        }
        /*
    |--------------------------------------------------------------------------
    | Edit User Status in Admin Panel 
    |--------------------------------------------------------------------------
    */
    public function edit($id)
    {   
         $user=User::find($id);

         return view('admin.users.edit',compact('user'));
    }
    public function admin_update(Request $request ,$id){
            
            $data['password']=Hash::make($request->password);


            $user=User::where('id', '=', $id)->update($data);

            return redirect()->route('user.list')->with('message','Admin Manager user is sucessfully added');

    }
    public function update(Request $request){

         $validated = $request->validate([
            'email' => 'required|email'
        ]); 

                        $artist['name']=$request->name;
                        $artist['location']=$request->country;

                        $artist = Artist::create($artist);

                        $user = new User;
                        $user->name = $request->name;
                        $user->email = strtolower($request->email);
                        $user->password = Hash::make($request->password);
                        $user->country = $request->country;
                        if($request->user_role != '')
                        {
                          $user->user_role = $request->user_role;
                        }
                        $user->artist_id = $artist->id;
                        $user->save();

                        $message='Admin Artist user is sucessfully';

                        return redirect()->route('user.list')->with('message','Admin User added sucessfully');

    }
    /*
    |--------------------------------------------------------------------------
    | Delete User Status in Admin Panel 
    |--------------------------------------------------------------------------
    */


     public function delete($id){
         
        $task = User::findOrFail($id);

        $task->delete();
        return back()->with('status', 'User deleted successfully');
    }


    /*
    |--------------------------------------------------------------------------
    | Betting List in Admin Panel 
    |--------------------------------------------------------------------------
    */

    public function bettingList(){

      $bettingList = Betting::with('category')->paginate(10);

      return view('admin.bettings.index',compact('bettingList'));

    }





/*
|--------------------------------------------------------------------------
| Delete Bet Api with Delete video and thumbnail
|--------------------------------------------------------------------------
*/


public function deleteBet($id){

    $bet = Betting::findorFail($id);
    $thumbDirectory = public_path('thumbnail');
    $file_path = public_path('betting_videoes/'.$bet->video_link1.'');
    $file_path2 = public_path('betting_videoes/'.$bet->video_link2.'');
              if (isset($file_path) && !empty($file_path)) {
                   unlink($file_path);
                   unlink($file_path2);
                   unlink($thumbDirectory.'/'.$bet->video_link1.'jpg');
                   unlink($thumbDirectory.'/'.$bet->video_link2.'jpg');
                   Betting::where('id',$id)->delete();
              }
}



/*
|--------------------------------------------------------------------------
| Listing of Betting report in admin panel
|--------------------------------------------------------------------------
*/


public function reportList()
{

    $reportList = User::paginate(10);
    return view('admin.bettings.reports',compact('reportList'));
}



public function logout(Request $request) {
    Auth::logout();
    return redirect('/login');
  }

  // public function logoutartist($userid) {

  //   if($request->user_id != '')
  //   {
  //    Auth::logoutUsingId($id);
  //    return redirect('/login');
  //   }
  // }


}
