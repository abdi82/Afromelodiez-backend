<?php

namespace App\Http\Controllers\api;
use App\Models\Category;
use App\Models\songsRecord;
use App\Models\Song;
use App\Models\Artist;
use App\Models\Language;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;


class CategoryController extends Controller
{

    public function index()
    {
        $catlist = Category::get();
        return response()->json([
            'success' => true,
            'data' => $catlist
        ], Response::HTTP_OK);
    }

    public function top_genres($id)
    {  

        $skip=0;
        $all_category=array();
        $date_array = array();
        $date_count = array();
        $find=array();
        $i = 0;
        while ($i < 10) {
            $today = Carbon::today();
            array_push( $date_array, $today->subDays($i)->format('Y-m-d') );
            $i++;
        }

        if(! empty( $date_array ) ){
            foreach($date_array as $date){
                $find = songsRecord::where( 'played_date', '>', $date )
                            ->where('user_id', $id)
                            ->get();
            }
        }
        $keys=[];
        $all_categories=[];
              foreach ($find as $value_data) {
                  
                $songID=$value_data->song_id;

                $song=Song::select('*')->where('id', $songID)
                        ->where('song','!=','')
                        ->first();
                if(empty($song))
                {
                    unset($find[$i]);
                }
                else
                { 
                        $artist=Artist::select('*')->where('id', $song->artist_id)
                                             ->first();

                        $language=Language::select('*')->where('id', $song->language_id)
                                             ->first();
                        $category=Category::select('*')->where('id', $song->category_id)
                         ->first();
                        if(!empty($category))
                        {
                         $all_category[]=$category->id;
                        }


					 $count=array_count_values($all_category);//Counts the values in the array, returns associatve array
					arsort($count);//Sort it from highest to lowest

					$keys=array_keys($count);

                }
               }
            $number_data=count($keys);

            $i=1;
	        for($i=0;$i<count($keys);$i++)
	        {   
                if($i < 3)
                {
			        $song = DB::table('songs')
			                ->where('category_id', '=', $keys[$i])
			                ->where('song','!=','')
			                ->get();

			        $categories = DB::table('categories')
			                ->where('id', '=', $keys[$i])
			                ->first(); 

			       foreach ($song as $value) {


			        $artist=Artist::select('*')->where('id', $value->artist_id)
			                             ->first();

			        $language=Language::select('*')->where('id', $value->language_id)
			                             ->first();
			         $category=Category::select('*')->where('id', $value->category_id)
			         ->first();

			            $value->artist_id=$artist;
			            $value->language_id=$language;
			           $value->category_id=$category;

			           $liked=explode(',',$value->liked);

			            $value->liked=$liked;

			           $all_featuring=array();
			          if($value->featuring != "")
			          { 
			            $data_featuring= explode(',',$value->featuring);
			            foreach($data_featuring as $value_fea){

			              $artist=Artist::select('*')->where('id', $value_fea)
			                               ->first();
			               $all_featuring[]=$artist;               
			            }
			          }
			         $value->featuring = $all_featuring;
			       }
			       $categories->songs=$song;
	               $all_categories[]=$categories;
	           }
	           else
	           {
	           	break;
	           }
		    } 


	                 return response()->json([
	                'success' => true,
	                'Category' => $all_categories
	            ], Response::HTTP_OK);
	    }

}
