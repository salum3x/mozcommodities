<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Setting;

class Settings extends Component
{
    public $company_name;
    public $company_email;
    public $company_phone;
    public $company_whatsapp;
    public $company_address;
    public $bank_name;
    public $bank_nib;
    public $bank_account_holder;
    public $mpesa_number;

    public function mount()
    {
        $this->company_name = Setting::get('company_name', '');
        $this->company_email = Setting::get('company_email', '');
        $this->company_phone = Setting::get('company_phone', '');
        $this->company_whatsapp = Setting::get('company_whatsapp', '');
        $this->company_address = Setting::get('company_address', '');
        $this->bank_name = Setting::get('bank_name', '');
        $this->bank_nib = Setting::get('bank_nib', '');
        $this->bank_account_holder = Setting::get('bank_account_holder', '');
        $this->mpesa_number = Setting::get('mpesa_number', '');
    }

    public function save()
    {
        $this->validate([
            'company_name' => 'required|string|max:255',
            'company_email' => 'required|email|max:255',
            'company_phone' => 'required|string|max:20',
            'company_whatsapp' => 'required|string|max:20',
            'company_address' => 'required|string|max:500',
            'bank_name' => 'required|string|max:255',
            'bank_nib' => 'required|string|max:255',
            'bank_account_holder' => 'required|string|max:255',
            'mpesa_number' => 'required|string|max:20',
        ]);

        Setting::set('company_name', $this->company_name);
        Setting::set('company_email', $this->company_email);
        Setting::set('company_phone', $this->company_phone);
        Setting::set('company_whatsapp', $this->company_whatsapp);
        Setting::set('company_address', $this->company_address);
        Setting::set('bank_name', $this->bank_name);
        Setting::set('bank_nib', $this->bank_nib);
        Setting::set('bank_account_holder', $this->bank_account_holder);
        Setting::set('mpesa_number', $this->mpesa_number);

        session()->flash('message', 'Configurações atualizadas com sucesso!');
    }

    public function render()
    {
        return view('livewire.admin.settings');
    }
}
