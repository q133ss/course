<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->safe()->only(['email', 'password']);
        $remember = $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            return back()
                ->withErrors(['email' => __('auth.failed')], 'login')
                ->withInput($request->except('password'))
                ->with('auth_modal_open', true)
                ->with('auth_modal_tab', 'login');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('courses.index'))
            ->with('auth_success', __('Вы успешно вошли в аккаунт.'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('courses.index')
            ->with('auth_success', __('Вы вышли из аккаунта. До встречи!'));
    }
}
