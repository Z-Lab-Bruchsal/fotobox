<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function login(): View
    {
        return view('settings-login');
    }

    public function authenticate(Request $request): RedirectResponse
    {
        $password = env('SETTINGS_PASSWORD', 'admin');

        if ($request->input('password') === $password) {
            session(['settings_authenticated' => true]);
            return redirect()->route('settings');
        }

        return back()->withErrors(['password' => 'Falsches Passwort.']);
    }

    public function index(): View
    {
        return view('settings');
    }

    public function logout(): RedirectResponse
    {
        session()->forget('settings_authenticated');
        return redirect()->route('settings.login');
    }
}
