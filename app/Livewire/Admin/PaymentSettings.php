<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use Livewire\Component;

class PaymentSettings extends Component
{
    // e-Mola (Movitel) — iTcore protocol
    public string $emola_api_key = '';
    public string $emola_username = '';
    public string $emola_password = '';
    public string $emola_partner_code = '';
    public string $emola_base_url = '';
    public bool $emola_sandbox = true;
    public bool $payment_emola_enabled = true;

    // M-Pesa (Vodacom)
    public string $mpesa_api_key = '';
    public string $mpesa_public_key = '';
    public string $mpesa_service_provider_code = '';
    public bool $mpesa_sandbox = true;
    public bool $payment_mpesa_enabled = false;

    // Cartão (placeholder — Stripe / outro provider futuro)
    public bool $payment_card_enabled = false;

    public function mount(): void
    {
        $this->ensureAdmin();
        $this->emola_api_key       = (string) Setting::get('emola_api_key', '');
        $this->emola_username      = (string) Setting::get('emola_username', '');
        $this->emola_password      = (string) Setting::get('emola_password', '');
        $this->emola_partner_code  = (string) Setting::get('emola_partner_code', '');
        $this->emola_base_url      = (string) Setting::get('emola_base_url', '');
        $this->emola_sandbox       = (bool) Setting::get('emola_sandbox', true);
        $this->payment_emola_enabled = (bool) Setting::get('payment_emola_enabled', true);

        $this->mpesa_api_key      = (string) Setting::get('mpesa_api_key', '');
        $this->mpesa_public_key   = (string) Setting::get('mpesa_public_key', '');
        $this->mpesa_service_provider_code = (string) Setting::get('mpesa_service_provider_code', '');
        $this->mpesa_sandbox      = (bool) Setting::get('mpesa_sandbox', true);
        $this->payment_mpesa_enabled = (bool) Setting::get('payment_mpesa_enabled', false);

        $this->payment_card_enabled = (bool) Setting::get('payment_card_enabled', false);
    }

    public function save(): void
    {
        $this->ensureAdmin();
        $this->validate([
            'emola_api_key'      => 'nullable|string|max:255',
            'emola_username'     => 'nullable|string|max:255',
            'emola_password'     => 'nullable|string|max:255',
            'emola_partner_code' => 'nullable|string|max:50',
            'emola_base_url'     => 'nullable|url|max:255',
            'mpesa_api_key'      => 'nullable|string|max:5000',
            'mpesa_public_key'   => 'nullable|string|max:5000',
            'mpesa_service_provider_code' => 'nullable|string|max:50',
        ]);

        $keys = [
            'emola_api_key', 'emola_username', 'emola_password', 'emola_partner_code',
            'emola_base_url', 'emola_sandbox', 'payment_emola_enabled',
            'mpesa_api_key', 'mpesa_public_key', 'mpesa_service_provider_code',
            'mpesa_sandbox', 'payment_mpesa_enabled',
            'payment_card_enabled',
        ];
        foreach ($keys as $k) {
            $val = $this->{$k};
            if (is_bool($val)) $val = $val ? '1' : '0';
            Setting::set($k, (string) $val);
        }

        session()->flash('message', 'Configurações de pagamento guardadas.');
    }

    protected function ensureAdmin(): void
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403);
        }
    }

    public function render()
    {
        $emolaReady = $this->emola_api_key && $this->emola_username && $this->emola_password && $this->emola_partner_code;
        $mpesaReady = $this->mpesa_api_key && $this->mpesa_public_key && $this->mpesa_service_provider_code;

        return view('livewire.admin.payment-settings', [
            'emolaReady' => $emolaReady,
            'mpesaReady' => $mpesaReady,
        ])->layout('components.layouts.admin');
    }
}
