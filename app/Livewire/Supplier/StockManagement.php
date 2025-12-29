<?php

namespace App\Livewire\Supplier;

use Livewire\Component;

class StockManagement extends Component
{
    public function render()
    {
        return view('livewire.supplier.stock-management')
            ->layout('components.layouts.supplier');
    }
}
