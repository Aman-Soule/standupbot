<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use App\Models\WorkspaceMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class WorkspaceController extends Controller
{
    public function create()
    {
        return view('workspaces.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:60'],
        ]);

        $workspace = Workspace::create([
            'name'     => $request->name,
            'slug'     => Str::slug($request->name) . '-' . Str::random(5),
            'owner_id' => Auth::id(),
        ]);

        // L'owner devient automatiquement admin
        WorkspaceMember::create([
            'workspace_id' => $workspace->id,
            'user_id'      => Auth::id(),
            'role'         => 'admin',
        ]);

        // Questions par défaut
        $defaultQuestions = [
            ["question" => "Qu'as-tu accompli hier ?",        "order" => 1],
            ["question" => "Que vas-tu faire aujourd'hui ?",  "order" => 2],
            ["question" => "As-tu des blocages ?",            "order" => 3],
        ];

        foreach ($defaultQuestions as $q) {
            $workspace->questions()->create($q);
        }

        $request->session()->put('current_workspace', $workspace);

        return redirect()->route('dashboard')
            ->with('success', 'Workspace créé avec succès.');
    }

    public function switch(Request $request, Workspace $workspace)
    {
        // Vérifier que l'user appartient bien à ce workspace
        abort_unless(
            $workspace->members()->where('user_id', Auth::id())->exists(),
            403
        );

        $request->session()->put('current_workspace', $workspace);

        return redirect()->route('dashboard');
    }
}
