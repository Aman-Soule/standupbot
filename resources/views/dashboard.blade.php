@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Vue d\'ensemble')

@section('topbar-actions')
    @if(!$userHasSubmitted)
        <a href="{{ route('standups.create') }}"
           style="height:32px; padding:0 16px; background:#7F77DD; border-radius:6px; color:#fff; font-size:12px; font-weight:600; text-decoration:none; display:flex; align-items:center;">
            Soumettre mon standup
        </a>
    @else
        <span style="background:#E1F5EE; color:#0F6E56; font-size:12px; font-weight:500; padding:4px 12px; border-radius:20px;">
            Standup soumis
        </span>
    @endif
@endsection

@section('content')

    {{-- Stat cards --}}
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(160px, 1fr)); gap:12px; margin-bottom:28px;">

        <div style="background:#fff; border:0.5px solid #e5e5e5; border-radius:10px; padding:16px;">
            <div style="font-size:11px; font-weight:600; color:#888780; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:8px;">Membres</div>
            <div style="font-size:26px; font-weight:600; color:#18181b;">{{ $members->count() }}</div>
            <div style="font-size:12px; color:#888780; margin-top:2px;">dans ce workspace</div>
        </div>

        <div style="background:#fff; border:0.5px solid #e5e5e5; border-radius:10px; padding:16px;">
            <div style="font-size:11px; font-weight:600; color:#888780; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:8px;">Soumis aujourd'hui</div>
            <div style="font-size:26px; font-weight:600; color:#18181b;">{{ $todayStandups->count() }} / {{ $members->count() }}</div>
            <div style="font-size:12px; color:#0F6E56; margin-top:2px;">
                {{ $members->count() > 0 ? round(($todayStandups->count() / $members->count()) * 100) : 0 }}% de l'équipe
            </div>
        </div>

        <div style="background:#fff; border:0.5px solid #e5e5e5; border-radius:10px; padding:16px;">
            <div style="font-size:11px; font-weight:600; color:#888780; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:8px;">Streak équipe</div>
            <div style="font-size:26px; font-weight:600; color:#18181b;">{{ $streak }}j</div>
            <div style="font-size:12px; color:#888780; margin-top:2px;">jours consécutifs</div>
        </div>

        <div style="background:#fff; border:0.5px solid #e5e5e5; border-radius:10px; padding:16px;">
            <div style="font-size:11px; font-weight:600; color:#888780; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:8px;">Ce mois</div>
            <div style="font-size:26px; font-weight:600; color:#18181b;">{{ $monthStandupsCount }}</div>
            <div style="font-size:12px; color:#888780; margin-top:2px;">standups soumis</div>
        </div>

    </div>

    {{-- Deux colonnes --}}
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">

        {{-- Standups du jour --}}
        <div>
            <h2 style="font-size:13px; font-weight:600; color:#18181b; margin:0 0 12px;">Standups du jour</h2>
            <div style="background:#fff; border:0.5px solid #e5e5e5; border-radius:10px; overflow:hidden;">
                @forelse($todayStandups as $standup)
                    <div style="padding:14px 16px; {{ !$loop->last ? 'border-bottom:0.5px solid #f0f0f0;' : '' }}">
                        <div style="display:flex; align-items:center; gap:10px; margin-bottom:8px;">
                            <div style="width:28px; height:28px; border-radius:50%; background:#EEEDFE; display:flex; align-items:center; justify-content:center; font-size:10px; font-weight:600; color:#534AB7; flex-shrink:0;">
                                {{ strtoupper(substr($standup->user->name, 0, 2)) }}
                            </div>
                            <div>
                                <span style="font-size:13px; font-weight:500; color:#18181b;">{{ $standup->user->name }}</span>
                                <span style="font-size:11px; color:#888780; margin-left:8px;">{{ $standup->created_at->format('H\hi') }}</span>
                            </div>
                        </div>
                        @foreach($standup->answers as $answer)
                            <div style="margin-bottom:6px;">
                                <div style="font-size:11px; font-weight:600; color:#888780; margin-bottom:2px;">{{ $answer->question->question }}</div>
                                <div style="font-size:13px; color:#3d3d3a; line-height:1.5;">{{ $answer->answer }}</div>
                            </div>
                        @endforeach
                    </div>
                @empty
                    <div style="padding:32px 16px; text-align:center; font-size:13px; color:#888780;">
                        Aucun standup soumis aujourd'hui.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Statut de l'équipe --}}
        <div>
            <h2 style="font-size:13px; font-weight:600; color:#18181b; margin:0 0 12px;">Statut de l'équipe </h2>
            <div style="background:#fff; border:0.5px solid #e5e5e5; border-radius:10px; overflow:hidden;">
                @foreach($members as $member)
                    @php $submitted = $todayStandups->pluck('user_id')->contains($member->id); @endphp
                    <div style="display:flex; align-items:center; justify-content:space-between; padding:12px 16px; {{ !$loop->last ? 'border-bottom:0.5px solid #f0f0f0;' : '' }}">
                        <div style="display:flex; align-items:center; gap:9px;">
                            <div style="width:26px; height:26px; border-radius:50%; background:{{ $submitted ? '#E1F5EE' : '#F1EFE8' }}; display:flex; align-items:center; justify-content:center; font-size:10px; font-weight:600; color:{{ $submitted ? '#085041' : '#5F5E5A' }}; flex-shrink:0;">
                                {{ strtoupper(substr($member->name, 0, 2)) }}
                            </div>
                            <span style="font-size:13px; color:#18181b;">{{ $member->name }}</span>
                        </div>
                        @if($submitted)
                            <span style="background:#E1F5EE; color:#0F6E56; font-size:11px; font-weight:500; padding:3px 10px; border-radius:20px;">Soumis</span>
                        @else
                            <span style="background:#F1EFE8; color:#5F5E5A; font-size:11px; font-weight:500; padding:3px 10px; border-radius:20px;">En attente</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

    </div>

@endsection
