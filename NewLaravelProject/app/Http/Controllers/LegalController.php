<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\SeoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Laravel\Socialite\Facades\Socialite;

class LegalController extends Controller
{
    /**
     * Display the legal page with Terms & Conditions and Cookies Policy.
     */
    public function index(): View
    {
        $meta = SeoService::generateMetaTags([
            'title' => 'Información Legal - Términos, Cookies y Contacto | ElAlmaDeLaFiesta',
            'description' => 'Términos y Condiciones, Política de Cookies e información de contacto de ElAlmaDeLaFiesta. Plataforma de festividades y eventos tradicionales en España.',
            'keywords' => 'términos y condiciones, política de cookies, información legal, contacto ElAlmaDeLaFiesta',
            'url' => route('legal.index'),
        ]);
        return view('legal.index', compact('meta'));
    }

    /**
     * Display the legal acceptance form.
     */
    public function acceptForm(): View
    {
        $meta = [
            'title' => 'Aceptar Términos Legales | ElAlmaDeLaFiesta',
            'description' => 'Aceptación de Términos y Condiciones y Política de Cookies.',
            'url' => route('legal.accept.form'),
            'robots' => 'noindex, nofollow',
        ];
        return view('legal.accept', compact('meta'));
    }

    /**
     * Handle the legal acceptance submission.
     */
    public function accept(Request $request): RedirectResponse
    {
        $request->validate([
            'accepted_legal' => ['required', 'accepted'],
        ]);

        // Check if this is a Google Auth flow
        if (session()->has('google_auth_pending')) {
            $googleData = session()->get('google_auth_pending');
            
            // Check if user already exists
            $user = User::where('email', $googleData['email'])->first();
            
            if ($user) {
                // Update existing user
                $user->accepted_legal = true;
                if (!$user->google_id) {
                    $user->google_id = $googleData['google_id'];
                }
                $user->save();
            } else {
                // Create new user
                $user = User::create([
                    'name' => $googleData['name'],
                    'email' => $googleData['email'],
                    'google_id' => $googleData['google_id'],
                    'password' => Hash::make(uniqid('', true)), // Random password since OAuth
                    'role' => 'visitor',
                    'email_verified_at' => now(), // Google emails are verified
                    'accepted_legal' => true,
                ]);
            }
            
            // Clear the session
            session()->forget('google_auth_pending');
            
            // Log the user in
            Auth::login($user, true);
            
            // Clear any intended URL to avoid redirect loops
            session()->forget('url.intended');
            
            return redirect()->route('home')->with('success', 'Términos legales aceptados correctamente.');
        }
        
        // Regular authenticated user updating their acceptance
        if (Auth::check()) {
            $user = Auth::user();
            
            // Refresh the user model to ensure we have the latest data
            $user->refresh();
            
            // Update accepted_legal
            $user->accepted_legal = true;
            $saved = $user->save();
            
            if (!$saved) {
                return redirect()->route('legal.accept.form')
                    ->with('error', 'Error al guardar la aceptación. Por favor, intente nuevamente.');
            }
            
            // Clear any intended URL to avoid redirect loops
            session()->forget('url.intended');
            
            return redirect()->route('home')->with('success', 'Términos legales aceptados correctamente.');
        }
        
        // Should not reach here, but redirect to login if somehow unauthenticated
        return redirect()->route('login')->with('error', 'Debe iniciar sesión para aceptar los términos legales.');
    }
}
