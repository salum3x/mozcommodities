<?php

namespace App\Livewire\Public;

use App\Models\Order;
use App\Models\OrderReturn;
use Livewire\Attributes\Locked;
use Livewire\Component;

class RequestReturn extends Component
{
    #[Locked]
    public int $orderId;

    public string $reason = '';
    public string $description = '';
    public bool $submitted = false;

    public function mount(int $order)
    {
        $loaded = Order::with('items.product')
            ->where('id', $order)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($loaded->payment_status !== 'paid') {
            abort(403, 'Apenas pedidos pagos podem ser devolvidos.');
        }
        if ($loaded->status === 'cancelled') {
            abort(403, 'Não é possível solicitar devolução de pedidos cancelados.');
        }
        if ($loaded->returns()->whereIn('status', ['pending', 'approved'])->exists()) {
            session()->flash('error', 'Já existe uma solicitação de devolução em curso para este pedido.');
            $this->submitted = true;
        }

        $this->orderId = $loaded->id;
    }

    public function submit(): void
    {
        $this->validate([
            'reason' => 'required|in:defeito,quantidade_errada,produto_errado,nao_corresponde,outro',
            'description' => 'required|string|min:15|max:1000',
        ], [
            'reason.required' => 'Seleciona um motivo.',
            'description.required' => 'Descreve o problema.',
            'description.min' => 'A descrição precisa de pelo menos 15 caracteres.',
            'description.max' => 'A descrição é demasiado longa.',
        ]);

        $order = Order::where('id', $this->orderId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($order->payment_status !== 'paid' || $order->status === 'cancelled') {
            abort(403);
        }
        if ($order->returns()->whereIn('status', ['pending', 'approved'])->exists()) {
            session()->flash('error', 'Já existe uma solicitação em curso.');
            return;
        }

        OrderReturn::create([
            'order_id' => $order->id,
            'user_id' => auth()->id(),
            'return_number' => 'DEV-' . strtoupper(bin2hex(random_bytes(6))),
            'reason' => $this->reason,
            'description' => $this->description,
            'status' => 'pending',
        ]);

        $this->submitted = true;
        session()->flash('message', 'Solicitação de devolução enviada. Vamos analisar e responder em até 48h.');
    }

    public function render()
    {
        $order = Order::with('items.product', 'returns')->findOrFail($this->orderId);

        return view('livewire.public.request-return', [
            'order' => $order,
        ])->layout('components.layouts.shop');
    }
}
