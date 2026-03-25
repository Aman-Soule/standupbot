<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\WorkspaceMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MemberController extends Controller
{
    /**
     * Récupère le workspace actif de l'utilisateur connecté.
     * Priorité : session → premier workspace en base.
     */
    private function getCurrentWorkspace(Request $request)
    {
        // 1. Essaie depuis la session (si déjà switché)
        $workspace = $request->session()->get('current_workspace');

        if ($workspace) {
            return $workspace;
        }

        // 2. Fallback : premier workspace dont l'utilisateur est membre
        $workspace = Auth::user()
            ->workspaces()
            ->first();

        // 3. Si trouvé, on le sauvegarde en session pour les prochains appels
        if ($workspace) {
            $request->session()->put('current_workspace', $workspace);
        }

        return $workspace;
    }

    public function index(Request $request)
    {
        $workspace = $this->getCurrentWorkspace($request);

        abort_if(! $workspace, 404);

        $members = $workspace->members()
            ->withPivot('role')
            ->get();

        $invitations = $workspace->invitations()
            ->where('status', 'pending')
            ->get();

        return view('members.index', compact('workspace', 'members', 'invitations'));
    }

    public function invite(Request $request)
    {
        $workspace = $this->getCurrentWorkspace($request);

        abort_if(! $workspace, 404);

        $member = WorkspaceMember::where('workspace_id', $workspace->id)
            ->where('user_id', Auth::id())
            ->first();

        abort_if(! $member || $member->role !== 'admin', 403, 'Action réservée aux admins.');

        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Déjà membre ?
        $alreadyMember = $workspace->members()
            ->where('email', $request->email)
            ->exists();

        if ($alreadyMember) {
            return back()->with('error', 'Cet utilisateur est déjà membre.');
        }

        Invitation::updateOrCreate(
            ['workspace_id' => $workspace->id, 'email' => $request->email],
            [
                'token'      => Str::random(32),
                'status'     => 'pending',
                'expires_at' => now()->addDays(7),
            ]
        );

        // TODO: envoyer l'email d'invitation (semaine 3)

        return back()->with('success', 'Invitation envoyée.');
    }

    public function remove(Request $request, $userId)
    {
        $workspace = $this->getCurrentWorkspace($request);

        abort_if(! $workspace, 404);
        abort_if($userId == Auth::id(), 403, 'Vous ne pouvez pas vous retirer vous-même.');

        WorkspaceMember::where('workspace_id', $workspace->id)
            ->where('user_id', $userId)
            ->delete();

        return back()->with('success', 'Membre retiré.');
    }
}
