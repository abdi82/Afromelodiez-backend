<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\ArtistController;
use App\Http\Controllers\Admin\SongController;
use App\Http\Controllers\Admin\AdController;
use App\Http\Controllers\Admin\AlbumController;
use App\Http\Controllers\Admin\podcastController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\featuredPlaylistsController;
//use App\Http\Controllers\Admin\videoController;
use App\Http\Controllers\Admin\EpisodesPodcastController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\AdminPanelController;
// use auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/base_path', function () {
    return base_path();
});

Route::get('/delete_song_record_data', [SongController::class, 'delete_song_record_data']);

Route::get('/dashboard', [LoginController::class, 'home']);

Route::get('artist', [ArtistController::class, 'index']);

Route::get('/admin/register', [AdminUserController::class, 'register']);

Route::post('/Register_admin', [AdminUserController::class, 'Register_admin'])->name('Register_admin');

Route::middleware(['auth:sanctum', 'verified'])->get('/admin/dashboard', [HomeController::class, 'Home'])->name('dashboard');

Route::middleware(['auth:sanctum', 'verified'])->get('/admin/artistDashboard', function () {
    return view('admin.indexArtist');
})->name('artistDashboard'); 

Route::middleware(['auth:sanctum', 'verified'])->get('/admin/ManagerDashboard', function () {
    return view('admin.index');
})->name('dashboard');



//Route::get('/admin/artistDashboard', [AdminUserController::class, 'artist_admin']);


//Route::get('/admin/artistDashboard', [AdminUserController::class, 'artist_admin']);
Route::group(['middleware' => ['auth']], function () {
    Route::get('dashboard', 'App\Http\Controllers\Admin\AdminUserController@index')->name('betting.page');

});

