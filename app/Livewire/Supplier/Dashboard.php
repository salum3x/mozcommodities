<?php

namespace App\Livewire\Supplier;

use Livewire\Component;
use App\Models\Product;
use App\Models\Supplier;

class Dashboard extends Component
{
    public function render()
    {
        $supplier = auth()->user()->supplier;

        $stats = [
            'total_products' => $supplier ? Product::where('supplier_id', $supplier->id)->count() : 0,
            'active_products' => $supplier ? Product::where('supplier_id', $supplier->id)->where('is_active', true)->count() : 0,
            'pending_products' => $supplier ? Product::where('supplier_id', $supplier->id)->where('approval_status', 'pending')->count() : 0,
            'total_stock' => $supplier ? Product::where('supplier_id', $supplier->id)->sum('stock_quantity') : 0,
        ];

        $recentProducts = $supplier
            ? Product::where('supplier_id', $supplier->id)
                ->with('category')
                ->latest()
                ->take(5)
                ->get()
            : collect();

        $lowStockProducts = $supplier
            ? Product::where('supplier_id', $supplier->id)
                ->where('stock_quantity', '<', 10)
                ->where('is_active', true)
                ->take(5)
                ->get()
            : collect();

        return view('livewire.supplier.dashboard', [
            'supplier' => $supplier,
            'stats' => $stats,
            'recentProducts' => $recentProducts,
            'lowStockProducts' => $lowStockProducts,
        ])->layout('components.layouts.supplier', ['title' => 'Dashboard']);
    }
}
