<?php
// app/Http/Controllers/Auth/LoginController.php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class LoginController extends Controller implements HasMiddleware
{
    /**
     * Where to redirect users after login.
     */
    protected $redirectTo = '/dashboard';

    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('guest', except: ['logout']),
        ];
    }

    /**
     * Show the application's login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     */
    public function login(Request $request)
    {
        // Validate the request
        $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);

        // Attempt to log the user in
        $credentials = $request->only('phone', 'password');
        
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            // Authentication passed
            return redirect()->intended($this->redirectTo);
        }

        // Authentication failed
        return back()->withErrors([
            'phone' => 'The provided credentials do not match our records.',
        ])->onlyInput('phone');
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}