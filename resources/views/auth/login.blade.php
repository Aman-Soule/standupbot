@extends('layouts.auth')
@section('title', 'Connexion')

@section('content')

    <h1 style="font-size:20px; font-weight:600; color:#18181b; margin:0 0 4px;">Bon retour</h1>
    <p style="font-size:13px; color:#888780; margin:0 0 28px;">Connectez-vous à votre espace</p>

    @if ($errors->any())
        <div style="background:#FEF2F2; border:1px solid #FECACA; border-radius:8px; padding:12px 16px; margin-bottom:20px; font-size:13px; color:#B91C1C;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Email --}}
        <div style="margin-bottom:16px;">
            <label style="display:block; font-size:11px; font-weight:600; color:#888780; letter-spacing:0.06em; text-transform:uppercase; margin-bottom:6px;">
                Adresse email
            </label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                   style="width:100%; height:40px; border:1px solid #e5e5e5; border-radius:8px; padding:0 12px; font-size:14px; color:#18181b; outline:none; box-sizing:border-box;"
                   placeholder="vous@exemple.com">
        </div>

        {{-- Mot de passe --}}
        <div style="margin-bottom:8px;">
            <label style="display:block; font-size:11px; font-weight:600; color:#888780; letter-spacing:0.06em; text-transform:uppercase; margin-bottom:6px;">
                Mot de passe
            </label>
            <input type="password" name="password" required
                   style="width:100%; height:40px; border:1px solid #e5e5e5; border-radius:8px; padding:0 12px; font-size:14px; color:#18181b; outline:none; box-sizing:border-box;"
                   placeholder="••••••••">
        </div>

        {{-- Mot de passe oublié --}}
        @if (Route::has('password.request'))
            <div style="text-align:right; margin-bottom:24px;">
                <a href="{{ route('password.request') }}" style="font-size:12px; color:#7F77DD; text-decoration:none;">
                    Mot de passe oublié ?
                </a>
            </div>
        @endif

        {{-- Remember me --}}
        <div style="display:flex; align-items:center; gap:8px; margin-bottom:20px;">
            <input type="checkbox" name="remember" id="remember" style="accent-color:#7F77DD;">
            <label for="remember" style="font-size:13px; color:#888780;">Se souvenir de moi</label>
        </div>

        <button type="submit"
                style="width:100%; height:42px; background:#18181b; border:none; border-radius:8px; color:#fff; font-size:14px; font-weight:600; cursor:pointer;">
            Se connecter
        </button>
    </form>

@endsection
