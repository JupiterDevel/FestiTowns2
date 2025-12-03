<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     */
    public function callback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Check if user already exists
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // User exists, check if they have a Google ID stored
                if (!$user->google_id) {
                    $user->google_id = $googleUser->getId();
                    $user->save();
                }
                
                // Check if user has accepted legal terms
                if (!$user->accepted_legal) {
                    // Store Google user data in session for later use
                    session()->put('google_auth_pending', [
                        'email' => $googleUser->getEmail(),
                        'name' => $googleUser->getName(),
                        'google_id' => $googleUser->getId(),
                    ]);
                    
                    // Log the user in temporarily so middleware can identify them
                    Auth::login($user, true);
                    
                    // Redirect to legal acceptance page
                    return redirect()->route('legal.accept');
                }
            } else {
                // New user - store Google user data in session
                // Don't create user yet, require legal acceptance first
                session()->put('google_auth_pending', [
                    'email' => $googleUser->getEmail(),
                    'name' => $googleUser->getName(),
                    'google_id' => $googleUser->getId(),
                ]);
                
                // Redirect to legal acceptance page
                return redirect()->route('legal.accept');
            }

            // User exists and has accepted legal terms - proceed with login
            Auth::login($user, true);

            return redirect()->intended(route('home', absolute: false));
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Error al autenticarse con Google: ' . $e->getMessage());
        }
    }
}

