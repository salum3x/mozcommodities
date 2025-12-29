<?php

namespace App\Livewire\Supplier;

use Livewire\Component;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Reports extends Component
{
    public $period = 'month'; // week, month, year, all

    public function render()
    {
        $supplier = auth()->user()->supplier;

        if (!$supplier) {
            return view('livewire.supplier.reports', [
                'supplier' => null,
                'stats' => [],
                'topProducts' => collect(),
                'salesByMonth' => collect(),
                'recentSales' => collect(),
            ])->layout('components.layouts.supplier', ['title' => 'Relatorios']);
        }

        // Get supplier's product IDs
        $supplierProductIds = Product::where('supplier_id', $supplier->id)->pluck('id');

        // Base query for paid orders with supplier's products
        $baseQuery = OrderItem::whereIn('product_id', $supplierProductIds)
            ->whereHas('order', fn($q) => $q->where('payment_status', 'paid'));

        // Apply date filter
        $filteredQuery = clone $baseQuery;
        switch ($this->period) {
            case 'week':
                $filteredQuery->whereHas('order', fn($q) => $q->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]));
                break;
            case 'month':
                $filteredQuery->whereHas('order', fn($q) => $q->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year));
                break;
            case 'year':
                $filteredQuery->whereHas('order', fn($q) => $q->whereYear('created_at', now()->year));
                break;
        }

        // Calculate stats
        $stats = [
            'total_revenue' => (clone $filteredQuery)->sum('subtotal'),
            'total_items_sold' => (clone $filteredQuery)->sum('quantity'),
            'total_orders' => (clone $filteredQuery)->distinct('order_id')->count('order_id'),
            'average_order_value' => 0,
        ];

        if ($stats['total_orders'] > 0) {
            $stats['average_order_value'] = $stats['total_revenue'] / $stats['total_orders'];
        }

        // Compare with previous period
        $previousQuery = clone $baseQuery;
        switch ($this->period) {
            case 'week':
                $previousQuery->whereHas('order', fn($q) => $q->whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()]));
                break;
            case 'month':
                $previousQuery->whereHas('order', fn($q) => $q->whereMonth('created_at', now()->subMonth()->month)->whereYear('created_at', now()->subMonth()->year));
                break;
            case 'year':
                $previousQuery->whereHas('order', fn($q) => $q->whereYear('created_at', now()->subYear()->year));
                break;
        }

        $previousRevenue = $previousQuery->sum('subtotal');
        $stats['revenue_change'] = $previousRevenue > 0
            ? round((($stats['total_revenue'] - $previousRevenue) / $previousRevenue) * 100, 1)
            : ($stats['total_revenue'] > 0 ? 100 : 0);

        // Top selling products
        $topProducts = OrderItem::whereIn('product_id', $supplierProductIds)
            ->whereHas('order', fn($q) => $q->where('payment_status', 'paid'))
            ->select('product_id', 'product_name', DB::raw('SUM(quantity) as total_quantity'), DB::raw('SUM(subtotal) as total_revenue'))
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('total_revenue')
            ->limit(5)
            ->get();

        // Sales by month (last 6 months)
        $salesByMonth = OrderItem::whereIn('product_id', $supplierProductIds)
            ->whereHas('order', function ($q) {
                $q->where('payment_status', 'paid')
                  ->where('created_at', '>=', now()->subMonths(6)->startOfMonth());
            })
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->select(
                DB::raw('YEAR(orders.created_at) as year'),
                DB::raw('MONTH(orders.created_at) as month'),
                DB::raw('SUM(order_items.subtotal) as total')
            )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                $date = Carbon::createFromDate($item->year, $item->month, 1);
                return [
                    'label' => $date->translatedFormat('M Y'),
                    'total' => $item->total,
                ];
            });

        // Recent sales
        $recentSales = OrderItem::whereIn('product_id', $supplierProductIds)
            ->whereHas('order', fn($q) => $q->where('payment_status', 'paid'))
            ->with(['order', 'product'])
            ->latest()
            ->limit(10)
            ->get();

        // Products stats
        $productStats = [
            'total_products' => Product::where('supplier_id', $supplier->id)->count(),
            'active_products' => Product::where('supplier_id', $supplier->id)->where('is_active', true)->where('approval_status', 'approved')->count(),
            'pending_approval' => Product::where('supplier_id', $supplier->id)->where('approval_status', 'pending')->count(),
            'out_of_stock' => Product::where('supplier_id', $supplier->id)->where('stock_quantity', 0)->count(),
        ];

        return view('livewire.supplier.reports', [
            'supplier' => $supplier,
            'stats' => $stats,
            'topProducts' => $topProducts,
            'salesByMonth' => $salesByMonth,
            'recentSales' => $recentSales,
            'productStats' => $productStats,
        ])->layout('components.layouts.supplier', ['title' => 'Relatorios']);
    }
}
