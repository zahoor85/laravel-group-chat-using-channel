<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\ChannelPermissionController;
use App\Models\Channel;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/start-chat/{channelId}', [App\Http\Controllers\HomeController::class, 'start'])->name('start-chat');
Route::get('/channel-permissions/{channelId}', [ChannelPermissionController::class, 'getUserPermissions'])->name('channel-permissions.get');

Route::get('chat', [App\Http\Controllers\HomeController::class, 'chat'])->name('chat');
Route::get('chat-jquery', [App\Http\Controllers\HomeController::class, 'chatJquery'])->name('chatJquery');

// Group routes
Route::get('/groups', [GroupController::class, 'index'])->name('groups.index');
Route::get('/groups/create', [GroupController::class, 'create'])->name('groups.create');
Route::post('/groups', [GroupController::class, 'store'])->name('groups.store');
Route::get('/groups/{group}/edit', [GroupController::class, 'edit'])->name('groups.edit');
Route::put('/groups/{group}', [GroupController::class, 'update'])->name('groups.update');
Route::delete('/groups/{group}', [GroupController::class, 'destroy'])->name('groups.destroy');
Route::get('/groups/{group}/assign-users', [GroupController::class, 'assignUsersForm'])->name('groups.assignUsers');
Route::post('/groups/{group}/assign-users', [GroupController::class, 'assignUsers'])->name('groups.assignUsers.post');

// Channels
Route::get('/channels/create', [ChannelController::class, 'create'])->name('channels.create');

// Permissions
Route::get('/channels/{channel}/permissions', [ChannelPermissionController::class, 'index'])->name('channels.permissions');
Route::put('/permissions/{channel}', [ChannelPermissionController::class, 'update'])->name('permissions.update');


Route::get('/channels/create/{group}', [ChannelController::class, 'create'])->name('channels.create.withGroup');
Route::get('/channels/{group?}', [ChannelController::class, 'index'])->name('channels.index');
Route::post('/channels', [ChannelController::class, 'store'])->name('channels.store');
Route::get('/channels/{channel}/edit', [ChannelController::class, 'edit'])->name('channels.edit');
Route::put('/channels/{channel}', [ChannelController::class, 'update'])->name('channels.update');
Route::delete('/channels/{channel}', [ChannelController::class, 'destroy'])->name('channels.destroy');




Route::get('messages/{channelId}', [App\Http\Controllers\HomeController::class, 'messages'])->name('messages');

Route::post('messages', [App\Http\Controllers\HomeController::class, 'messageStore'])->name('messageStore');
