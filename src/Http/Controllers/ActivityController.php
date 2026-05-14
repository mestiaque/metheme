<?php

namespace ME\Http\Controllers;

use ME\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ActivityController extends Controller
{
    /**
     * Display a listing of user activities with filters
     */
    public function index(Request $request): View
    {
        $query = UserActivity::with('user');

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by activity type
        if ($request->filled('activity_type')) {
            $query->where('activity_type', $request->activity_type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('activity_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('activity_at', '<=', $request->date_to);
        }

        // Filter by IP address
        if ($request->filled('ip_address')) {
            $query->where('ip_address', 'like', '%' . $request->ip_address . '%');
        }

        // Filter by browser
        if ($request->filled('browser_name')) {
            $query->where('browser_name', 'like', '%' . $request->browser_name . '%');
        }

        // Filter by device type
        if ($request->filled('device_type')) {
            $query->where('device_type', $request->device_type);
        }

        // Search in user name and email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        // Sort by latest activity
        $query->orderBy('activity_at', 'desc');

        // Paginate results
        $activities = $query->paginate(20)->withQueryString();

        // Get unique activity types from stored activity logs
        $activityTypes = UserActivity::query()
            ->whereNotNull('activity_type')
            ->where('activity_type', '!=', '')
            ->distinct()
            ->orderBy('activity_type')
            ->pluck('activity_type')
            ->mapWithKeys(function (string $type) {
                return [$type => ucfirst(str_replace('_', ' ', $type))];
            })
            ->toArray();

        $statuses = [
            'success' => __('Success'),
            'failed' => __('Failed'),
            'pending' => __('Pending'),
        ];

        $deviceTypes = [
            'phone' => __('Phone'),
            'tablet' => __('Tablet'),
            'desktop' => __('Desktop'),
        ];

        $currentIp = $request->ip();
        $currentUserAgent = $request->userAgent();

        return view('me::activity.index', compact(
            'activities',
            'activityTypes',
            'statuses',
            'deviceTypes',
            'currentIp',
            'currentUserAgent'
        ));
    }

    /**
     * Force logout a specific device/session by activity row
     */
    public function logoutDevice(Request $request, UserActivity $activity): RedirectResponse
    {
        if ((int) $activity->user_id !== (int) Auth::id()) {
            return back()->withErrors(['activity' => __('You are not allowed to logout this device.')]);
        }

        if ($activity->activity_type !== 'login' || $activity->status !== 'success') {
            return back()->withErrors(['activity' => __('Only successful login activities can be logged out.')]);
        }

        if (!Schema::hasTable('sessions')) {
            return back()->withErrors(['activity' => __('Sessions table not found. Device logout is unavailable.')]);
        }

        $sessionQuery = DB::table('sessions')->where('user_id', $activity->user_id);

        if (!empty($activity->ip_address)) {
            $sessionQuery->where('ip_address', $activity->ip_address);
        }

        if (!empty($activity->user_agent)) {
            $sessionQuery->where('user_agent', $activity->user_agent);
        }

        $deletedSessions = $sessionQuery->delete();

        if ($deletedSessions < 1) {
            return back()->withErrors(['activity' => __('No active session found for this device. It may already be logged out.')]);
        }

        UserActivity::create([
            'user_id' => $activity->user_id,
            'activity_type' => 'logout',
            'ip_address' => $activity->ip_address,
            'browser_name' => $activity->browser_name,
            'browser_version' => $activity->browser_version,
            'device_name' => $activity->device_name,
            'device_type' => $activity->device_type,
            'os_name' => $activity->os_name,
            'os_version' => $activity->os_version,
            'user_agent' => $activity->user_agent,
            'status' => 'success',
            'description' => 'Force logout from activity list. Source login activity #' . $activity->id,
            'activity_at' => now(),
        ]);

        return back()->with('status', __('Device logged out successfully.'));
    }

    /**
     * Show activity details
     */
    public function show(UserActivity $activity): View
    {
        return view('me::activity.show', compact('activity'));
    }

    /**
     * Get activity statistics
     */
    public function statistics(Request $request)
    {
        $days = $request->get('days', 7);
        $fromDate = now()->subDays($days);

        $stats = [
            'total_logins' => UserActivity::where('activity_type', 'login')
                ->where('status', 'success')
                ->where('activity_at', '>=', $fromDate)
                ->count(),
            'total_registrations' => UserActivity::where('activity_type', 'registration')
                ->where('status', 'success')
                ->where('activity_at', '>=', $fromDate)
                ->count(),
            'total_password_resets' => UserActivity::where('activity_type', 'password_reset')
                ->where('status', 'success')
                ->where('activity_at', '>=', $fromDate)
                ->count(),
            'failed_logins' => UserActivity::where('activity_type', 'login')
                ->where('status', 'failed')
                ->where('activity_at', '>=', $fromDate)
                ->count(),
            'active_users' => UserActivity::where('activity_type', 'login')
                ->where('status', 'success')
                ->where('activity_at', '>=', $fromDate)
                ->distinct('user_id')
                ->count('user_id'),
        ];

        return response()->json($stats);
    }

    /**
     * Export activities to CSV
     */
    public function export(Request $request)
    {
        $query = UserActivity::with('user');

        // Apply same filters as index
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('activity_type')) {
            $query->where('activity_type', $request->activity_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('activity_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('activity_at', '<=', $request->date_to);
        }

        $activities = $query->orderBy('activity_at', 'desc')->get();

        $filename = 'user_activities_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $columns = [
            'ID',
            'User',
            'Activity Type',
            'IP Address',
            'Browser',
            'Device',
            'OS',
            'Status',
            'Date',
        ];

        $callback = function () use ($activities, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($activities as $activity) {
                fputcsv($file, [
                    $activity->id,
                    $activity->user?->name ?? 'Guest',
                    $activity->getActivityTypeLabel(),
                    $activity->ip_address,
                    $activity->browser_name . ' ' . $activity->browser_version,
                    $activity->device_name,
                    $activity->os_name . ' ' . $activity->os_version,
                    ucfirst($activity->status),
                    $activity->activity_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
