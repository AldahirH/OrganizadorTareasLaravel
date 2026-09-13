<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller{
    public function showRegister():View{
        return view('auth.register');
    } 

    public function register(RegisterRequest $request):RedirectResponse{
        $user = User::create($request->validated());
        Auth::login($user);
        $request->session()->regenerate();
        return redirect()->route('dashboard');
    }

    public function showLogin(): View{
        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse{
        if(!Auth::attempt($request->validated())){
            throw ValidationException::withMessages([
                'email' => 'El correo o la contrasena son incorrectos.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request):RedirectResponse{
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('status', 'Has cerrado tu sesion correctamente.'); 
    }
}


