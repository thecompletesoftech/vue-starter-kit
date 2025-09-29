<?php

use App\Http\Controllers\ChannelKeyController;
use App\Models\ChannelKey;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('channels', function () {
    $channelKeys = ChannelKey::where('user_id', auth()->id())->get();
    return Inertia::render('channel', [
        'channel_keys' => $channelKeys,
    ]);
})->middleware(['auth', 'role:customer'])->name('channels');

Route::post('/channel-keys', [ChannelKeyController::class, 'store'])->middleware(['auth'])->name('channel.keys.store');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