Route::group(['middleware' => ['artist'],'prefix' => 'admin'], function () {
    Route::get('get_song_form', [SongController::class, 'get_song_form'])->name('get_song_form');

});
Route::group(['middleware' => ['admin'],'prefix' => 'admin'], function () {


    /*
    |--------------------------------------------------------------------------
    | Admin Dashboard
    |--------------------------------------------------------------------------
    */ 
    Route::get('admin_contact', [App\Http\Controllers\Admin\AdminPanelController::class,'admin_contact'])->name('admin_contact');

   Route::get('contact_listing', [App\Http\Controllers\Admin\AdminPanelController::class,'contact_listing'])->name('contact_listing');

     Route::get('reply-contact/{id}', [App\Http\Controllers\Admin\AdminPanelController::class,'reply'])->name('reply-contact');

     Route::post('send-reply', [App\Http\Controllers\Admin\AdminPanelController::class,'contact_store'])->name('send-reply');
    Route::post('resend', [App\Http\Controllers\Admin\AdminPanelController::class,'resend'])->name('resend');

    Route::get('get_notification', [App\Http\Controllers\Admin\AdminPanelController::class,'notification'])->name('get_notification');
    Route::get('add-notification', [App\Http\Controllers\Admin\AdminPanelController::class,'create_notification'])->name('add_notification');
    Route::post('notification', [App\Http\Controllers\Admin\AdminPanelController::class,'add_notification'])->name('notification');
    Route::get('notification_list', [App\Http\Controllers\Admin\AdminPanelController::class,'notification_listing'])->name('notification_list');
    Route::get('delete_notification/{id}', [App\Http\Controllers\Admin\AdminPanelController::class,'delete_noti'])->name('delete_notification');
    Route::get('edit_notification/{id}', [App\Http\Controllers\Admin\AdminPanelController::class,'edit'])->name('edit_notification');
    Route::get('notification_reply/{id}', [App\Http\Controllers\Admin\AdminPanelController::class,'notification_reply'])->name('notification_reply');
    Route::put('update_notification/{id}', [App\Http\Controllers\Admin\AdminPanelController::class,'update'])->name('update_notification');

     Route::get('privacy-policy', [App\Http\Controllers\Admin\AdminPanelController::class,'index'])->name('privacy-policy');
     Route::post('add-privacy-policy', [App\Http\Controllers\Admin\AdminPanelController::class,'store'])->name('add-privacy-policy');
     
   Route::get('terms_conditions', [App\Http\Controllers\Admin\AdminPanelController::class,'term_index'])->name('terms_conditions');

  Route::post('edit-terms_conditions', [App\Http\Controllers\Admin\AdminPanelController::class,'storeterm'])->name('edit-terms_conditions');

    Route::get('/artistDashboard', [AdminUserController::class, 'artist_admin']);

    Route::post('admin', 'App\Http\Controllers\Admin\AdminUserController@store')->name('admin.store');
    Route::get('videolikes', 'App\Http\Controllers\Admin\AdminUserController@get_youtube')->name('cat.you');
    Route::get('users', 'App\Http\Controllers\Admin\AdminUserController@userList')->name('user.list');
    Route::get('managers', 'App\Http\Controllers\Admin\AdminUserController@managerList')->name('manager.list');
    Route::post('admin_update/{id}', 'App\Http\Controllers\Admin\AdminUserController@admin_update')->name('admin_update');
    Route::get('bettings', 'App\Http\Controllers\Admin\AdminUserController@bettingList')->name('bet.list');
    Route::get('bettingReports', 'App\Http\Controllers\Admin\AdminUserController@reportList')->name('reportList');
    Route::get('changeStatus', 'App\Http\Controllers\Admin\AdminUserController@changeStatus');
    Route::any('user/delete/{id}', 'App\Http\Controllers\Admin\AdminUserController@delete')->name('user.del');
    Route::get('user/edit/{id}', 'App\Http\Controllers\Admin\AdminUserController@edit')->name('user.edit');
    Route::post('user/update/{id}', 'App\Http\Controllers\Admin\AdminUserController@update')->name('user.update');
    Route::any('bet/delete/{id}', 'App\Http\Controllers\Admin\AdminUserController@deleteBet')->name('bet.del');

    /*
    |--------------------------------------------------------------------------
    | Category Section
    |--------------------------------------------------------------------------
    */
    Route::get('category/create', [CategoryController::class, 'create'])->name('cat.page');
    Route::get('category/edit/{id}', [CategoryController::class, 'edit'])->name('cat.edit');
    Route::post('category/update/{id}', [CategoryController::class, 'update'])->name('cat.update');
    Route::post('category/create', [CategoryController::class, 'store'])->name('cat.store');
    Route::any('category/delete/{id}', [CategoryController::class, 'delete'])->name('cat.del');
    Route::get('categories', [CategoryController::class, 'index'])->name('cat.list');
    
    /*
    |--------------------------------------------------------------------------
    |  Banner Section
    |--------------------------------------------------------------------------
    */
     Route::post('banner_store', [BannerController::class, 'store'])->name('banner_store');
     Route::get('banner_form', [BannerController::class, 'banner_form'])->name('banner_form');
     Route::get('delete_banner_image/{id}', [BannerController::class, 'delete_banner_image'])->name('delete_banner_image');

    /*
    |--------------------------------------------------------------------------
    | Logout Section
    |--------------------------------------------------------------------------
    */

    Route::get('/logout', '\App\Http\Controllers\Admin\AdminUserController@logout')->name('clog');
    /*
    |--------------------------------------------------------------------------
    | Emoji Section
    |--------------------------------------------------------------------------
    */


     Route::get('/emojis', '\App\Http\Controllers\Admin\EmojiController@index')->name('emoji.list');
     Route::post('/emojis', '\App\Http\Controllers\Admin\EmojiController@store')->name('emoji.store');

  });

  /*
   |--------------------------------------------------------------------------
   | Artist Routes
   |--------------------------------------------------------------------------

   */

     Route::get('artistlist', [ArtistController::class, 'index'])->name('artistlist');;
     Route::get('agreement', [ArtistController::class, 'showAgreement'])->name('agreement');
     Route::get('delete_agreement/{id}', [ArtistController::class, 'delete_agreement'])->name('delete_agreement');
     Route::get('agreement_listing', [ArtistController::class, 'agreement_listing'])->name('agreement_listing');
     Route::post('add_agreement', [ArtistController::class, 'add_agreement'])->name('add_agreement');
     Route::get('monthly-listener', [SongController::class, 'monthly_listeners'])->name('monthly.listener');
     Route::get('users_visit', [AdController::class, 'users_visit_list'])->name('users_visit');
     Route::get('get_artist_form', [ArtistController::class, 'get_artist_form'])->name('get_artist_form');
     Route::post('add_artist', [ArtistController::class, 'store'])->name('add_artist');
     Route::get('edit_artist/{id}', [ArtistController::class, 'edit'])->name('edit_artist');
     Route::post('update_artist/{id}', [ArtistController::class, 'update'])->name('update_artist');
     Route::get('delete_artist/{id}', [ArtistController::class, 'delete'])->name('delete_artist');
     Route::get('/search-artist', [ArtistController::class, 'search_artist'])->name('search_artist');
      Route::get('/search-listing-artist', [ArtistController::class, 'indexSearch_artist'])->name('search_list_artist');
      Route::get('MostlistenedArtist', [ArtistController::class, 'MostlistenedArtist'])->name('MostlistenedArtist');
      
     /*
   |--------------------------------------------------------------------------
   | language Routes
   |--------------------------------------------------------------------------

   */
     Route::get('language', [LanguageController::class, 'index'])->name('language');
     Route::get('language_form', [LanguageController::class, 'language_form'])->name('language_form');
     Route::post('add_language', [LanguageController::class, 'store'])->name('add_language');
     Route::get('edit_language/{id}', [LanguageController::class, 'edit'])->name('edit_language');
     Route::post('update_language/{id}', [LanguageController::class, 'update'])->name('update_language');
     Route::get('delete_language/{id}', [LanguageController::class, 'delete'])->name('delete_language');
     /*
   |--------------------------------------------------------------------------
   | Song Routes
   |--------------------------------------------------------------------------

   */
