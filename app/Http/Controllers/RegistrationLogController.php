<?php
// app/Http/Controllers/RegistrationLogController.php

namespace App\Http\Controllers;

use App\Models\RegistrationLog;
use App\Models\User;
use App\Services\RegistrationLogService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController; 

class RegistrationLogController extends BaseController
{
    protected $registrationLog;

    public function __construct(RegistrationLogService $registrationLog)
    {
        $this->middleware('auth');
        $this->registrationLog = $registrationLog;
    }

    /**
     * Display a listing of registration logs.
     */
    public function index(Request $request)
    {
        // Eager load both user and creator relationships
        $query = RegistrationLog::with(['user', 'creator'])->orderBy('created_at', 'desc');

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('is_successful', $request->status === 'success');
        }

        // Apply creator filter
        if ($request->filled('created_by')) {
            $query->where('created_by', $request->created_by);
        }

        // Apply date range filters
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Get paginated results
        $logs = $query->paginate(20);
        
        // Get statistics
        $stats = $this->registrationLog->getStats();
        
        // Get all users for the creator filter dropdown
        $users = User::orderBy('name')->get();

        return view('registration-logs.index', compact('logs', 'stats', 'users'));
    }

    /**
     * Display the specified registration log.
     */
    public function show($id)
    {
        // Eager load both user and creator relationships
        $log = RegistrationLog::with(['user', 'creator'])->findOrFail($id);
        
        return view('registration-logs.show', compact('log'));
    }

    /**
     * Export registration logs to CSV.
     */
    public function export(Request $request)
    {
        $query = RegistrationLog::with(['user', 'creator']);

        // Apply date range filters
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('is_successful', $request->status === 'success');
        }

        // Apply creator filter
        if ($request->filled('created_by')) {
            $query->where('created_by', $request->created_by);
        }

        // Get all logs matching the filters
        $logs = $query->orderBy('created_at', 'desc')->get();

        // Generate filename
        $filename = 'registration-logs-' . now()->format('Y-m-d-His') . '.csv';
        
        // Create temporary file handle
        $handle = fopen('php://temp', 'w');

        // Add CSV headers
        fputcsv($handle, [
            'ID', 
            'Name', 
            'Phone', 
            'Created By',
            'Status', 
            'Browser', 
            'Platform', 
            'Device', 
            'Location', 
            'IP Address', 
            'Error Message', 
            'Registered At'
        ]);

        // Add data rows
        foreach ($logs as $log) {
            fputcsv($handle, [
                $log->id,
                $log->name,
                $log->phone,
                $log->creator ? $log->creator->name : 'System/Auto',
                $log->is_successful ? 'Success' : 'Failed',
                $log->browser ?? 'Unknown',
                $log->platform ?? 'Unknown',
                $log->device ?? 'Unknown',
                $log->location ?? 'Unknown',
                $log->ip_address,
                $log->error_message ?? 'N/A',
                $log->created_at->format('Y-m-d H:i:s')
            ]);
        }

        // Get CSV content
        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        // Return CSV download response
        return response($content)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Get statistics by creator (admin).
     */
    public function creatorStats()
    {
        // Get registration counts grouped by creator
        $creatorStats = RegistrationLog::selectRaw('created_by, count(*) as total')
            ->whereNotNull('created_by')
            ->with('creator')
            ->groupBy('created_by')
            ->orderBy('total', 'desc')
            ->get();

        // Get system/auto registrations count
        $systemRegistrations = RegistrationLog::whereNull('created_by')->count();

        return view('registration-logs.creator-stats', compact('creatorStats', 'systemRegistrations'));
    }

    /**
     * Get recent failed attempts.
     */
    public function recentFailures()
    {
        $failedLogs = $this->registrationLog->getRecentFailures(20);
        
        return view('registration-logs.failures', compact('failedLogs'));
    }
}