<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user      = Auth::user();
        $workspace = $request->session()->get('current_workspace')
            ?? $user->workspaces()->first();

        if (! $workspace) {
            return redirect()->route('workspaces.create');
        }

        $today = now()->toDateString();

        $members = $workspace->members()->get();

        $todayStandups = $workspace->standups()
            ->with(['user', 'answers.question'])
            ->whereDate('date', $today)
            ->latest()
            ->get();

        $submittedIds = $todayStandups->pluck('user_id');

        $pendingMembers = $members->whereNotIn('id', $submittedIds);

        $userHasSubmitted = $submittedIds->contains($user->id);

        $monthStandupsCount = $workspace->standups()
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->count();

        $streak = $this->calculateStreak($workspace);

        return view('dashboard', compact(
            'workspace',
            'members',
            'todayStandups',
            'pendingMembers',
            'userHasSubmitted',
            'monthStandupsCount',
            'streak'
        ));
    }

    private function calculateStreak($workspace): int
    {
        $streak = 0;
        $date   = now()->startOfDay();

        while (true) {
            $hasStandup = $workspace->standups()
                ->whereDate('date', $date->toDateString())
                ->exists();

            if (! $hasStandup) break;

            $streak++;
            $date->subDay();
        }

        return $streak;
    }
}
