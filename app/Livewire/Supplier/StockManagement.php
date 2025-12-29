<?php

namespace App\Livewire\Supplier;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;

class StockManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $stockFilter = '';

    // Stock update modal
    public $showUpdateModal = false;
    public $selectedProduct = null;
    public $newStock = 0;
    public $stockAction = 'set'; // set, add, subtract
    public $stockNote = '';

    // Stock history modal
    public $showHistoryModal = false;
    public $historyProduct = null;

    protected $rules = [
        'newStock' => 'required|numeric|min:0',
        'stockAction' => 'required|in:set,add,subtract',
        'stockNote' => 'nullable|max:500',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openUpdateModal($productId)
    {
        $supplier = auth()->user()->supplier;
        $this->selectedProduct = Product::where('supplier_id', $supplier->id)->find($productId);

        if ($this->selectedProduct) {
            $this->newStock = $this->selectedProduct->stock_quantity ?? 0;
            $this->stockAction = 'set';
            $this->stockNote = '';
            $this->showUpdateModal = true;
        }
    }

    public function updateStock()
    {
        $this->validate();

        if (!$this->selectedProduct) {
            return;
        }

        $supplier = auth()->user()->supplier;

        // Verify ownership
        if ($this->selectedProduct->supplier_id !== $supplier->id) {
            session()->flash('error', 'Acesso negado.');
            return;
        }

        $currentStock = $this->selectedProduct->stock_quantity ?? 0;
        $finalStock = $currentStock;

        switch ($this->stockAction) {
            case 'set':
                $finalStock = $this->newStock;
                break;
            case 'add':
                $finalStock = $currentStock + $this->newStock;
                break;
            case 'subtract':
                $finalStock = max(0, $currentStock - $this->newStock);
                break;
        }

        $this->selectedProduct->update([
            'stock_quantity' => $finalStock,
        ]);

        session()->flash('success', 'Stock atualizado com sucesso! Novo stock: ' . number_format($finalStock, 0, ',', '.') . ' ' . ($this->selectedProduct->unit ?? 'kg'));

        $this->closeUpdateModal();
    }

    public function closeUpdateModal()
    {
        $this->showUpdateModal = false;
        $this->selectedProduct = null;
        $this->newStock = 0;
        $this->stockAction = 'set';
        $this->stockNote = '';
    }

    public function quickAdd($productId, $amount)
    {
        $supplier = auth()->user()->supplier;
        $product = Product::where('supplier_id', $supplier->id)->find($productId);

        if ($product) {
            $newStock = ($product->stock_quantity ?? 0) + $amount;
            $product->update(['stock_quantity' => $newStock]);
            session()->flash('success', 'Adicionado ' . $amount . ' ' . ($product->unit ?? 'kg') . ' ao stock de "' . $product->name . '"');
        }
    }

    public function quickSubtract($productId, $amount)
    {
        $supplier = auth()->user()->supplier;
        $product = Product::where('supplier_id', $supplier->id)->find($productId);

        if ($product) {
            $newStock = max(0, ($product->stock_quantity ?? 0) - $amount);
            $product->update(['stock_quantity' => $newStock]);
            session()->flash('success', 'Removido ' . $amount . ' ' . ($product->unit ?? 'kg') . ' do stock de "' . $product->name . '"');
        }
    }

    public function setOutOfStock($productId)
    {
        $supplier = auth()->user()->supplier;
        $product = Product::where('supplier_id', $supplier->id)->find($productId);

        if ($product) {
            $product->update(['stock_quantity' => 0]);
            session()->flash('success', 'Produto "' . $product->name . '" marcado como sem stock.');
        }
    }

    public function render()
    {
        $supplier = auth()->user()->supplier;

        $query = $supplier
            ? Product::where('supplier_id', $supplier->id)
                ->where('is_active', true)
                ->with('category')
            : Product::where('id', 0);

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        if ($this->stockFilter === 'low') {
            $query->whereRaw('stock_quantity <= min_quantity');
        } elseif ($this->stockFilter === 'out') {
            $query->where('stock_quantity', 0);
        } elseif ($this->stockFilter === 'available') {
            $query->where('stock_quantity', '>', 0);
        }

        $products = $query->orderBy('stock_quantity', 'asc')->paginate(15);

        // Calculate stats
        $stats = [
            'total_products' => $supplier ? Product::where('supplier_id', $supplier->id)->where('is_active', true)->count() : 0,
            'out_of_stock' => $supplier ? Product::where('supplier_id', $supplier->id)->where('is_active', true)->where('stock_quantity', 0)->count() : 0,
            'low_stock' => $supplier ? Product::where('supplier_id', $supplier->id)->where('is_active', true)->whereRaw('stock_quantity <= min_quantity AND stock_quantity > 0')->count() : 0,
            'total_stock' => $supplier ? Product::where('supplier_id', $supplier->id)->where('is_active', true)->sum('stock_quantity') : 0,
        ];

        return view('livewire.supplier.stock-management', [
            'products' => $products,
            'stats' => $stats,
        ])->layout('components.layouts.supplier', ['title' => 'Gestao de Stock']);
    }
}
