<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function redirect(): RedirectResponse
    {
        if (!Auth::guest()) {
            return redirect()->to('/');
        }
        return Socialite::driver('vkontakte')->redirect();
    }

    public function callback(): RedirectResponse
    {
        $user = User::createOrUpdateFromSocialite(Socialite::driver('vkontakte')->user());
        Auth::login($user);
        return redirect()->to('/');
    }
}
