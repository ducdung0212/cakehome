<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $startOfYear = Carbon::now()->startOfYear();

        // Revenue (paid payments)
        $revenueToday = (float) Payment::completed()
            ->whereNotNull('paid_at')
            ->whereDate('paid_at', $today)
            ->sum('amount');

        $revenueYesterday = (float) Payment::completed()
            ->whereNotNull('paid_at')
            ->whereDate('paid_at', $yesterday)
            ->sum('amount');

        // New orders
        $newOrdersToday = (int) Order::whereDate('created_at', $today)->count();
        $newOrdersYesterday = (int) Order::whereDate('created_at', $yesterday)->count();

        // New customers (role = customer)
        $newCustomersToday = (int) User::whereHas('role', function ($q) {
            $q->where('name', 'customer');
        })->whereDate('created_at', $today)->count();

        $newCustomersYesterday = (int) User::whereHas('role', function ($q) {
            $q->where('name', 'customer');
        })->whereDate('created_at', $yesterday)->count();

        // Products
        $productsCount = (int) Product::count();
        $lowStockCount = (int) Product::where('stock', '<=', 5)->count();

        // Trends (vs yesterday)
        $revenueTrendPct = $this->calcTrendPct($revenueToday, $revenueYesterday);
        $ordersTrendPct = $this->calcTrendPct($newOrdersToday, $newOrdersYesterday);
        $customersTrendPct = $this->calcTrendPct($newCustomersToday, $newCustomersYesterday);

        // Revenue by month (current year), values in millions for the existing chart axis
        $revenueByMonth = array_fill(0, 12, 0.0);
        $monthly = Payment::completed()
            ->whereNotNull('paid_at')
            ->where('paid_at', '>=', $startOfYear)
            ->selectRaw('MONTH(paid_at) as m, SUM(amount) as total')
            ->groupBy('m')
            ->get();

        foreach ($monthly as $row) {
            $monthIndex = ((int) $row->m) - 1;
            if ($monthIndex >= 0 && $monthIndex < 12) {
                $revenueByMonth[$monthIndex] = round(((float) $row->total) / 1000000, 1);
            }
        }

        // Orders by status (mapped to the dashboard's 5 buckets)
        $rawStatusCounts = Order::select('status', DB::raw('COUNT(*) as c'))
            ->groupBy('status')
            ->pluck('c', 'status');

        $pendingCount = (int) ($rawStatusCounts['pending'] ?? 0);
        $confirmedCount = (int) ($rawStatusCounts['confirmed'] ?? 0)
            + (int) ($rawStatusCounts['processing'] ?? 0)
            + (int) ($rawStatusCounts['ready'] ?? 0);
        $shippingCount = (int) ($rawStatusCounts['shipping'] ?? 0)
            + (int) ($rawStatusCounts['delivered'] ?? 0);
        $completedCount = (int) ($rawStatusCounts['completed'] ?? 0);
        $cancelledCount = (int) ($rawStatusCounts['cancelled'] ?? 0);

        $orderStatusChartData = [$pendingCount, $confirmedCount, $shippingCount, $completedCount, $cancelledCount];

        // Recent orders
        $recentOrders = Order::with(['user', 'orderItems.product'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($order) {
                $firstItem = $order->orderItems->first();
                $firstName = $firstItem && $firstItem->product ? $firstItem->product->name : '---';
                $moreCount = max(0, $order->orderItems->count() - 1);
                $productSummary = $moreCount > 0 ? ($firstName . ' +' . $moreCount) : $firstName;

                return [
                    'id' => $order->id,
                    'customer' => $order->user?->name ?? '---',
                    'product_summary' => $productSummary,
                    'total_price' => (float) $order->total_price,
                    'status' => (string) $order->status,
                    'created_at' => $order->created_at,
                ];
            });

        // Top products by quantity (completed orders)
        $topProductRows = OrderItem::query()
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.status', 'completed')
            ->select('order_items.product_id', DB::raw('SUM(order_items.quantity) as qty'))
            ->groupBy('order_items.product_id')
            ->orderByDesc('qty')
            ->take(5)
            ->get();

        $topProducts = collect();
        if ($topProductRows->isNotEmpty()) {
            $productIds = $topProductRows->pluck('product_id')->all();
            $products = Product::with('firstImage')->whereIn('id', $productIds)->get()->keyBy('id');

            $topProducts = $topProductRows->map(function ($row, $index) use ($products) {
                $product = $products->get($row->product_id);
                return [
                    'rank' => $index + 1,
                    'name' => $product?->name ?? '---',
                    'qty' => (int) $row->qty,
                    'image' => $product?->firstImage?->image,
                ];
            });
        }

        return view('admin.pages.dashboard', [
            'revenueToday' => $revenueToday,
            'revenueTrendPct' => $revenueTrendPct,
            'newOrdersToday' => $newOrdersToday,
            'ordersTrendPct' => $ordersTrendPct,
            'newCustomersToday' => $newCustomersToday,
            'customersTrendPct' => $customersTrendPct,
            'productsCount' => $productsCount,
            'lowStockCount' => $lowStockCount,
            'revenueByMonth' => $revenueByMonth,
            'orderStatusChartData' => $orderStatusChartData,
            'recentOrders' => $recentOrders,
            'topProducts' => $topProducts,
        ]);
    }

    private function calcTrendPct(float|int $todayValue, float|int $yesterdayValue): float
    {
        $todayValue = (float) $todayValue;
        $yesterdayValue = (float) $yesterdayValue;

        if ($yesterdayValue <= 0.0) {
            return $todayValue > 0.0 ? 100.0 : 0.0;
        }

        return round((($todayValue - $yesterdayValue) / $yesterdayValue) * 100.0, 1);
    }
}
