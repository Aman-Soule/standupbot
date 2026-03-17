@extends('layouts.auth')
@section('title', 'Créer un compte')

@section('content')

    <h1 style="font-size:20px; font-weight:600; color:#18181b; margin:0 0 4px;">Créer un compte</h1>
    <p style="font-size:13px; color:#888780; margin:0 0 28px;">Commencez gratuitement, sans carte bancaire</p>

    @if ($errors->any())
        <div style="background:#FEF2F2; border:1px solid #FECACA; border-radius:8px; padding:12px 16px; margin-bottom:20px; font-size:13px; color:#B91C1C;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- Nom --}}
        <div style="margin-bottom:16px;">
            <label style="display:block; font-size:11px; font-weight:600; color:#888780; letter-spacing:0.06em; text-transform:uppercase; margin-bottom:6px;">
                Nom complet
            </label>
            <input type="text" name="name" value="{{ old('name') }}" required autofocus
                   style="width:100%; height:40px; border:1px solid #e5e5e5; border-radius:8px; padding:0 12px; font-size:14px; color:#18181b; outline:none; box-sizing:border-box;"
                   placeholder="Ali Diallo">
        </div>

        {{-- Email --}}
        <div style="margin-bottom:16px;">
            <label style="display:block; font-size:11px; font-weight:600; color:#888780; letter-spacing:0.06em; text-transform:uppercase; margin-bottom:6px;">
                Adresse email
            </label>
            <input type="email" name="email" value="{{ old('email') }}" required
                   style="width:100%; height:40px; border:1px solid #e5e5e5; border-radius:8px; padding:0 12px; font-size:14px; color:#18181b; outline:none; box-sizing:border-box;"
                   placeholder="vous@exemple.com">
        </div>

        {{-- Mot de passe --}}
        <div style="margin-bottom:16px;">
            <label style="display:block; font-size:11px; font-weight:600; color:#888780; letter-spacing:0.06em; text-transform:uppercase; margin-bottom:6px;">
                Mot de passe
            </label>
            <input type="password" name="password" required
                   style="width:100%; height:40px; border:1px solid #e5e5e5; border-radius:8px; padding:0 12px; font-size:14px; color:#18181b; outline:none; box-sizing:border-box;"
                   placeholder="Minimum 8 caractères">
        </div>

        {{-- Confirmation --}}
        <div style="margin-bottom:24px;">
            <label style="display:block; font-size:11px; font-weight:600; color:#888780; letter-spacing:0.06em; text-transform:uppercase; margin-bottom:6px;">
                Confirmer le mot de passe
            </label>
            <input type="password" name="password_confirmation" required
                   style="width:100%; height:40px; border:1px solid #e5e5e5; border-radius:8px; padding:0 12px; font-size:14px; color:#18181b; outline:none; box-sizing:border-box;"
                   placeholder="••••••••">
        </div>

        <button type="submit"
                style="width:100%; height:42px; background:#18181b; border:none; border-radius:8px; color:#fff; font-size:14px; font-weight:600; cursor:pointer;">
            Créer mon compte
        </button>
    </form>

@endsection
