<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;

class OAuthController extends Controller
{
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }
    
    public function handleProviderCallback($provider)
    {
        try {
            $user = Socialite::driver($provider)->user();
            
            // Aquí iría la lógica para crear/actualizar usuario
            return redirect('/dashboard')->with('success', 'Autenticación exitosa');
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Error en autenticación: ' . $e->getMessage());
        }
    }
}
