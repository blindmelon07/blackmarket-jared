<?php
// Step 1: Create app/Http/Responses/LoginResponse.php

namespace App\Http\Responses;

use Filament\Http\Responses\Auth\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Http\RedirectResponse;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = auth()->user();

        // Check if user has 'customer' role
        if ($user && $user->hasRole('Customer')) {
            $url = '/';
        } else {
            // For admin users or other roles, redirect to admin panel
            $url = filament()->getUrl();
        }

        return redirect()->intended($url);
    }
}