Route::get('get_song_form', [SongController::class, 'get_song_form'])->name('get_song_form');
      Route::get('/ajax-autocomplete-search', [SongController::class,'selectSearch']);
      Route::get('/ajax-autocomplete-search-language', [SongController::class,'langSearch']);
      Route::get('/ajax-autocomplete-search-cat', [SongController::class,'catSearch']);
      Route::get('/ajax-autocomplete-search-album', [SongController::class,'albumSearch']);
      Route::get('/ajax-autocomplete-search-feature-artists', [SongController::class,'featureSearch']);
      Route::get('song_form', [SongController::class, 'song_form'])->name('song_form');
      Route::get('song', [SongController::class, 'index'])->name('song');

      Route::post('song/create', 'App\Http\Controllers\Admin\SongController@store')->name('song.store');
      Route::get('edit_song/{id}', [SongController::class, 'edit'])->name('edit_song');
      Route::post('song_status/', [SongController::class, 'song_status']);
      Route::post('update_song/{id}', [SongController::class, 'update'])->name('update_song');
      Route::get('delete_song/{id}', [SongController::class, 'delete'])->name('delete_song');
      Route::get('get_music/{name}', [SongController::class, 'get_music'])->name('get_music');
      Route::get('get_multiple_song_form', [SongController::class, 'get_multiple_song_form'])->name('get_multiple_song_form');
      Route::post('multiple_song_store', [SongController::class, 'multiple_song_store'])->name('multiple_song_store');
      Route::get('/search-song', [SongController::class, 'search_song'])->name('search_song');
      Route::get('/search-listing', [SongController::class, 'indexSearch'])->name('search_list');
      // artist 
      Route::post('store_artist', [SongController::class, 'store_artist'])->name('store_artist');
      Route::post('multiple_song_store_artist/{userid}', [SongController::class, 'multiple_song_store_artist'])->name('multiple_song_store_artist');
      Route::get('artistsong', [SongController::class, 'index_artist'])->name('song_artist_index');
      Route::get('artist_song_form', [SongController::class, 'artist_song_form'])->name('artist_song_form');
      Route::get('artist_multiple_song_form', [SongController::class, 'artist_multiple_song_form'])->name('artist_multiple_song_form');
      Route::get('/indexSearchArtist', [SongController::class,'indexSearchArtist'])->name('indexSearchArtist');
      Route::get('/get_new_admin_form', [SongController::class,'get_new_admin_form'])->name('get_new_admin_form');
      Route::get('/search-song-artist', [SongController::class, 'search_song_artist'])->name('search_song_artist');
      Route::get('edit_song_artist/{id}', [SongController::class, 'edit_song_artist'])->name('edit_song_artist');
      Route::post('update_song_artist/{id}', [SongController::class, 'update_song_artist'])->name('update_song_artist');
      Route::get('delete_song_artist/{id}', [SongController::class, 'delete_song_artist'])->name('delete_song_artist');
      Route::get('mostlistenedSong', [SongController::class, 'mostlistenedSong'])->name('mostlistenedSong');
      Route::get('CurrentListenersUsers', [SongController::class, 'CurrentListenersUsers'])->name('CurrentListenersUsers');
      
     /*
   |--------------------------------------------------------------------------
   | Video Routes
   |--------------------------------------------------------------------------

   */
//      Route::get('/ajax-autocomplete-search-video', [videoController::class,'selectSearch']);
//      Route::get('/ajax-autocomplete-search-language-video', [videoController::class,'langSearch']);
//      Route::get('/ajax-autocomplete-search-cat-video', [videoController::class,'catSearch']);
//      Route::get('/ajax-autocomplete-search-album-video', [videoController::class,'albumSearch']);
//      Route::get('video_form', [videoController::class, 'video_form'])->name('video_form');
//      Route::get('video', [videoController::class, 'index'])->name('video');

