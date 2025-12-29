<?php

namespace App\Livewire\Public;

use Livewire\Component;
use App\Models\ProductRequest as ProductRequestModel;

class ProductRequest extends Component
{
    public $name = '';
    public $email = '';
    public $phone = '';
    public $product_name = '';
    public $description = '';
    public $quantity_kg = '';
    public $showSuccessMessage = false;

    public function submit()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'product_name' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'quantity_kg' => 'nullable|integer|min:1',
        ], [
            'name.required' => 'Por favor, insira o seu nome',
            'email.required' => 'Por favor, insira o seu email',
            'email.email' => 'Por favor, insira um email válido',
            'phone.required' => 'Por favor, insira o seu telefone',
            'product_name.required' => 'Por favor, insira o nome do produto',
            'description.required' => 'Por favor, descreva o produto que procura',
            'description.min' => 'A descrição deve ter pelo menos 10 caracteres',
            'quantity_kg.integer' => 'A quantidade deve ser um número inteiro',
            'quantity_kg.min' => 'A quantidade deve ser maior que 0',
        ]);

        ProductRequestModel::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'product_name' => $this->product_name,
            'description' => $this->description,
            'quantity_kg' => $this->quantity_kg,
            'status' => 'pending',
        ]);

        $this->showSuccessMessage = true;
        $this->reset(['name', 'email', 'phone', 'product_name', 'description', 'quantity_kg']);

        // Hide success message after 5 seconds
        $this->dispatch('request-submitted');
    }

    public function render()
    {
        return view('livewire.public.product-request')->layout('components.layouts.shop');
    }
}
