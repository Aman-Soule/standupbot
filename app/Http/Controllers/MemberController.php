<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\WorkspaceMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $workspace = $request->session()->get('current_workspace');

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
        $workspace = $request->session()->get('current_workspace');

        abort_if(! $workspace, 404);

        $this->authorize('admin', $workspace);

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
        $workspace = $request->session()->get('current_workspace');

        abort_if(! $workspace, 404);
        abort_if($userId == Auth::id(), 403, 'Vous ne pouvez pas vous retirer vous-même.');

        WorkspaceMember::where('workspace_id', $workspace->id)
            ->where('user_id', $userId)
            ->delete();

        return back()->with('success', 'Membre retiré.');
    }
}
