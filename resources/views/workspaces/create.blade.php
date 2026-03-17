@extends('layouts.auth')
@section('title', 'Créer un workspace')

@section('content')

    <h1 style="font-size:20px; font-weight:600; color:#18181b; margin:0 0 4px;">Créer votre équipe</h1>
    <p style="font-size:13px; color:#888780; margin:0 0 28px;">Donnez un nom à votre workspace pour commencer</p>

    @if ($errors->any())
        <div style="background:#FEF2F2; border:1px solid #FECACA; border-radius:8px; padding:12px 16px; margin-bottom:20px; font-size:13px; color:#B91C1C;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('workspaces.store') }}">
        @csrf
        <div style="margin-bottom:24px;">
            <label style="display:block; font-size:11px; font-weight:600; color:#888780; letter-spacing:0.06em; text-transform:uppercase; margin-bottom:6px;">
                Nom du workspace
            </label>
            <input type="text" name="name" value="{{ old('name') }}" required autofocus
                   class="auth-input"
                   placeholder="Ex: Équipe Backend, Studio Dakar...">
            <p style="font-size:12px; color:#888780; margin-top:6px;">
                Vous pourrez inviter des membres après la création.
            </p>
        </div>

        <button type="submit" class="auth-btn">
            Créer le workspace
        </button>
    </form>

@endsection
