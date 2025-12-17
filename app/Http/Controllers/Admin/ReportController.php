<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        return redirect()->route('admin.reports.revenue', $request->query());
    }

    public function revenue(Request $request)
    {
        $data = $request->validate([
            'range' => ['nullable', 'in:today,week,month,quarter,year,custom'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $range = $data['range'] ?? 'month';

        [$startAt, $endAt] = $this->resolveDateRange($range, $data['start_date'] ?? null, $data['end_date'] ?? null);

        $basePayments = Payment::query()
            ->completed()
            ->whereNotNull('paid_at')
            ->whereBetween('paid_at', [$startAt, $endAt]);

        $totalRevenue = (float) (clone $basePayments)->sum('amount');

        $paidOrdersCount = (int) (clone $basePayments)
            ->distinct('order_id')
            ->count('order_id');

        $aov = $paidOrdersCount > 0 ? ($totalRevenue / $paidOrdersCount) : 0.0;

        $byMethodRows = (clone $basePayments)
            ->select('payment_method', DB::raw('SUM(amount) as total_amount'), DB::raw('COUNT(*) as payments_count'))
            ->groupBy('payment_method')
            ->get();

        $methods = [
            'cash' => ['label' => 'COD (Tiền mặt)', 'amount' => 0.0, 'count' => 0, 'pct' => 0.0],
            'momo' => ['label' => 'MoMo', 'amount' => 0.0, 'count' => 0, 'pct' => 0.0],
        ];

        foreach ($byMethodRows as $row) {
            $key = (string) $row->payment_method;
            if (!isset($methods[$key])) {
                $methods[$key] = ['label' => strtoupper($key), 'amount' => 0.0, 'count' => 0, 'pct' => 0.0];
            }
            $methods[$key]['amount'] = (float) $row->total_amount;
            $methods[$key]['count'] = (int) $row->payments_count;
        }

        foreach ($methods as $key => $m) {
            $methods[$key]['pct'] = $totalRevenue > 0 ? round(($m['amount'] / $totalRevenue) * 100, 1) : 0.0;
        }

        [$timeLabels, $timeValues] = $this->buildRevenueSeries($startAt, $endAt, $basePayments);

        return view('admin.pages.reports.revenue', [
            'range' => $range,
            'startAt' => $startAt,
            'endAt' => $endAt,
            'totalRevenue' => $totalRevenue,
            'paidOrdersCount' => $paidOrdersCount,
            'aov' => $aov,
            'methods' => $methods,
            'timeLabels' => $timeLabels,
            'timeValues' => $timeValues,
        ]);
    }

    private function resolveDateRange(string $range, ?string $startDate, ?string $endDate): array
    {
        $now = Carbon::now();

        return match ($range) {
            'today' => [Carbon::today()->startOfDay(), Carbon::today()->endOfDay()],
            'week' => [$now->copy()->startOfWeek()->startOfDay(), $now->copy()->endOfDay()],
            'month' => [$now->copy()->startOfMonth()->startOfDay(), $now->copy()->endOfDay()],
            'quarter' => [$now->copy()->startOfQuarter()->startOfDay(), $now->copy()->endOfDay()],
            'year' => [$now->copy()->startOfYear()->startOfDay(), $now->copy()->endOfDay()],
            'custom' => [
                Carbon::parse($startDate ?: $now->toDateString())->startOfDay(),
                Carbon::parse($endDate ?: $now->toDateString())->endOfDay(),
            ],
            default => [$now->copy()->startOfMonth()->startOfDay(), $now->copy()->endOfDay()],
        };
    }

    private function buildRevenueSeries(Carbon $startAt, Carbon $endAt, $basePayments): array
    {
        $days = $startAt->diffInDays($endAt) + 1;
        $driver = DB::connection()->getDriverName();

        $groupByMonth = $days > 62;

        if ($groupByMonth) {
            $keyExpr = $driver === 'sqlite'
                ? "strftime('%Y-%m', paid_at)"
                : "DATE_FORMAT(paid_at, '%Y-%m')";

            $rows = (clone $basePayments)
                ->selectRaw($keyExpr . ' as k, SUM(amount) as total')
                ->groupBy('k')
                ->orderBy('k')
                ->get();

            $map = $rows->pluck('total', 'k')->toArray();

            $cursor = $startAt->copy()->startOfMonth();
            $endMonth = $endAt->copy()->startOfMonth();

            $labels = [];
            $values = [];
            while ($cursor <= $endMonth) {
                $k = $cursor->format('Y-m');
                $labels[] = $cursor->format('m/Y');
                $values[] = (float) ($map[$k] ?? 0);
                $cursor->addMonth();
            }

            return [$labels, $values];
        }

        $rows = (clone $basePayments)
            ->selectRaw('DATE(paid_at) as d, SUM(amount) as total')
            ->groupBy('d')
            ->orderBy('d')
            ->get();

        $map = $rows->pluck('total', 'd')->toArray();

        $labels = [];
        $values = [];
        $cursor = $startAt->copy();
        while ($cursor->lte($endAt)) {
            $d = $cursor->toDateString();
            $labels[] = $cursor->format('d/m');
            $values[] = (float) ($map[$d] ?? 0);
            $cursor->addDay();
        }

        return [$labels, $values];
    }
}
