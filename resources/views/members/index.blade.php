@extends('layouts.app')
@section('title', 'Membres')
@section('page-title', 'Membres de l\'équipe')

@section('topbar-actions')
    <button onclick="document.getElementById('invite-form').style.display='block'"
            style="height:32px; padding:0 16px; background:#7F77DD; border-radius:6px; color:#fff; font-size:12px; font-weight:600; border:none; cursor:pointer;">
        Inviter un membre
    </button>
@endsection

@section('content')

    {{-- Formulaire d'invitation --}}
    <div id="invite-form" style="display:none; background:#fff; border:0.5px solid #e5e5e5; border-radius:10px; padding:20px; margin-bottom:24px; max-width:480px;">
        <h3 style="font-size:14px; font-weight:600; color:#18181b; margin:0 0 16px;">Inviter par email</h3>
        <form method="POST" action="{{ route('members.invite') }}" style="display:flex; gap:10px;">
            @csrf
            <input type="email" name="email" required placeholder="collegue@exemple.com"
                   style="flex:1; height:38px; border:1px solid #e5e5e5; border-radius:8px; padding:0 12px; font-size:13px; outline:none;">
            <button type="submit"
                    style="height:38px; padding:0 18px; background:#18181b; border:none; border-radius:8px; color:#fff; font-size:13px; font-weight:600; cursor:pointer;">
                Envoyer
            </button>
        </form>
    </div>

    {{-- Liste des membres --}}
    <div style="max-width:640px;">
        <h2 style="font-size:13px; font-weight:600; color:#18181b; margin:0 0 12px;">
            {{ $members->count() }} membre{{ $members->count() > 1 ? 's' : '' }}
        </h2>
        <div style="background:#fff; border:0.5px solid #e5e5e5; border-radius:10px; overflow:hidden; margin-bottom:28px;">
            @foreach($members as $member)
                <div style="display:flex; align-items:center; justify-content:space-between; padding:12px 16px; {{ !$loop->last ? 'border-bottom:0.5px solid #f0f0f0;' : '' }}">
                    <div style="display:flex; align-items:center; gap:10px;">
                        <div style="width:32px; height:32px; border-radius:50%; background:#EEEDFE; display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:600; color:#534AB7;">
                            {{ strtoupper(substr($member->name, 0, 2)) }}
                        </div>
                        <div>
                            <div style="font-size:13px; font-weight:500; color:#18181b;">{{ $member->name }}</div>
                            <div style="font-size:12px; color:#888780;">{{ $member->email }}</div>
                        </div>
                    </div>
                    <div style="display:flex; align-items:center; gap:10px;">
                    <span style="font-size:11px; font-weight:500; padding:3px 10px; border-radius:20px;
                          {{ $member->pivot->role === 'admin' ? 'background:#EEEDFE; color:#534AB7;' : 'background:#F1EFE8; color:#5F5E5A;' }}">
                        {{ $member->pivot->role === 'admin' ? 'Admin' : 'Membre' }}
                    </span>
                        @if($member->id !== Auth::id())
                            <form method="POST" action="{{ route('members.remove', $member->id) }}">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        onclick="return confirm('Retirer ce membre ?')"
                                        style="height:28px; padding:0 12px; background:transparent; border:0.5px solid #e5e5e5; border-radius:6px; color:#888780; font-size:12px; cursor:pointer;">
                                    Retirer
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Invitations en attente --}}
        @if($invitations->count() > 0)
            <h2 style="font-size:13px; font-weight:600; color:#18181b; margin:0 0 12px;">Invitations en attente</h2>
            <div style="background:#fff; border:0.5px solid #e5e5e5; border-radius:10px; overflow:hidden;">
                @foreach($invitations as $invitation)
                    <div style="display:flex; align-items:center; justify-content:space-between; padding:12px 16px; {{ !$loop->last ? 'border-bottom:0.5px solid #f0f0f0;' : '' }}">
                        <span style="font-size:13px; color:#18181b;">{{ $invitation->email }}</span>
                        <span style="background:#FAEEDA; color:#633806; font-size:11px; font-weight:500; padding:3px 10px; border-radius:20px;">
                        Expire le {{ $invitation->expires_at->format('d/m') }}
                    </span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

@endsection
