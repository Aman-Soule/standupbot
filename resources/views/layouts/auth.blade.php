<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} — @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; }

        .auth-wrapper {
            display: flex;
            min-height: 100vh;
            align-items: center;
            justify-content: center;
            background: #f4f4f4;
            padding: 20px;
        }

        .auth-card {
            display: flex;
            width: 100%;
            max-width: 900px;
            min-height: 560px;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 32px rgba(0,0,0,0.10);
        }

        .auth-left {
            width: 42%;
            background: #18181b;
            padding: 44px 40px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .auth-right {
            flex: 1;
            background: #ffffff;
            padding: 44px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .auth-input {
            width: 100%;
            height: 40px;
            border: 1px solid #e5e5e5;
            border-radius: 8px;
            padding: 0 12px;
            font-size: 14px;
            color: #18181b;
            outline: none;
            transition: border-color 0.15s;
        }

        .auth-input:focus {
            border-color: #7F77DD;
            box-shadow: 0 0 0 3px #EEEDFE;
        }

        .auth-btn {
            width: 100%;
            height: 42px;
            background: #18181b;
            border: none;
            border-radius: 8px;
            color: #fff;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: opacity 0.15s;
        }

        .auth-btn:hover { opacity: 0.85; }

        .auth-tabs {
            display: flex;
            border: 1px solid #e5e5e5;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 32px;
        }

        .auth-tab {
            flex: 1;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            text-decoration: none;
            color: #888780;
            transition: background 0.15s;
        }

        .auth-tab.active {
            background: #18181b;
            color: #fff;
            font-weight: 600;
        }

        /* Masquer le panneau gauche sur mobile */
        @media (max-width: 640px) {
            .auth-wrapper { padding: 0; align-items: stretch; }
            .auth-card { border-radius: 0; min-height: 100vh; flex-direction: column; box-shadow: none; }
            .auth-left { display: none; }
            .auth-right { padding: 36px 24px; justify-content: flex-start; }
            .auth-right-header { display: flex; align-items: center; gap: 10px; margin-bottom: 32px; }
        }

        @media (min-width: 641px) and (max-width: 820px) {
            .auth-left { width: 38%; padding: 32px 28px; }
            .auth-right { padding: 32px 28px; }
            .auth-left-features { display: none; }
        }
    </style>
</head>
<body style="margin:0; font-family: sans-serif;">

<div class="auth-wrapper">
    <div class="auth-card">

        {{-- Panneau gauche (masqué sur mobile) --}}
        <div class="auth-left">
            <div style="display:flex; align-items:center; gap:10px;">
                <div style="width:30px; height:30px; background:#7F77DD; border-radius:7px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg width="16" height="16" viewBox="0 0 18 18" fill="none">
                        <rect x="2" y="2" width="6" height="6" rx="1.5" fill="white" opacity="0.9"/>
                        <rect x="10" y="2" width="6" height="6" rx="1.5" fill="white" opacity="0.5"/>
                        <rect x="2" y="10" width="6" height="6" rx="1.5" fill="white" opacity="0.5"/>
                        <rect x="10" y="10" width="6" height="6" rx="1.5" fill="white" opacity="0.2"/>
                    </svg>
                </div>
                <span style="font-size:15px; font-weight:600; color:#fff;">StandupBot</span>
            </div>

            <div class="auth-left-features">
                <h2 style="font-size:22px; font-weight:600; color:#fff; line-height:1.4; margin:0 0 12px;">
                    Daily standups,<br>done differently.
                </h2>
                <p style="font-size:13px; color:#888780; line-height:1.7; margin:0 0 24px;">
                    Synchronisez votre équipe en quelques secondes, même en remote.
                </p>
                <div style="display:flex; flex-direction:column; gap:10px;">
                    @foreach(['Questions configurables par équipe', 'Résumé IA chaque matin', 'Rappels automatiques par email'] as $feature)
                        <div style="display:flex; align-items:center; gap:10px;">
                            <div style="width:6px; height:6px; border-radius:50%; background:#7F77DD; flex-shrink:0;"></div>
                            <span style="font-size:13px; color:#B4B2A9;">{{ $feature }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <span style="font-size:12px; color:#5F5E5A;">© {{ date('Y') }} StandupBot</span>
        </div>

        {{-- Panneau droit --}}
        <div class="auth-right">

            {{-- Logo visible uniquement sur mobile --}}
            <div class="auth-right-header" style="display:none;">
                <div style="width:30px; height:30px; background:#7F77DD; border-radius:7px; display:flex; align-items:center; justify-content:center;">
                    <svg width="16" height="16" viewBox="0 0 18 18" fill="none">
                        <rect x="2" y="2" width="6" height="6" rx="1.5" fill="white" opacity="0.9"/>
                        <rect x="10" y="2" width="6" height="6" rx="1.5" fill="white" opacity="0.5"/>
                        <rect x="2" y="10" width="6" height="6" rx="1.5" fill="white" opacity="0.5"/>
                        <rect x="10" y="10" width="6" height="6" rx="1.5" fill="white" opacity="0.2"/>
                    </svg>
                </div>
                <span style="font-size:15px; font-weight:600; color:#18181b;">StandupBot</span>
            </div>

            {{-- Tabs --}}
            <div class="auth-tabs">
                <a href="{{ route('login') }}"
                   class="auth-tab {{ request()->routeIs('login') ? 'active' : '' }}">
                    Connexion
                </a>
                <a href="{{ route('register') }}"
                   class="auth-tab {{ request()->routeIs('register') ? 'active' : '' }}">
                    Créer un compte
                </a>
            </div>

            @yield('content')
        </div>
    </div>
</div>

</body>
</html>
