<?php

namespace App\Http\Controllers\Admin;

use Edujugon\PushNotification\PushNotification;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Album;
use App\Models\User;
use App\Models\Song;
use Illuminate\Support\Facades\Auth;
use Thumbnail;
use Carbon;
use DB;
use Artisan;
use Illuminate\Support\Str;

class AlbumController extends Controller
{ 

    /*
    |--------------------------------------------------------------------------
    | Betting Form Page In admin Panel
    |--------------------------------------------------------------------------
    */

    public function index()
    {
       if (Auth::user()->user_role =="superAdmin"){
           $album = Album::paginate(10);    
       }else{
           $album = Album::where('artist_id',Auth::user()->id)->paginate(10);
       }
            
        return view('admin.album.index',compact('album'));
    }
    public function store(Request $request)
    {
        $data=$request->all();
        
        $validated = $request->validate([
            'name' => 'required'
        ]);

        if ($request->hasFile('image')) {
            $images = $request->image->getClientOriginalName();
            $images = time().'_'.$images; // Add current time before image name
            $images = str_replace(' ', '', $images);
            $data['image'] = $images;
            $upload = $request->file('image')->move(storage_path('app/public/album/'), $images);
        }
        else{
            $data['image'] ='';
        }

        $data['creator_id'] = Auth::user()->id;
        $Album = Album::create($data);

         return redirect()->route('albumlist')->with('message','Album Added Successfully');

    }
    public function get_album_form()
    {

        return view('admin.album.add');
    }
    public function edit($id)
    {
          $album=Album::find($id);

        return view('admin.album.edit',compact('album'));
    }
    public function update(Request $request ,$id)
    {   
        $data = request()->except(['_token']);
        
        $validated = $request->validate([
            'name' => 'required'
        ]);
         $old_data=Album::where('id',$id)->first();

        if ($request->hasFile('image')) {
            if($old_data->image != '')
            { 
                if (file_exists('app/public/album/'.$old_data->image)) {
                    unlink(storage_path('app/public/album/'.$old_data->image));
                }
            }
            $images = $request->image->getClientOriginalName();
            $images = time().'_'.$images; // Add current time before image name
            $images = str_replace(' ', '', $images);
            $data['image'] = $images;
            $upload = $request->file('image')->move(storage_path('app/public/album/'), $images);
        }
        else{
            $data['image'] ='';
        }

          $artist=Album::where('id',$id)->update($data);
          
        return redirect()->route('albumlist')->with('message','Album Updated Successfully');
    }
    public function delete($id)
    {
        $Album=Album::find($id);
         
        if($Album->image != '')
        {   
            if (file_exists('app/public/album/'.$Album->image)) {
            unlink(storage_path('app/public/album/'.$Album->image));
            }
        }
        $data['album']='';
        $songs=Song::where('album',$id)->update($data);

        $Album->delete();



        return redirect()->route('albumlist')->with('message','Album Deleted Successfully');
    }

    //Artist 

    public function index_artist()
    {

        $userId = Auth::id();

        $userartist_id=User::where('id',$userId)->first();
        $album = Album::where('artist_id','=',$userartist_id->artist_id)
                     ->paginate(10);

        return view('admin.artistAlbum.index',compact('album'));
    }
    public function store_artist(Request $request)
    {
        $data=$request->all();
        
        $validated = $request->validate([
            'name' => 'required'
        ]);

        if ($request->hasFile('image')) {
            $images = $request->image->getClientOriginalName();
            $images = time().'_'.$images; // Add current time before image name
            $images = str_replace(' ', '', $images);
            $data['image'] = $images;
            $upload = $request->file('image')->move(storage_path('app/public/album/'), $images);
        }
        else{
            $data['image'] ='';
        }


        $Album = Album::create($data);

         return redirect()->route('albumlist_artist')->with('message','Album Added Successfully');

    }
    public function get_album_form_artist()
    {

        return view('admin.artistAlbum.add');
    }
    public function edit_artist($id)
    {
          $album=Album::find($id);

        return view('admin.artistAlbum.edit',compact('album'));
    }
    public function update_artist(Request $request ,$id)
    {   
        $data = request()->except(['_token']);
        
         $old_data=Album::where('id',$id)->first();

        if ($request->hasFile('image')) {
            if($old_data->image != '')
            {  
                if (file_exists('app/public/album/'.$old_data->image)) {
                     unlink(storage_path('app/public/album/'.$old_data->image));
                 }
            }
            $images = $request->image->getClientOriginalName();
            $images = time().'_'.$images; // Add current time before image name
            $images = str_replace(' ', '', $images);
            $data['image'] = $images;
            $upload = $request->file('image')->move(storage_path('app/public/album/'), $images);
        }
        else{
            $data['image'] ='';
        }

          $artist=Album::where('id',$id)->update($data);
          
        return redirect()->route('albumlist_artist')->with('message','Album Updated Successfully');
    }
    public function delete_artist($id)
    {
        $Album=Album::find($id);

        if($Album->image != '')
        { 
             if (file_exists('app/public/album/'.$Album->image)) {
            unlink(storage_path('app/public/album/'.$Album->image));
              }
        }
        $data['album']='';
        $songs=Song::where('album',$id)->update($data);


        $Album->delete();
        

        return redirect()->route('albumlist_artist')->with('message','Album Deleted Successfully');
    }

    


}
