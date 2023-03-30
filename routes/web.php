<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\PlaylistVideoController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\RatingController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Requests\PlaylistVideoRequest;
use App\Http\Requests\PlaylistRequest;
use App\Http\Requests\CommentRequest;


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

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'updateInfo'])->name('profile.updateInfo');
    Route::post('/profile', [ProfileController::class, 'updatePicture'])->name('profile.updatePicture');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/* channels */
Route::get('channels/{id}', function($id) {
    return ChannelController::view($id);
})->middleware(['auth', 'verified'])
  ->name('channels.view');

/* videos */
Route::resource('videos', VideoController::class)
    ->only(['index', 'create', 'store'])
    ->middleware(['auth', 'verified']);

Route::get('/videos/{token}', function(string $token) {
    return VideoController::view($token);
})->name('videos.url_token');

Route::post('/videos/uploadVideo', [VideoController::class, 'upload'])
    ->middleware(['auth', 'verified'])->name('videos.uploadVideo');

/* comments */
Route::post('/comments', [CommentController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('comments.store');

Route::post('/comments/get/{urlToken}', function($urlToken) {
    return CommentController::getComments($urlToken);
})->middleware(['auth', 'verified'])
  ->name('comments.get');

/* playlists */
Route::resource('playlists', PlaylistController::class)
    ->only(['index', 'create', 'store'])
    ->middleware(['auth', 'verified']);

Route::get('/playlists/{token}', function(string $token) {
    return PlaylistController::view($token);
})->middleware(['auth', 'verified', 'viewPlaylist']);

Route::delete('/playlists/delete', function(PlaylistRequest $request) {
    return PlaylistController::destroy($request);
})->middleware(['auth', 'verified', 'editPlaylist'])
  ->name('playlists.destroy');

Route::post('/playlists/get', function() {
    return PlaylistController::getPlaylists();
})->middleware(['auth', 'verified'])
  ->name('playlists.getPlaylists');

Route::resource('playlistVideos', PlaylistVideoController::class)
    ->only(['store'])
    ->middleware(['auth', 'verified']);

Route::delete('/playlistVideos/delete', function(PlaylistVideoRequest $request) {
    return PlaylistVideoController::destroy($request);
})->middleware(['auth', 'verified', 'editPlaylist'])
  ->name('playlistVideos.destroy');

/* ratings */
Route::post('/ratings/rateVideo', function(Request $request) {
    return RatingController::store($request);
})->middleware(['auth', 'verified'])
  ->name('ratings.rateVideo');

require __DIR__.'/auth.php';
