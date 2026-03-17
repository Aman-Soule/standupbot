@extends('layouts.app')
@section('title', 'Mon standup')
@section('page-title', 'Mon standup du jour')

@section('content')

    <div style="max-width:600px;">

        <p style="font-size:13px; color:#888780; margin:0 0 24px;">
            {{ now()->translatedFormat('l d F Y') }}
        </p>

        @if ($errors->any())
            <div style="background:#FEF2F2; border:1px solid #FECACA; border-radius:8px; padding:12px 16px; margin-bottom:20px; font-size:13px; color:#B91C1C;">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('standups.store') }}">
            @csrf

            @foreach($questions as $question)
                <div style="background:#fff; border:0.5px solid #e5e5e5; border-radius:10px; padding:20px; margin-bottom:16px;">
                    <label style="display:block; font-size:14px; font-weight:600; color:#18181b; margin-bottom:10px;">
                        {{ $question->question }}
                    </label>
                    <textarea name="answers[{{ $question->id }}]" rows="3" required
                              style="width:100%; border:1px solid #e5e5e5; border-radius:8px; padding:10px 12px; font-size:13px; color:#18181b; outline:none; resize:vertical; font-family:sans-serif; box-sizing:border-box;"
                              placeholder="Votre réponse...">{{ old("answers.{$question->id}") }}</textarea>
                </div>
            @endforeach

            <button type="submit"
                    style="height:42px; padding:0 28px; background:#18181b; border:none; border-radius:8px; color:#fff; font-size:14px; font-weight:600; cursor:pointer;">
                Soumettre mon standup
            </button>
        </form>

    </div>

@endsection
