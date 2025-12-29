<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\QuoteRequest;

class QuoteRequests extends Component
{
    public $statusFilter = '';
    public $selectedQuote = null;
    public $showModal = false;

    public function updateStatus($quoteId, $status)
    {
        $quote = QuoteRequest::find($quoteId);
        if ($quote) {
            $quote->update(['status' => $status]);
        }
    }

    public function viewDetails($quoteId)
    {
        $this->selectedQuote = QuoteRequest::with('product')->find($quoteId);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedQuote = null;
    }

    public function render()
    {
        $query = QuoteRequest::with('product')->latest();

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        $quotes = $query->get();

        return view('livewire.admin.quote-requests', [
            'quotes' => $quotes,
        ])->layout('components.layouts.admin', ['title' => 'Cotacoes']);
    }
}
