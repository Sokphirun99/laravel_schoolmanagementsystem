<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PortalLoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/portal/dashboard';

    public function __construct()
    {
        $this->middleware('guest.portal')->except('logout');
    }

    public function showLoginForm()
    {
        return view('portal.auth.login');
    }

    protected function guard()
    {
        return Auth::guard('portal');
    }

    protected function authenticated(Request $request, $user)
    {
        // Redirect based on user type
        if ($user->user_type === 'parent') {
            return redirect()->route('portal.dashboard');
        }
        return redirect()->route('portal.dashboard');
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->invalidate();
        return redirect()->route('portal.login');
    }
}
