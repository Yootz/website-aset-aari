<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function create(): View|RedirectResponse
    {
        if (auth()->user()?->is_admin) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'password' => ['required', 'string', 'max:255'],
        ]);

        $configuredPassword = config('admin.password');

        if (! is_string($configuredPassword) || $configuredPassword === '') {
            return back()->withErrors([
                'password' => 'Password admin belum dikonfigurasi.',
            ]);
        }

        if (! hash_equals($configuredPassword, $validated['password'])) {
            return back()->withErrors([
                'password' => 'Password admin tidak sesuai.',
            ]);
        }

        $admin = User::query()->where('email', config('admin.email'))->first() ?? new User;
        $admin->email = config('admin.email');
        $admin->name = 'Administrator';
        $admin->password = $configuredPassword;
        $admin->is_admin = true;
        $admin->save();

        Auth::login($admin);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
