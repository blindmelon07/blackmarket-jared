<?php

use App\Models\Product;
use App\Models\User;
use App\Http\Controllers\SellerController;
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
    $categories = \App\Models\Category::all();
    return view('welcome', compact('products', 'categories'));
});
Route::get('/contact/{sellerId}', function ($sellerId) {
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

    // Redirect directly to Chatify with seller ID
    return redirect("/chatify/{$seller->id}");
})->name('contact.seller');
Route::middleware('auth')->group(function () {
    Route::get('/become-seller', [SellerController::class, 'show'])->name('become.seller');
    Route::post('/become-seller', [SellerController::class, 'apply'])->name('become.seller.apply');
});