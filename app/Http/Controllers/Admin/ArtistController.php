<?php

namespace App\Http\Controllers\Admin;

use Edujugon\PushNotification\PushNotification;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Artist;
use App\Models\UploadAgreement;
use App\Models\BetReport;
use App\Models\Category;
use App\Models\Emoji;
use App\Models\User;
use App\Models\Song;
use App\Models\episodesRecord;
use Illuminate\Support\Facades\Auth;
use Thumbnail;
use Carbon;
use DB;
use Artisan;
use Illuminate\Support\Str;
use Hash;

class ArtistController extends Controller
{
    public function index()
    {

        $artist = Artist::paginate(10);

        return view('admin.artist.index',compact('artist'));
    }
    public function store(Request $request)
    { 
        $validated = $request->validate([
            'name' => 'required',
            'isVerified' => 'required'
          ]);

        if (User::where('email', '=', $request->email)->count() > 0)
        {
        return redirect()->route('artistlist')->with('message','User with this email already Exist');                  
        }
        else
        {   
                        
                        if ($request->hasFile('image')) {
                            $images = $request->image->getClientOriginalName();
                            $images = time().'_'.$images; 
                            $images = str_replace(' ', '', $images); // Add current time before image name
                            $data['image'] = $images;

                            $upload = $request->file('image')->move(storage_path('app/public/artist/'), $images);
                        }
                        else{
                            $data['image'] ='';
                        }
                        $data['name']=$request->name;

                        $data['location']=$request->location;
                        $data['description']=$request->description;
                        $data['isVerified']=$request->isVerified;
                        $data['facebook']=$request->facebook;
                        $data['twitter']=$request->twitter;
                        $data['youtube']=$request->youtube;
                        $data['instagram']=$request->instagram;
                        $artist = Artist::create($data);
                         
                        // if($request->email != '' && $request->password != '')
                        // { 
                        // $user = new User;
                        // $user->name = $request->name;
                        // $user->email = strtolower($request->email);
                        // $user->password = Hash::make($request->password);
                        // if($request->location != '')
                        //     { 
                        // $user->country = $request->location;
                        // }
                        // else
                        // {
                        //     $user->country = 'None';
                        // }
                        // if($request->user_role != '')
                        // {
                        //   $user->user_role = $request->user_role;
                        // }
                        // $user->artist_id = $artist->id;
                        // $user->save();
                        // }



                  return redirect()->route('artistlist')->with('message','Artist Added Successfully');
                        
        }


    }
    public function indexSearch_artist(Request $request)
    {   
        $search=$request->search;
        if($request->search_artist != '')
        {
            $artist =Artist::select('*')
                ->where('id', '=', $request->search_artist)
                ->first();  
        
        
        return view('admin.artist.indexSearch',compact('artist'));
        }
        else
        {
            return back()->with('message','Please select artist name');
        }
    } 
    public function search_artist(Request $request)
    {   
        $Artist =[];

        if($request->has('q')){
            $search = $request->q;
            $Artist =Artist::select('*')
                ->where('name', 'LIKE', "%$search%")
                ->get();
        } 

       return response()->json($Artist);
    }

    public function get_artist_form()
    {

        return view('admin.artist.add');
    }
    public function edit($id)
    {
          $artist=Artist::find($id);
          
          $user=User::where('artist_id', '=', $id)->first();
          if(!empty($user))
          {
              $email=$user->email;
              $password=$user->password;
          }
          else
          {
            $email = '';
            $password = '';
          }


        return view('admin.artist.edit',compact('artist','email','password'));
    }
    public function update(Request $request ,$id)
    {   
        $validated = $request->validate([
            'image' => 'mimes:jpeg,jpg,png,gif',
            'name' => 'required',
            'isVerified' => 'required'
        ]);
        $data = request()->except(['_token','email','password','user_role']);

        $old_data=Artist::where('id',$id)->first();

        if ($request->hasFile('image')) {
            if($old_data->image != '')
            { 
                 if (file_exists('app/public/artist/'.$old_data->image)) {
                 unlink(storage_path('app/public/artist/'.$old_data->image));
               }
            }
            $images = $request->image->getClientOriginalName();
            $images = time().'_'.$images; // Add current time before image name
            $images = str_replace(' ', '', $images);
            $data['image'] = $images;
            $upload = $request->file('image')->move(storage_path('app/public/artist/'), $images);
        }

        $artist=Artist::where('id',$id)->update($data); 
       
        // if($request->email != '' && $request->password != '')
        // {
        //     if (User::where('artist_id', '=', $id)->count() > 0)
        //     {
        //            $user['email']= $request->email;
        //            $user['password']= Hash::make($request->password);
                   
        //             $userUpdate=User::where('artist_id',$id)->update($user);    
        //     }
        //     else
        //     {   
        //                     $user = new User;
        //                     $user->name = $request->name;
        //                     $user->email = strtolower($request->email);
        //                     $user->password = Hash::make($request->password);
        //                     if($request->location != '')
        //                     { 
        //                         $user->country = $request->location;
        //                     }
        //                     else
        //                     {
        //                         $user->country = 'None';
        //                     }
        //                     if($request->user_role != '')
        //                     {
        //                       $user->user_role = $request->user_role;
        //                     }
        //                     $user->artist_id = $id;
        //                     $user->save();
        //     }
                            
            
        // }

          
        return redirect()->route('artistlist')->with('message','Artist Updated Successfully');
    }
    public function delete($id)
    {
        $artist=Artist::find($id);

        if($artist->image != '')
        { 
            if (file_exists('app/public/artist/'.$artist->image)) {
            unlink(storage_path('app/public/artist/'.$artist->image));
             }
        }
        $data['artist_id']='';
        $songs=Song::where('album',$id)->update($data);

        $record_delete=episodesRecord::where('artist_id',$id)->delete();

        $artist->delete();

        return redirect()->route('artistlist')->with('message','Artist Deleted Successfully');
    }

    public function MostlistenedArtist()
    {
         $artist=Artist::where('played','>',100)->paginate(10);
        return view('admin.artist.index',compact('artist'));
    }

    //Show Agreement
    public function showAgreement(){
        $agreement =UploadAgreement::where('user_id',Auth::user()->id)->first();
        return view('admin.artist.upload_agreement',compact('agreement'));
    }
    // Add Agreement
    public function add_agreement(Request $request){
        if ($request->hasFile('agreement')) {
            $images = $request->agreement->getClientOriginalName();
            $images = Auth::user()->email. '_' . time() . '_' . $images; // Add current time before image name
            $images = str_replace(' ', '', $images);
            $data['agreement'] = $images;
            $data['user_id'] =Auth::user()->id;
            $upload = $request->file('agreement')->move(storage_path('app/public/artistagreement/'), $images);
              UploadAgreement::create($data);
            return redirect()->route('agreement')->with('message','Agreement uploaded Successfully');
        }
    }

    public function agreement_listing (){
        $agreement =UploadAgreement::with('user')->paginate(10);
         return view('admin.artist.agreement_list',compact('agreement'));
    }

    public function delete_agreement($id){

        UploadAgreement::find($id)->delete();
        return redirect()->route('agreement')->with('message','Agreement Deleted Successfully');

    }
    public function indexSearch_artist_agreement(Request $request)
    {
        $search=$request->search;
        if($request->search_artist != '')
        {
            $agreement =UploadAgreement::with('user')
                ->where('user_id', '=', $request->search_artist)
                ->first();


            return view('admin.artist.indexagreement',compact('agreement'));
        }
        else
        {
            return back()->with('message','Please select artist name');
        }
    }
}
