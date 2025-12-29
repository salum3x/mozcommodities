<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'subtotal',
        'total',
        'payment_method',
        'payment_status',
        'status',
        'payment_proof',
        'transaction_id',
        'payment_reference',
        'payment_gateway',
        'payment_response',
        'paid_at',
        'notes',
    ];

    protected $casts = [
        'payment_response' => 'array',
        'paid_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    public function isPending(): bool
    {
        return $this->payment_status === 'pending';
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return match($this->payment_method) {
            'mpesa' => 'M-Pesa',
            'emola' => 'e-Mola',
            'card' => 'Cartao',
            'bank_transfer' => 'Transferencia Bancaria',
            default => $this->payment_method,
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Pendente',
            'processing' => 'Em Processamento',
            'completed' => 'Concluido',
            'cancelled' => 'Cancelado',
            default => $this->status,
        };
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        return match($this->payment_status) {
            'pending' => 'Aguardando Pagamento',
            'paid' => 'Pago',
            'failed' => 'Falhou',
            default => $this->payment_status,
        };
    }
}
