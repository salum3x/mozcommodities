<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderReturn extends Model
{
    protected $fillable = [
        'order_id',
        'user_id',
        'return_number',
        'reason',
        'description',
        'status',
        'admin_notes',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getReasonLabelAttribute(): string
    {
        return match ($this->reason) {
            'defeito' => 'Produto com defeito',
            'quantidade_errada' => 'Quantidade errada',
            'produto_errado' => 'Produto errado',
            'nao_corresponde' => 'Não corresponde à descrição',
            'outro' => 'Outro motivo',
            default => $this->reason,
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Em análise',
            'approved' => 'Aprovada',
            'rejected' => 'Recusada',
            'refunded' => 'Reembolsada',
            default => $this->status,
        };
    }
}
