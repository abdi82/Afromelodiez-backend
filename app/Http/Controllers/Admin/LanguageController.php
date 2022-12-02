<?php

namespace App\Http\Controllers\Admin;

use Edujugon\PushNotification\PushNotification;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Language;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Thumbnail;
use Carbon;
use DB;
use Artisan;


class LanguageController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Betting Form Page In admin Panel
    |--------------------------------------------------------------------------
    */

    public function index()
    {

        $language = Language::paginate(10);

        return view('admin.language.index',compact('language'));
    }
    public function store(Request $request)
    {
        $data=$request->all();

        $validated = $request->validate([
            'name' => 'required'
        ]);
 
        $language = Language::create($data);

        return redirect()->route('language')->with('message','Language Added Successfully');

    }
    public function language_form()
    {

        return view('admin.language.add');
    }
    public function edit($id)
    {
        $language=Language::find($id);

        return view('admin.language.edit',compact('language'));
    }
    public function update(Request $request ,$id)
    {
        $data = request()->except(['_token']);
        $artist=Language::where('id',$id)->update($data);

        return redirect()->route('language')->with('message','language Updated Successfully');
    }
    public function delete($id)
    {
        $user=Language::find($id);
        $user->delete();

        return redirect()->route('language')->with('message','language Deleted Successfully');
    }




}
