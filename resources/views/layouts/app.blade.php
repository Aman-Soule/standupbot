<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} — @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="margin:0; background:#f4f4f4; font-family: sans-serif;">

<div style="display:flex; min-height:100vh;">

    {{-- ===================== SIDEBAR ===================== --}}
    <aside style="width:220px; background:#18181b; display:flex; flex-direction:column; min-height:100vh; position:fixed; top:0; left:0; z-index:100;">

        {{-- Logo --}}
        <div style="display:flex; align-items:center; gap:10px; padding:24px 20px 28px;">
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

        {{-- Section label --}}
        <div style="font-size:10px; font-weight:600; color:#5F5E5A; letter-spacing:0.08em; text-transform:uppercase; padding:0 20px; margin-bottom:6px;">
            Menu
        </div>

        {{-- Nav items --}}
        <nav style="display:flex; flex-direction:column; gap:2px; padding:0 10px;">

            @php
                $navItems = [
                    ['route' => 'dashboard',      'label' => 'Dashboard',     'icon' => 'grid'],
                    ['route' => 'standups.create', 'label' => 'Mon standup',   'icon' => 'list'],
                    ['route' => 'members.index',   'label' => 'Membres',       'icon' => 'users'],
                ];
            @endphp

            @foreach($navItems as $item)
                @php $active = request()->routeIs($item['route']); @endphp
                <a href="{{ route($item['route']) }}"
                   style="display:flex; align-items:center; gap:10px; padding:9px 12px; border-radius:7px; font-size:13px; text-decoration:none;
                          {{ $active ? 'background:#27272a; color:#fff; font-weight:500;' : 'color:#888780;' }}">

                    @if($item['icon'] === 'grid')
                        <svg width="15" height="15" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
                            <rect x="1" y="1" width="6" height="6" rx="1"/><rect x="9" y="1" width="6" height="6" rx="1"/>
                            <rect x="1" y="9" width="6" height="6" rx="1"/><rect x="9" y="9" width="6" height="6" rx="1"/>
                        </svg>
                    @elseif($item['icon'] === 'list')
                        <svg width="15" height="15" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M2 4h12M2 8h8M2 12h5"/>
                        </svg>
                    @elseif($item['icon'] === 'users')
                        <svg width="15" height="15" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
                            <circle cx="8" cy="6" r="3"/><path d="M2 14c0-3 2.7-5 6-5s6 2 6 5"/>
                        </svg>
                    @endif

                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        {{-- Divider --}}
        <div style="height:0.5px; background:#27272a; margin:20px 0;"></div>

        {{-- Workspace actif --}}
        @if(isset($workspace))
            <div style="padding:0 16px; margin-bottom:8px;">
                <div style="background:#27272a; border-radius:8px; padding:10px 12px;">
                    <div style="font-size:11px; font-weight:500; color:#5F5E5A; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:4px;">
                        Workspace
                    </div>
                    <div style="font-size:13px; font-weight:500; color:#fff; margin-bottom:2px;">
                        {{ $workspace->name }}
                    </div>
                    <div style="font-size:11px; color:#5F5E5A;">
                        {{ $workspace->members()->count() }} membres
                    </div>
                </div>
            </div>
        @endif

        {{-- Spacer --}}
        <div style="flex:1;"></div>

        {{-- User + logout --}}
        <div style="border-top:0.5px solid #27272a; padding:16px;">
            <div style="display:flex; align-items:center; gap:9px; margin-bottom:10px;">
                <div style="width:30px; height:30px; border-radius:50%; background:#534AB7; display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:500; color:#EEEDFE; flex-shrink:0;">
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </div>
                <div>
                    <div style="font-size:12px; font-weight:500; color:#fff; line-height:1.3;">
                        {{ Auth::user()->name }}
                    </div>
                    <div style="font-size:11px; color:#5F5E5A;">
                        {{ Auth::user()->email }}
                    </div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        style="width:100%; height:32px; background:transparent; border:0.5px solid #3f3f46; border-radius:6px; color:#888780; font-size:12px; cursor:pointer;">
                    Se déconnecter
                </button>
            </form>
        </div>

    </aside>

    {{-- ===================== CONTENU PRINCIPAL ===================== --}}
    <div style="margin-left:220px; flex:1; display:flex; flex-direction:column; min-height:100vh;">

        {{-- Topbar --}}
        <header style="background:#ffffff; border-bottom:0.5px solid #e5e5e5; height:54px; display:flex; align-items:center; justify-content:space-between; padding:0 28px; position:sticky; top:0; z-index:50;">
            <h1 style="font-size:15px; font-weight:600; color:#18181b; margin:0;">
                @yield('page-title')
            </h1>
            <div style="display:flex; align-items:center; gap:12px;">
                <span style="font-size:12px; color:#888780;">
                    {{ now()->translatedFormat('l d F Y') }}
                </span>
                @yield('topbar-actions')
            </div>
        </header>

        {{-- Flash messages --}}
        @if(session('success'))
            <div style="background:#E1F5EE; border-bottom:0.5px solid #9FE1CB; padding:10px 28px; font-size:13px; color:#085041;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="background:#FCEBEB; border-bottom:0.5px solid #F7C1C1; padding:10px 28px; font-size:13px; color:#791F1F;">
                {{ session('error') }}
            </div>
        @endif

        @if(session('info'))
            <div style="background:#E6F1FB; border-bottom:0.5px solid #B5D4F4; padding:10px 28px; font-size:13px; color:#0C447C;">
                {{ session('info') }}
            </div>
        @endif

        {{-- Page content --}}
        <main style="padding:28px; flex:1;">
            @yield('content')
        </main>

    </div>
</div>

</body>
</html>
