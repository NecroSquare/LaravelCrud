<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

use function Laravel\Prompts\alert;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function processLogin(Request $request)
    {
        $user = User::where('name', $request->name)
                    ->where('email', $request->email)
                    ->first();

        if (!$user) {
            return back()->with('error', 'Invalid name or email.');
        }

        // Store user in session
        session(['user' => $user]);

        return redirect()->route('dashboard'); // Redirect to dashboard
    }

    public function logout()
    {
            // Step 1: Destroy session data
        session()->flush();  

        // Step 2: Invalidate the session
        session()->invalidate(); 

        // Step 3: Regenerate CSRF token
        session()->regenerateToken();  

        // Step 4: Redirect and remove session cookie
        return redirect()->route('login')
            ->withCookie(cookie()->forget('laravel_session'))
            ->with('logout_message', 'You have been logged out successfully.');
    }
}