//      Route::post('video/create', 'App\Http\Controllers\Admin\videoController@store')->name('video.store');
//      Route::get('get_video_form', [videoController::class, 'get_video_form'])->name('get_video_form');
//      Route::get('edit_video/{id}', [videoController::class, 'edit'])->name('edit_video');
//      Route::post('update_video/{id}', [videoController::class, 'update'])->name('update_video');
//      Route::get('delete_video/{id}', [videoController::class, 'delete'])->name('delete_video');
//      Route::get('get_video/{name}', [videoController::class, 'get_video'])->name('get_video');
//      Route::get('get_multiple_video_form', [videoController::class, 'get_multiple_video_form'])->name('get_multiple_video_form');
//      Route::post('multiple_video_store', [videoController::class, 'multiple_video_store'])->name('multiple_video_store');
//      Route::get('/search-video', [videoController::class, 'search_video'])->name('search_video');
//      Route::get('/search-listing-video', [videoController::class, 'indexSearch'])->name('search_list_video');
        /*
   |--------------------------------------------------------------------------
   | Advertisement Routes
   |--------------------------------------------------------------------------

   */
     Route::get('adlist', [AdController::class, 'index'])->name('adlist');
     Route::get('get_ad_form', [AdController::class, 'get_ad_form'])->name('get_ad_form');
     Route::post('add_ad', [AdController::class, 'store'])->name('add_ad');
     Route::get('edit_ad/{id}', [AdController::class, 'edit'])->name('edit_ad');
     Route::post('update_ad/{id}', [AdController::class, 'update'])->name('update_ad');
     Route::get('delete_ad/{id}', [AdController::class, 'delete'])->name('delete_ad');
    /*

   |--------------------------------------------------------------------------
   | Album Routes
   |--------------------------------------------------------------------------

   */
     Route::get('albumlist', [AlbumController::class, 'index'])->name('albumlist');
     Route::get('get_album_form', [AlbumController::class, 'get_album_form'])->name('get_album_form');
     Route::post('add_album', [AlbumController::class, 'store'])->name('add_album');
     Route::get('edit_album/{id}', [AlbumController::class, 'edit'])->name('edit_album');
     Route::post('update_album/{id}', [AlbumController::class, 'update'])->name('update_album');
     Route::get('delete_album/{id}', [AlbumController::class, 'delete'])->name('delete_album');
     //artist album page routes
     Route::get('albumlist_artist', [AlbumController::class, 'index_artist'])->name('albumlist_artist');
     Route::get('get_album_form_artist', [AlbumController::class, 'get_album_form_artist'])->name('get_album_form_artist');
     Route::post('add_album_artist', [AlbumController::class, 'store_artist'])->name('add_album_artist');
     Route::get('edit_album_artist/{id}', [AlbumController::class, 'edit_artist'])->name('edit_album_artist');
     Route::post('update_album_artist/{id}', [AlbumController::class, 'update_artist'])->name('update_album_artist');
     Route::get('delete_album_artist/{id}', [AlbumController::class, 'delete_artist'])->name('delete_album_artist');
     /*
        |--------------------------------------------------------------------------
   | Featured Routes
   |--------------------------------------------------------------------------

   */
     Route::get('featuredlist', [featuredPlaylistsController::class, 'index'])->name('featuredlist');
     Route::get('get_featured_form', [featuredPlaylistsController::class, 'get_featured_form'])->name('get_featured_form');
     Route::post('add_featured', [featuredPlaylistsController::class, 'store'])->name('add_featured');
     Route::get('edit_featured/{id}', [featuredPlaylistsController::class, 'edit'])->name('edit_featured');
     Route::post('update_featured', [featuredPlaylistsController::class, 'update'])->name('update_featured');
     Route::get('delete_featured/{id}', [featuredPlaylistsController::class, 'delete'])->name('delete_featured');
     Route::get('delete_episode_song/{id}/{fid}', [featuredPlaylistsController::class, 'delete_episode_song'])->name('delete_episode_song');

         /*

   |--------------------------------------------------------------------------
   | Podcast Routes
   |--------------------------------------------------------------------------

   */
    Route::post('podcast/create', [podcastController::class, 'store'])->name('podcast.store');
    Route::get('get_podcast_create_form', [podcastController::class, 'get_podcast_create_form'])->name('get_podcast_create_form');
    Route::get('podcastlist', [podcastController::class, 'index'])->name('podcastlist');
   Route::get('delete_podcast/{id}', [podcastController::class, 'delete'])->name('delete_podcast');
   Route::get('edit_podcast/{id}', [podcastController::class, 'edit'])->name('edit_podcast');
   Route::post('update_podcast/{id}', [podcastController::class, 'update'])->name('update_podcast');
   Route::get('add_multiple_episodes/{id}', [podcastController::class, 'add_multiple_episodes'])->name('add_multiple_episodes');
   Route::get('delete_episode/{id}', [EpisodesPodcastController::class, 'delete'])->name('delete_episode');
   Route::get('episodeslist', [EpisodesPodcastController::class, 'index'])->name('episodeslist');
   Route::get('edit_episode/{id}', [EpisodesPodcastController::class, 'edit'])->name('edit_episode');
   Route::post('update_episode/{id}', [EpisodesPodcastController::class, 'update'])->name('update_episode');
   Route::get('get_episode_form', [EpisodesPodcastController::class, 'get_episode_form'])->name('get_episode_form');
   Route::get('get_multiple_episodes_form', [EpisodesPodcastController::class, 'get_multiple_episodes_form'])->name('get_multiple_episodes_form');
   Route::post('multiple_episodes_store', [EpisodesPodcastController::class, 'multiple_episodes_store'])->name('multiple_episodes_store');
   Route::get('/ajax-autocomplete-search-podcast', [EpisodesPodcastController::class,'selectSearch']);
   Route::post('episode/create', [EpisodesPodcastController::class, 'store'])->name('episode.store');
    /* artist podcast routes  */
    
    Route::post('podcast/create_artist', [podcastController::class, 'store_artist'])->name('podcast.store_artist');
    Route::get('get_podcast_create_form_artist', [podcastController::class, 'get_podcast_create_form_artist'])->name('get_podcast_create_form_artist');
    Route::get('podcastlist_artist', [podcastController::class, 'index_artist'])->name('podcastlist_artist');
   Route::get('delete_podcast_artist/{id}', [podcastController::class, 'delete_artist'])->name('delete_podcast_artist');
   Route::get('edit_podcast_artist/{id}', [podcastController::class, 'edit_artist'])->name('edit_podcast_artist');
   Route::post('update_podcast_artist/{id}', [podcastController::class, 'update_artist'])->name('update_podcast_artist');
   Route::get('add_multiple_episodes_artist/{id}', [podcastController::class, 'add_multiple_episodes_artist'])->name('add_multiple_episodes_artist');
   Route::get('delete_episode_artist/{id}', [EpisodesPodcastController::class, 'delete_artist'])->name('delete_episode_artist');
   Route::get('episodeslist_artist', [EpisodesPodcastController::class, 'index_artist'])->name('episodeslist_artist');
   Route::get('edit_episode_artist/{id}', [EpisodesPodcastController::class, 'edit_artist'])->name('edit_episode_artist');
   Route::post('update_episode_artist/{id}', [EpisodesPodcastController::class, 'update_artist'])->name('update_episode_artist');
   Route::get('get_episode_form_artist', [EpisodesPodcastController::class, 'get_episode_form_artist'])->name('get_episode_form_artist');
   Route::get('get_multiple_episodes_form_artist', [EpisodesPodcastController::class, 'get_multiple_episodes_form_artist'])->name('get_multiple_episodes_form_artist');
   Route::post('multiple_episodes_store_artist', [EpisodesPodcastController::class, 'multiple_episodes_store_artist'])->name('multiple_episodes_store_artist');
   Route::get('/ajax-autocomplete-search-podcast_artist', [EpisodesPodcastController::class,'selectSearch_artist']);
   Route::post('episode/create_artist', [EpisodesPodcastController::class, 'store_artist'])->name('episode.store_artist');
   Route::get('PodcastCurrentListeners', [podcastController::class, 'PodcastCurrentListeners'])->name('PodcastCurrentListeners');

    /* End podacst routes 
    |--------------------------------------------------------------------------
    | Notification Routes
    |--------------------------------------------------------------------------
    */

    Route::any('testnoti', 'App\Http\Controllers\Admin\AdminUserController@testNotify');
    Route::any('sendnoti', 'App\Http\Controllers\Admin\AdminUserController@notifyUser');
    Route::any('sendPushNotification', 'App\Http\Controllers\Admin\AdminUserController@sendPushNotification');
    Route::any('sendNotification', 'App\Http\Controllers\Admin\AdminUserController@sendNotification');
   