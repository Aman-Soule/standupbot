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

        /* ── Overlay (mobile) ── */
        #sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.55);
            z-index: 99;
        }
        #sidebar-overlay.open { display: block; }

        /* ── Sidebar ── */
        #sidebar {
            width: 220px;
            background: #18181b;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            transition: transform 0.25s cubic-bezier(.4,0,.2,1);
        }

        /* ── Main wrapper ── */
        #main-wrapper {
            margin-left: 220px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ── Hamburger (hidden on desktop) ── */
        #hamburger {
            display: none;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            background: transparent;
            border: 0.5px solid #e5e5e5;
            border-radius: 7px;
            cursor: pointer;
            flex-shrink: 0;
        }

        /* ── Topbar title: truncate on small screens ── */
        .topbar-title {
            font-size: 15px;
            font-weight: 600;
            color: #18181b;
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* ── Date label: hide on very small screens ── */
        .topbar-date {
            font-size: 12px;
            color: #888780;
            white-space: nowrap;
        }

        /* ── MOBILE breakpoint ── */
        @media (max-width: 767px) {
            #sidebar {
                transform: translateX(-100%);
            }
            #sidebar.open {
                transform: translateX(0);
            }
            #main-wrapper {
                margin-left: 0;
            }
            #hamburger {
                display: flex;
            }
            .topbar-date {
                display: none;
            }
            /* Shrink padding on mobile */
            #page-main {
                padding: 16px !important;
            }
        }

        /* ── Tablet: sidebar always visible ── */
        @media (min-width: 768px) {
            #sidebar-overlay { display: none !important; }
        }
    </style>
</head>
<body style="margin:0; background:#f4f4f4; font-family: sans-serif;">

{{-- Overlay backdrop (mobile only) --}}
<div id="sidebar-overlay" onclick="closeSidebar()"></div>

<div style="display:flex; min-height:100vh;">

    {{-- ===================== SIDEBAR ===================== --}}
    <aside id="sidebar">

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

            {{-- Close button (mobile only) --}}
            <button onclick="closeSidebar()"
                    style="margin-left:auto; display:none; background:transparent; border:none; color:#888780; cursor:pointer; padding:4px;"
                    id="sidebar-close">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M2 2l12 12M14 2L2 14"/>
                </svg>
            </button>
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
                   onclick="closeSidebar()"
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
                <div style="min-width:0;">
                    <div style="font-size:12px; font-weight:500; color:#fff; line-height:1.3; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                        {{ Auth::user()->name }}
                    </div>
                    <div style="font-size:11px; color:#5F5E5A; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
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
    <div id="main-wrapper">

        {{-- Topbar --}}
        <header style="background:#ffffff; border-bottom:0.5px solid #e5e5e5; height:54px; display:flex; align-items:center; justify-content:space-between; padding:0 20px; gap:12px; position:sticky; top:0; z-index:50;">

            {{-- Left: hamburger + title --}}
            <div style="display:flex; align-items:center; gap:12px; min-width:0; flex:1;">
                <button id="hamburger" onclick="openSidebar()" aria-label="Ouvrir le menu">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="#18181b" stroke-width="1.8">
                        <path d="M2 4h12M2 8h12M2 12h12"/>
                    </svg>
                </button>
                <h1 class="topbar-title">@yield('page-title')</h1>
            </div>

            {{-- Right: date + actions --}}
            <div style="display:flex; align-items:center; gap:12px; flex-shrink:0;">
                <span class="topbar-date">
                    {{ now()->translatedFormat('l d F Y') }}
                </span>
                @yield('topbar-actions')
            </div>
        </header>

        {{-- Flash messages --}}
        @if(session('success'))
            <div style="background:#E1F5EE; border-bottom:0.5px solid #9FE1CB; padding:10px 20px; font-size:13px; color:#085041;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="background:#FCEBEB; border-bottom:0.5px solid #F7C1C1; padding:10px 20px; font-size:13px; color:#791F1F;">
                {{ session('error') }}
            </div>
        @endif

        @if(session('info'))
            <div style="background:#E6F1FB; border-bottom:0.5px solid #B5D4F4; padding:10px 20px; font-size:13px; color:#0C447C;">
                {{ session('info') }}
            </div>
        @endif

        {{-- Page content --}}
        <main id="page-main" style="padding:28px; flex:1;">
            @yield('content')
        </main>

    </div>
</div>

<script>
    // Show close button inside sidebar on mobile
    function openSidebar() {
        document.getElementById('sidebar').classList.add('open');
        document.getElementById('sidebar-overlay').classList.add('open');
        document.getElementById('sidebar-close').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    function closeSidebar() {
        document.getElementById('sidebar').classList.remove('open');
        document.getElementById('sidebar-overlay').classList.remove('open');
        document.getElementById('sidebar-close').style.display = 'none';
        document.body.style.overflow = '';
    }
    // Close sidebar on resize to desktop
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 768) closeSidebar();
    });
</script>

</body>
</html>
