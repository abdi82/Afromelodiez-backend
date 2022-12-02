<?php


use App\Models\Song;
use App\Models\songsRecord;
use App\Models\User;
use App\Models\Artist;
use App\Models\Category;
use App\Models\Language;
use App\Models\Album;
use App\Models\advertisements;
use App\Models\podcast;
use App\Models\podcastsEpisodes;
use App\Models\episodesRecord;
use App\Models\AdRecord;
use Carbon\Carbon;

function restyle_text($input){
    $input = number_format($input);
    $input_count = substr_count($input, ',');
    if($input_count != '0'){
        if($input_count == '1'){
            return substr($input, 0, -4).'k';
        } else if($input_count == '2'){
            return substr($input, 0, -8).'mil';
        } else if($input_count == '3'){
            return substr($input, 0,  -12).'bil';
        } else {
            return;
        }
    } else {
        return $input;
    }
    // if (Auth::user()->id == $song->user_role->artist)
    // {
    // return redirect('/song');
    // }
    
}



        $listenerdata =songsRecord::whereBetween('created_at',[Carbon::now()->subDays(30),Carbon::now()])->groupBy('user_id')->get()->count();

   $users_visit =AdRecord::count();


 $song=Song::get();
 $songTotal=restyle_text($song->count()); 

 $User=User::get();
 $UserTotal=restyle_text($User->count()); 

 $AdminUser=User::where('user_role','admin')->get();
 $AdminTotal=restyle_text($AdminUser->count()); 
 
 $Album=Album::get();
 $AlbumTotal=restyle_text($Album->count()); 
 
 $Artist=Artist::get();
 $ArtistTotal=restyle_text($Artist->count()); 
 
 $Ad=advertisements::get();
 $AdTotal=restyle_text($Ad->count());

$Song=Song::where('played' ,'>','100')->get();
 $MostListened=restyle_text($Song->count());

 $MostlistenedArtist=Artist::where('played','>',100)->get();
 $MostArtistTotal=restyle_text($MostlistenedArtist->count()); 

  $podcast=podcast::get();
 $Totalpodcast=restyle_text($podcast->count());

 // $users_visit=AdRecord::get();
 // $Totalpodcast=restyle_text($users_visit->count());

  $language=Language::get();
 $Totallanguage=restyle_text($language->count());

 $songsRecord=songsRecord::distinct()->select('id')->where('updated_at', '>', Carbon::now()->subMinutes(5)->toDateTimeString())->groupBy('user_id')->get();
  $Listeners=restyle_text($songsRecord->count());

  $podcastsEpisodes=podcastsEpisodes::get();
 $TotalpodcastEpisodes=restyle_text($podcastsEpisodes->count());

   $episodesRecord=episodesRecord::distinct()->select('id')->where('updated_at', '>', Carbon::now()->subMinutes(5)->toDateTimeString())->groupBy('user_id')->get();

  $episodesListeners=$episodesRecord->count();

  $Hometotalbates = AdRecord::leftJoin('advertisements','advertisements.id','=','ad_records.ad_id')
          ->select('*')
          ->where('advertisements.banner_type','l')
         ->get();
        $homepopup=0;
        foreach($Hometotalbates as $valuehome)
        {
           $homepopup=$homepopup+$valuehome->played;
        } 
     $homepopup=restyle_text($homepopup);
       
  $Popuptotalbates = AdRecord::leftJoin('advertisements','advertisements.id','=','ad_records.ad_id')
          ->select('*')
          ->where('advertisements.banner_type','s')
         ->get();
         $sumpopup=0;
        foreach($Popuptotalbates as $value)
        {
           $sumpopup=$sumpopup+$value->played;
        } 
        $sumpopup =restyle_text($sumpopup);
