<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\PlaylistVideoController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Requests\PlaylistVideoRequest;
use App\Http\Requests\PlaylistRequest;
use App\Http\Requests\VideoEditRequest;
use App\Http\Requests\VideoUpdateRequest;
use Illuminate\Support\Facades\Log;
use App\Models\Video;


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
    return redirect(route('videos.index'));
});

Route::middleware(['auth', 'verified'])->group(function() {
  /* channels */
  Route::get('channels/{id}', function(int $id) {
    return ChannelController::view($id);
  })->name('channels.view');

  Route::post('/channels/subscribe/{channelId}', function($channelId) {
    return SubscriptionController::subscribe($channelId);
  })->name('subscriptions.subscribe');

  Route::get('/users/isSubscribedTo/{channelId}', function($channelId) {
    return SubscriptionController::isSubscribed($channelId);
  })->name('subscriptions.getStatus');

  Route::get('/users/getSubscriptions', function() {
    return SubscriptionController::getSubscriptions();
  })->name('subscriptions.getSubscriptions');

/* videos */
  Route::resource('videos', VideoController::class)
    ->only(['index', 'create', 'store']);

  Route::get('/videos/{token}', function(string $token) {
    return VideoController::view($token);
  })->name('videos.view');

  Route::get('/videos/edit/{token}', function(string $token) {
    return VideoController::edit($token);
  })->name('videos.edit');

  Route::post('/videos/edit/{token}', function(string $token, VideoUpdateRequest $request) {
    $video = Video::where('url_token', $token)->get()->first();

    return VideoController::update($request, $video);
  })->middleware(['editVideo'])
  ->name('videos.update');

  Route::post('videos/updateThumbnail', function(Request $request) {
    return VideoController::updateThumbnail($request);
  })->middleware(['editVideo'])
  ->name('videos.updateThumbnail');

  Route::delete('/videos/delete/{token}', function(string $token) {
    $video = Video::where('url_token', $token)->get()->first();

    return VideoController::destroy($video);
  })->middleware(['editVideo'])
  ->name('videos.destroy');

  Route::post('/videos/uploadVideo', [VideoController::class, 'upload'])
    ->name('videos.uploadVideo');

/* comments */
  Route::post('/comments', [CommentController::class, 'store'])
    ->name('comments.store');

  Route::get('/comments/get/{token}', function($token) {
    return CommentController::getComments($token);
  })->name('comments.get');

/* playlists */
  Route::resource('playlists', PlaylistController::class)
    ->only(['index', 'create', 'store']);

  Route::get('/playlists/get', function() {
    return PlaylistController::getPlaylists();
  })->name('playlists.getPlaylists');

  Route::delete('/playlists/delete', function(PlaylistRequest $request) {
    return PlaylistController::destroy($request);
  })->middleware(['editPlaylist'])
    ->name('playlists.destroy');

  Route::get('/playlists/{token}', function(string $token) {
    return PlaylistController::view($token);
  })->middleware(['viewPlaylist']);

  Route::resource('playlistVideos', PlaylistVideoController::class)
    ->only(['store']);

  Route::delete('/playlistVideos/delete', function(PlaylistVideoRequest $request) {
    return PlaylistVideoController::destroy($request);
  })->middleware(['editPlaylist'])
    ->name('playlistVideos.destroy');

/* ratings */
  Route::post('/ratings/rateVideo', function(Request $request) {
    return RatingController::store($request);
  })->name('ratings.rateVideo');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'updateInfo'])->name('profile.updateInfo');
    Route::post('/profile', [ProfileController::class, 'updatePicture'])->name('profile.updatePicture');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


/* subscriptions */


require __DIR__.'/auth.php';
