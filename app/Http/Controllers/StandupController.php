<?php

namespace App\Http\Controllers;

use App\Models\Standup;
use App\Models\StandupAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StandupController extends Controller
{
    public function create(Request $request)
    {
        $workspace = $request->session()->get('current_workspace');

        abort_if(! $workspace, 404);

        // Déjà soumis aujourd'hui ?
        $alreadySubmitted = Standup::where('workspace_id', $workspace->id)
            ->where('user_id', Auth::id())
            ->whereDate('date', today())
            ->exists();

        if ($alreadySubmitted) {
            return redirect()->route('dashboard')
                ->with('info', 'Vous avez déjà soumis votre standup aujourd\'hui.');
        }

        $questions = $workspace->questions()
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        return view('standups.create', compact('workspace', 'questions'));
    }

    public function store(Request $request)
    {
        $workspace = $request->session()->get('current_workspace');

        abort_if(! $workspace, 404);

        $request->validate([
            'answers'   => ['required', 'array'],
            'answers.*' => ['required', 'string', 'max:1000'],
        ]);

        // Empêcher la double soumission (race condition)
        $exists = Standup::where('workspace_id', $workspace->id)
            ->where('user_id', Auth::id())
            ->whereDate('date', today())
            ->exists();

        if ($exists) {
            return redirect()->route('dashboard');
        }

        DB::transaction(function () use ($request, $workspace) {
            $standup = Standup::create([
                'workspace_id' => $workspace->id,
                'user_id'      => Auth::id(),
                'date'         => today(),
            ]);

            foreach ($request->answers as $questionId => $answer) {
                StandupAnswer::create([
                    'standup_id'  => $standup->id,
                    'question_id' => $questionId,
                    'answer'      => $answer,
                ]);
            }
        });

        return redirect()->route('dashboard')
            ->with('success', 'Standup soumis avec succès.');
    }

    public function history(Request $request)
    {
        $workspace = $request->session()->get('current_workspace');

        abort_if(! $workspace, 404);

        $standups = $workspace->standups()
            ->with(['user', 'answers.question'])
            ->orderByDesc('date')
            ->paginate(20);

        return view('standups.history', compact('workspace', 'standups'));
    }
}