?>
@extends('admin_layout.layout')
@section('content')
    <div class="row">
        <!-- column -->
        <div class="col-12">
            <div class="card">
                <div class="card-body">
          <section class="content">
            <div class="container-fluid">
               <div class="row">
                    <div class="col-lg-3 col-6">
                     <!-- small box -->
                     <div class="small-box bg-orange">
                        <div class="inner">
                           <h3><?php echo $songTotal; ?></h3>
                           <p>Total Songs</p>
                        </div>
                        <div class="icon">
                           <i class="ion ion-music-note"></i>
                        </div>
                        <a href="{{url('')}}/song" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                     </div>
                  </div>
                                    <!-- ./col -->
                  <div class="col-lg-3 col-6">
                     <!-- small box -->
                     <div class="small-box bg-success">
                        <div class="inner">
                          <h3><?php  echo $UserTotal; ?> </h3>
                           <p>Total Users</p>
                        </div>
                        <div class="icon">
                           <i class="ion ion-person-add"></i>
                        </div>
                        <a href="{{url('')}}/admin/users" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                     </div>
                  </div>
                                    <!-- ./col -->
                                    <div class="col-lg-3 col-6">
                     <!-- small box -->
                     <div class="small-box bg-blue">
                        <div class="inner">
                          <h3><?php echo $AdminTotal; ?> </h3>
                           <p>Admin Users</p>
                        </div>
                        <div class="icon">
                           <i class="ion ion-person-stalker"></i>
                        </div>
                        <a href="{{url('')}}/admin/managers" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                     </div>
                  </div>
                                                      <div class="col-lg-3 col-6">
                     <!-- small box -->
                     <div class="small-box bg-info">
                        <div class="inner">
                           <h3><?php echo $ArtistTotal; ?> </h3>
                           <p>Total Artists</p>
                        </div>
                        <div class="icon">
                           <i class="ion ion-ios-albums"></i>
                        </div>
                        <a href="{{url('')}}/artistlist" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                     </div>
                  </div>
                                    <!-- ./col -->
                                    <div class="col-lg-3 col-6">
                     <!-- small box -->
                     <div class="small-box bg-warning">
                        <div class="inner">
                           <h3><?php echo $AdTotal; ?></h3>
                           <p>Total Ads</p>
                        </div>
                        <a href="{{url('')}}/adlist" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                     </div>
                  </div>
                                    <div class="col-lg-3 col-6">
                     <div class="small-box bg-purple">
                         <div class="inner">
                             <h3><?php echo $MostListened; ?></h3>                     
                             <p>Most Listened Songs</p>
                         </div>
                         <div class="icon">
                             <i class="ion ion-pie-graph"></i>
                         </div>
                         <a href="{{route('mostlistenedSong')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                     </div>
                     </div> 
                    <div class="col-lg-3 col-6">
                     <div class="small-box bg-info">
                         <div class="inner">
                             <h3><?php echo $MostArtistTotal; ?></h3>                     
                             <p>Most Listened Artists</p>
                         </div>
                         <div class="icon">
                             <i class="ion ion-pie-graph"></i>
                         </div>
                         <a href="{{route('MostlistenedArtist')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                     </div>
                     </div>
                     <div class="col-lg-3 col-6">
                     <div class="small-box bg-orange">
                         <div class="inner">
                             <h3><?php echo $Totalpodcast; ?></h3>                     
                             <p>Total Podcasts</p>
                         </div>
                         <div class="icon">
                             <i class="ion ion-pie-graph"></i>
                         </div>
                         <a href="{{url('')}}/podcastlist" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                     </div>
                     </div>
                                                         <div class="col-lg-3 col-6">
                     <!-- small box -->
                     <div class="small-box bg-blue">
                        <div class="inner">
                          <h3><?php echo $TotalpodcastEpisodes; ?> </h3>
                           <p>Total Podcasts episodes</p>
                        </div>
                        <div class="icon">
                           <i class="ion ion-person-stalker"></i>
                        </div>
                        <a href="{{url('')}}/episodeslist" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                     </div>
                  </div>
                                          <div class="col-lg-3 col-6">
                     <div class="small-box bg-info">
                         <div class="inner">
                             <h3><?php echo $Totallanguage; ?></h3>                     
                             <p>Total Languages</p>
                         </div>
                         <div class="icon">
                             <i class="ion ion-pie-graph"></i>
                         </div>
                         <a href="{{url('')}}/language" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                     </div>
                     </div>
                    <div class="col-lg-3 col-6">
                     <!-- small box -->
                     <div class="small-box bg-warning">
                        <div class="inner">
                           <h3><?php echo $Listeners; ?></h3>
                           <p>Music Listeners</p>
                        </div>
                        <div class="icon">
                           <i class="ion ion-ios-albums"></i>
                        </div> 
                        <?php if($Listeners == '0')
                        {?>
                        <a href="{{route('CurrentListenersUsers')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                      <?php } 
                      else
                        { ?> <a href="{{route('CurrentListenersUsers')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> <?php } ?>

                     </div>
                  </div>
                   <div class="col-lg-3 col-6">
                     <!-- small box -->
                     <div class="small-box bg-success">
                        <div class="inner">
                           <h3><?php echo $listenerdata; ?></h3>
                           <p>Monthly Listeners</p>
                        </div>
                        <div class="icon">
                           <i class="ion ion-ios-albums"></i>
                        </div>
                        <?php if($listenerdata == '0')
                        {?>
                        <a href="{{route('monthly.listener')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                      <?php }
                      else
                        { ?> <a href="{{route('monthly.listener')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> <?php } ?>

                     </div>
                  </div>
                                    <div class="col-lg-3 col-6">
                     <!-- small box -->
                     <div class="small-box bg-success">
                        <div class="inner">
                          <h3><?php  echo $episodesListeners; ?> </h3>
                           <p>Podcast Listeners</p>
                        </div>
                        <div class="icon">
                           <i class="ion ion-person-add"></i>
                        </div>
                        <?php if($episodesListeners == '0')
                        {?>
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                      <?php } 
                      else
                        { ?> <a href="{{route('PodcastCurrentListeners')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a><?php } ?>


                     </div>
                  </div>
                                    <div class="col-lg-3 col-6">
                     <!-- small box -->
                     <div class="small-box bg-blue">
                        <div class="inner">
                          <h3><?php echo $homepopup; ?>  </h3>
                           <p>Home Screen Bates</p>
                        </div>
                        <div class="icon">
                           <i class="ion ion-person-stalker"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                     </div>
                  </div>
                                                      <div class="col-lg-3 col-6">
                     <div class="small-box bg-purple">
                         <div class="inner">
                             <h3><?php echo $sumpopup; ?></h3>                     
                             <p>Popup Bates</p>
                         </div>
                         <div class="icon">
                             <i class="ion ion-pie-graph"></i>
                         </div>
                         <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                     </div>
                     </div> 
                     <div class="col-lg-3 col-6">
                     <!-- small box -->
                     <div class="small-box bg-blue">
                        <div class="inner">
                           <h3><?php echo $users_visit; ?></h3>
                           <p>Website Visit</p>
                        </div>
                        <div class="icon">
                           <i class="ion ion-ios-albums"></i>
                        </div>
                        <?php if($users_visit == '0')
                        {?>
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                      <?php }
                      else
                        { ?> <a href="{{route('users_visit')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> <?php } ?>

                     </div>
                  </div>
               </div>
            </div>
         </section>
    	          </div>
            </div>
        </div>
    </div>
   
@endsection