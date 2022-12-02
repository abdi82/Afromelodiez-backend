<?php

namespace App\Http\Controllers\Admin;
use App\Models\Category;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index()
    {
        $catlist = Category::paginate(10);
        return view('admin.category.index')->with('catlist',$catlist);
    }

    public function create()
    {
        return view('admin.category.create');
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
            $upload = $request->file('image')->move(storage_path('app/public/category/'), $images);
        }
        else{
            $data['image'] ='';
        }
        $category = Category::create($data);
        return redirect()->route('cat.list')->with('message', 'Category created successfully');
    }

    public function edit($id)
    {
        $category = Category::find($id);
        return view('admin.category.edit')->with('category',$category);
    }

    public function update(Request $request, $id)
    {  
        $data = request()->except(['_token']);

        $old_data=Category::where('id',$id)->first();

        
        if ($request->hasFile('image')) {
            if($old_data->image != '')
            { 
                if (file_exists('app/public/category/'.$old_data->image)) {
                  unlink(storage_path('app/public/category/'.$old_data->image));
               }
            }
            $images = $request->image->getClientOriginalName();
            $images = time().'_'.$images; // Add current time before image name
            $images = str_replace(' ', '', $images);
            $data['image'] = $images;
            $upload = $request->file('image')->move(storage_path('app/public/category/'), $images);
        }

        $song=Category::where('id',$id)->update($data);

        return redirect()->route('cat.list')->with('message', 'Category updated successfully');
    }

    public function delete($id)
    {
        $task = Category::findOrFail($id);
        if($task->image != '')
        {   if (file_exists('app/public/category/'.$task->image)) {
            unlink(storage_path('app/public/category/'.$task->image));
            }
        } 
        $task->delete();
    
        return redirect()->route('cat.list')->with('message', 'Category deletd successfully');
    }
}
