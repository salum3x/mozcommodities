<?php

namespace App\Livewire\Supplier;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class MySales extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $dateFilter = '';
    public $showOrderModal = false;
    public $selectedOrder = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function viewOrder($orderId)
    {
        $supplier = auth()->user()->supplier;

        $this->selectedOrder = Order::with(['items' => function ($query) use ($supplier) {
            $query->whereHas('product', function ($q) use ($supplier) {
                $q->where('supplier_id', $supplier->id);
            });
        }, 'items.product'])->find($orderId);

        $this->showOrderModal = true;
    }

    public function closeModal()
    {
        $this->showOrderModal = false;
        $this->selectedOrder = null;
    }

    public function render()
    {
        $supplier = auth()->user()->supplier;

        if (!$supplier) {
            return view('livewire.supplier.my-sales', [
                'orders' => collect(),
                'stats' => [
                    'total_sales' => 0,
                    'total_revenue' => 0,
                    'pending_orders' => 0,
                    'completed_orders' => 0,
                ],
                'supplier' => null,
            ])->layout('components.layouts.supplier', ['title' => 'Minhas Vendas']);
        }

        // Get product IDs for this supplier
        $supplierProductIds = Product::where('supplier_id', $supplier->id)->pluck('id');

        // Get order IDs that contain supplier's products
        $orderIds = OrderItem::whereIn('product_id', $supplierProductIds)
            ->pluck('order_id')
            ->unique();

        // Build query
        $query = Order::whereIn('id', $orderIds)
            ->with(['items' => function ($query) use ($supplierProductIds) {
                $query->whereIn('product_id', $supplierProductIds);
            }, 'items.product']);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('order_number', 'like', '%' . $this->search . '%')
                  ->orWhere('customer_name', 'like', '%' . $this->search . '%')
                  ->orWhere('customer_email', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->dateFilter) {
            switch ($this->dateFilter) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', now()->month)
                          ->whereYear('created_at', now()->year);
                    break;
            }
        }

        $orders = $query->latest()->paginate(10);

        // Calculate stats for supplier's items only
        $stats = [
            'total_sales' => OrderItem::whereIn('product_id', $supplierProductIds)
                ->whereHas('order', fn($q) => $q->where('payment_status', 'paid'))
                ->count(),
            'total_revenue' => OrderItem::whereIn('product_id', $supplierProductIds)
                ->whereHas('order', fn($q) => $q->where('payment_status', 'paid'))
                ->sum('subtotal'),
            'pending_orders' => Order::whereIn('id', $orderIds)
                ->where('status', 'pending')
                ->count(),
            'completed_orders' => Order::whereIn('id', $orderIds)
                ->where('status', 'completed')
                ->count(),
        ];

        return view('livewire.supplier.my-sales', [
            'orders' => $orders,
            'stats' => $stats,
            'supplier' => $supplier,
        ])->layout('components.layouts.supplier', ['title' => 'Minhas Vendas']);
    }
}
