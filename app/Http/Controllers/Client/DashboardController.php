<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;

class DashboardController extends Controller
{
    public function index()
    {
        $client = auth()->user()->client;

        if (!$client) {
            return view('client.dashboard', ['noProfile' => true]);
        }

        $projectIds = Project::where('client_id', $client->id)->pluck('id');

        $totalProjectsCount = Project::where('client_id', $client->id)->count();
        $activeProjectsCount = Project::where('client_id', $client->id)
            ->where('status', 'In Progress')
            ->count();

        $totalTasksCount = Task::whereIn('project_id', $projectIds)->count();
        $completedTasksCount = Task::whereIn('project_id', $projectIds)
            ->where('status', 'Completed')
            ->count();
        $pendingTasksCount = Task::whereIn('project_id', $projectIds)
            ->whereIn('status', ['Pending', 'In Progress'])
            ->count();

        $projects = Project::where('client_id', $client->id)
            ->with('manager')
            ->latest()
            ->take(5)
            ->get();

        $recentTasks = Task::whereIn('project_id', $projectIds)
            ->with(['employee', 'project'])
            ->latest()
            ->take(5)
            ->get();

        return view('client.dashboard', compact(
            'client',
            'projects',
            'recentTasks',
            'totalProjectsCount',
            'activeProjectsCount',
            'totalTasksCount',
            'completedTasksCount',
            'pendingTasksCount'
        ));
    }
}