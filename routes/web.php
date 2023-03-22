<?php

use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\PlaylistVideoController;
use App\Http\Requests\PlaylistVideoRequest;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::resource('videos', VideoController::class)
    ->only(['index', 'create', 'store'])
    ->middleware(['auth', 'verified']);

Route::get('/videos/{token}', function(string $token) {
    return VideoController::view($token);
})->name('videos.url_token');

Route::post('videos/uploadVideo', [VideoController::class, 'upload'])
    ->middleware(['auth', 'verified'])->name('videos.uploadVideo');

Route::resource('playlists', PlaylistController::class)
    ->only(['index', 'create', 'store'])
    ->middleware(['auth', 'verified']);

Route::get('/playlists/{token}', function(string $token) {
        return PlaylistController::view($token);
})->middleware(['auth', 'verified']);

Route::post('/playlists/get', function() {
    return PlaylistController::getPlaylists();
})->middleware(['auth', 'verified'])
  ->name('playlists.getPlaylists');

Route::resource('playlistVideos', PlaylistVideoController::class)
    ->only(['store', 'delete'])
    ->middleware(['auth', 'verified']);

require __DIR__.'/auth.php';
