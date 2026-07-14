<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use App\Models\ShippingZone;
use Livewire\Component;

class Shipping extends Component
{
    public ?int $editingId = null;
    public bool $showForm = false;

    public string $province = '';
    public string $city = '';
    public string $base_fee = '0';
    public string $per_kg_rate = '0';
    public string $free_above_amount = '';
    public string $truckload_threshold_kg = '';
    public string $truckload_flat_fee = '';
    public bool $active = true;
    public string $notes = '';

    // Global fallback settings
    public string $shipping_base_fee = '50';
    public string $shipping_price_per_km = '8';
    public string $shipping_min_fee = '80';
    public string $shipping_free_over_amount = '';

    public function mount(): void
    {
        $this->ensureAdmin();
        $this->shipping_base_fee         = (string) Setting::get('shipping_base_fee', '50');
        $this->shipping_price_per_km     = (string) Setting::get('shipping_price_per_km', '8');
        $this->shipping_min_fee          = (string) Setting::get('shipping_min_fee', '80');
        $this->shipping_free_over_amount = (string) Setting::get('shipping_free_over_amount', '');
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $z = ShippingZone::findOrFail($id);
        $this->editingId = $z->id;
        $this->province = $z->province;
        $this->city = (string) $z->city;
        $this->base_fee = (string) $z->base_fee;
        $this->per_kg_rate = (string) $z->per_kg_rate;
        $this->free_above_amount = (string) ($z->free_above_amount ?? '');
        $this->truckload_threshold_kg = (string) ($z->truckload_threshold_kg ?? '');
        $this->truckload_flat_fee = (string) ($z->truckload_flat_fee ?? '');
        $this->active = (bool) $z->active;
        $this->notes = (string) $z->notes;
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->ensureAdmin();
        $data = $this->validate([
            'province' => 'required|string|max:100',
            'city' => 'nullable|string|max:100',
            'base_fee' => 'required|numeric|min:0',
            'per_kg_rate' => 'required|numeric|min:0',
            'free_above_amount' => 'nullable|numeric|min:0',
            'truckload_threshold_kg' => 'nullable|integer|min:0',
            'truckload_flat_fee' => 'nullable|numeric|min:0',
            'active' => 'boolean',
            'notes' => 'nullable|string|max:255',
        ]);
        foreach (['free_above_amount', 'truckload_threshold_kg', 'truckload_flat_fee'] as $k) {
            if ($data[$k] === '' || $data[$k] === null) $data[$k] = null;
        }
        if ($this->editingId) {
            ShippingZone::findOrFail($this->editingId)->update($data);
            session()->flash('message', "Zona {$this->province} atualizada.");
        } else {
            ShippingZone::create($data);
            session()->flash('message', "Zona {$this->province} criada.");
        }
        $this->resetForm();
        $this->showForm = false;
    }

    public function delete(int $id): void
    {
        $this->ensureAdmin();
        ShippingZone::findOrFail($id)->delete();
        session()->flash('message', 'Zona removida.');
    }

    public function toggleActive(int $id): void
    {
        $z = ShippingZone::findOrFail($id);
        $z->update(['active' => !$z->active]);
    }

    public function saveFallback(): void
    {
        $this->ensureAdmin();
        $this->validate([
            'shipping_base_fee'         => 'required|numeric|min:0',
            'shipping_price_per_km'     => 'required|numeric|min:0',
            'shipping_min_fee'          => 'required|numeric|min:0',
            'shipping_free_over_amount' => 'nullable|numeric|min:0',
        ]);
        Setting::set('shipping_base_fee', $this->shipping_base_fee);
        Setting::set('shipping_price_per_km', $this->shipping_price_per_km);
        Setting::set('shipping_min_fee', $this->shipping_min_fee);
        Setting::set('shipping_free_over_amount', $this->shipping_free_over_amount ?: '');
        session()->flash('message', 'Tarifa de fallback (distância) atualizada.');
    }

    protected function resetForm(): void
    {
        $this->editingId = null;
        $this->province = '';
        $this->city = '';
        $this->base_fee = '0';
        $this->per_kg_rate = '0';
        $this->free_above_amount = '';
        $this->truckload_threshold_kg = '';
        $this->truckload_flat_fee = '';
        $this->active = true;
        $this->notes = '';
    }

    protected function ensureAdmin(): void
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') abort(403);
    }

    public function render()
    {
        return view('livewire.admin.shipping', [
            'zones' => ShippingZone::orderBy('province')->get(),
        ])->layout('components.layouts.admin');
    }
}
