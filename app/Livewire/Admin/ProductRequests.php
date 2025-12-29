<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\ProductRequest;

class ProductRequests extends Component
{
    public $selectedRequest = null;
    public $admin_notes = '';
    public $showNotesModal = false;

    public function updateStatus($requestId, $status)
    {
        $request = ProductRequest::find($requestId);
        if ($request) {
            $request->update(['status' => $status]);
            $this->dispatch('status-updated');
        }
    }

    public function openNotesModal($requestId)
    {
        $this->selectedRequest = ProductRequest::find($requestId);
        $this->admin_notes = $this->selectedRequest->admin_notes ?? '';
        $this->showNotesModal = true;
    }

    public function saveNotes()
    {
        if ($this->selectedRequest) {
            $this->selectedRequest->update([
                'admin_notes' => $this->admin_notes,
            ]);
            $this->showNotesModal = false;
            $this->selectedRequest = null;
            $this->admin_notes = '';
        }
    }

    public function render()
    {
        $requests = ProductRequest::latest()->get();

        return view('livewire.admin.product-requests', [
            'requests' => $requests,
        ])->layout('components.layouts.admin');
    }
}
