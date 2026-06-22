<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login()
    {
        $tokos = Toko::all();

        return view('auth.login', compact('tokos'));
    }

    public function authenticate(LoginRequest $request)
    {
        if (Auth::attempt($request->safe()->except('toko_id'), $request->boolean('remember'))) {
            $request->session()->regenerate();
            $request->session()->put('toko_id', (int) $request->input('toko_id'));

            return redirect()->intended('/dashboard');
        }

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
