<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Station;
use App\Models\Train;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $userCount        = User::where('role', 'user')->count();
        $transactionCount = Transaction::count();
        $pendingCount     = Transaction::where('status', 'pending')->count();
        $totalRevenue     = Transaction::where('status', 'paid')->sum('total_price')
                         + Transaction::where('status', 'completed')->sum('total_price');
        $trainCount       = Train::where('is_active', true)->count();
        $stationCount     = Station::where('is_active', true)->count();
        $scheduleCount    = Schedule::where('is_active', true)->count();
        $todayCount       = Transaction::whereDate('created_at', today())->count();

        // Monthly revenue for current year (Jan–Dec)
        $year = now()->year;
        $monthlyRevenue = Transaction::select(
        DB::raw("CAST(strftime('%m', created_at) AS INTEGER) as month"),
        DB::raw('SUM(total_price) as total')
    )
    ->whereYear('created_at', $year)
    ->whereIn('status', ['paid', 'completed'])
    ->groupBy('month')
    ->pluck('total', 'month');

        $revenueData = collect(range(1, 12))->map(fn($m) => (float) ($monthlyRevenue[$m] ?? 0));

        // Status breakdown for pie chart
        $statusCounts = Transaction::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('admin.dashboard', compact(
            'userCount', 'transactionCount', 'pendingCount', 'totalRevenue',
            'trainCount', 'stationCount', 'scheduleCount', 'todayCount',
            'revenueData', 'statusCounts', 'year'
        ));
    }
}
