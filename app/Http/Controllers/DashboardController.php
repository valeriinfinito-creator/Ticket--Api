<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Device;
use App\Models\Ticket;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $metrics = [
            'total_tickets'   => Ticket::count(),
            'total_devices'   => Device::count(),
            'total_users'     => User::count(),
            'open_tickets'    => Ticket::where('status', 'open')->count(),
            'assigned_devices' => Device::where('status', 'assigned')->count(),
        ];

        $recentLogs = ActivityLog::with('user')
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard', compact('metrics', 'recentLogs'));
    }
}
