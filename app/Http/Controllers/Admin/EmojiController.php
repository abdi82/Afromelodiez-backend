<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Emoji;

class EmojiController extends Controller
	{
    public function index()
    {
    	return view('admin.emoji');
    }
    public function store(Request $request)
    {
    	// $request->validate([
     //        'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
     //    ]);
    
        $imageName = $request->file('image')->getClientOriginalName();  

     
        $request->image->move(public_path('emoji'), $imageName);
  
        /* Store $imageName name in DATABASE from HERE */
          $emoji = new Emoji;

        $emoji->image = $imageName;

        $emoji->save();
        
        return back()
            ->with('success','You have successfully upload image.')
            ->with('image',$imageName); 
    }
}
