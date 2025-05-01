<?php

use App\Models\Product;
use App\Models\User;
use Filament\Models\Contracts\FilamentUser;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Livewire\Livewire;

/* NOTE: Do Not Remove
/ Livewire asset handling if using sub folder in domain
*/

Livewire::setUpdateRoute(function ($handle) {
    return Route::post(config('app.asset_prefix') . '/livewire/update', $handle);
});

Livewire::setScriptRoute(function ($handle) {
    return Route::get(config('app.asset_prefix') . '/livewire/livewire.js', $handle);
});
/*
/ END
*/
Route::get('/', function () {
    $products = Product::with('category')->latest()->get();
    return view('welcome', compact('products'));
});
Route::get('/admin/{sellerId}', function ($sellerId) {
    // If not authenticated, store the redirect and send to login
    if (!Auth::check()) {
        Session::put('redirect_after_login', route('contact.seller', ['sellerId' => $sellerId]));
        return redirect()->route('filament.admin.auth.login');
    }

    // Check if the target user exists and has the 'seller' role
    $seller = User::where('id', $sellerId)->role('seller')->first();

    if (! $seller) {
        abort(404, 'Seller not found');
    }

    // Check if current user can access Filament (optional with Shield)
    if (! Filament::auth()->user()->canAccessFilament()) {
        abort(403, 'Unauthorized');
    }

    // Redirect to Chatify in Filament admin with seller ID
    return redirect("/admin/chatify?user={$seller->id}");
})->name('contact.seller');
