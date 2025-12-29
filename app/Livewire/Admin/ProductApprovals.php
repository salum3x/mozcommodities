<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Product;

class ProductApprovals extends Component
{
    public $pendingProducts = [];
    public $selectedProduct = null;
    public $cost_price = '';
    public $platform_margin = 20; // Margem padrão de 20%
    public $calculated_price = 0;
    public $stock_kg = 0;
    public $rejection_reason = '';
    public $showApprovalModal = false;
    public $showRejectionModal = false;

    public function mount()
    {
        $this->loadPendingProducts();
    }

    public function loadPendingProducts()
    {
        $this->pendingProducts = Product::with(['supplier.user', 'category'])
            ->where('approval_status', 'pending')
            ->latest()
            ->get();
    }

    public function openApprovalModal($productId)
    {
        $this->selectedProduct = Product::with(['supplier.user', 'category'])->find($productId);
        $this->cost_price = $this->selectedProduct->cost_price ?? '';
        $this->platform_margin = $this->selectedProduct->platform_margin ?? 20;
        $this->stock_kg = $this->selectedProduct->stock_kg ?? 0;
        $this->calculatePrice();
        $this->showApprovalModal = true;
    }

    public function openRejectionModal($productId)
    {
        $this->selectedProduct = Product::find($productId);
        $this->rejection_reason = '';
        $this->showRejectionModal = true;
    }

    public function updatedCostPrice()
    {
        $this->calculatePrice();
    }

    public function updatedPlatformMargin()
    {
        $this->calculatePrice();
    }

    public function calculatePrice()
    {
        if ($this->cost_price && $this->platform_margin) {
            // P.V.P. = P.B.F. × (1 + Margem)
            $this->calculated_price = $this->cost_price * (1 + ($this->platform_margin / 100));
        }
    }

    public function approveProduct()
    {
        $this->validate([
            'cost_price' => 'required|numeric|min:0',
            'platform_margin' => 'required|numeric|min:0|max:100',
            'stock_kg' => 'required|integer|min:0',
        ]);

        $this->selectedProduct->update([
            'cost_price' => $this->cost_price,
            'platform_margin' => $this->platform_margin,
            'price_per_kg' => $this->calculated_price,
            'stock_kg' => $this->stock_kg,
            'approval_status' => 'approved',
            'is_active' => true,
        ]);

        session()->flash('message', 'Produto aprovado com sucesso!');
        $this->closeModal();
        $this->loadPendingProducts();
    }

    public function rejectProduct()
    {
        $this->validate([
            'rejection_reason' => 'required|string|min:10',
        ]);

        $this->selectedProduct->update([
            'approval_status' => 'rejected',
            'rejection_reason' => $this->rejection_reason,
            'is_active' => false,
        ]);

        session()->flash('message', 'Produto rejeitado. Fornecedor será notificado.');
        $this->closeModal();
        $this->loadPendingProducts();
    }

    public function closeModal()
    {
        $this->showApprovalModal = false;
        $this->showRejectionModal = false;
        $this->reset(['selectedProduct', 'cost_price', 'platform_margin', 'calculated_price', 'stock_kg', 'rejection_reason']);
    }

    public function render()
    {
        return view('livewire.admin.product-approvals')
            ->layout('components.layouts.admin');
    }
}
