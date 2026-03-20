<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleAuthController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirect(): RedirectResponse
    {
        $driver = Socialite::driver('google')
            ->redirectUrl(route('google.callback'));

        if (config('services.google.stateless')) {
            $driver = $driver->stateless();
        }

        return $driver->redirect();
    }

    /**
     * Obtain the user information from Google.
     */
    public function callback(): RedirectResponse
    {
        try {
            $driver = Socialite::driver('google')
                ->redirectUrl(route('google.callback'));

            if (config('services.google.stateless')) {
                $driver = $driver->stateless();
            }

            $googleUser = $driver->user();

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
                    return redirect()->route('legal.accept.form');
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
                return redirect()->route('legal.accept.form');
            }

            // User exists and has accepted legal terms - proceed with login
            Auth::login($user, true);

            return redirect()->intended(route('home', absolute: false));
        } catch (Throwable $e) {
            Log::error('Google OAuth authentication failed', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return redirect()->route('login')
                ->with('error', 'Error al autenticarse con Google. Revisa la configuración OAuth y vuelve a intentarlo.');
        }
    }
}

