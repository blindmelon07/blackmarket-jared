<?php

namespace App\Filament\Admin\Pages\Auth;

use Filament\Pages\Auth\Register as BaseRegister;
use Filament\Http\Responses\Auth\Contracts\RegistrationResponse;


class Register extends BaseRegister
{
    public function register(): ?RegistrationResponse
    {
        $response = parent::register();

        if ($response) {
            redirect('/');
        }

        return $response;
    }
}
