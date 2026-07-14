<div class="p-6 max-w-7xl mx-auto">
    <header class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Pedidos</h1>
        <p class="text-sm text-gray-500 mt-1">Gere os pedidos. Aprova manualmente pedidos pendentes que ficaram presos no gateway.</p>
    </header>

    @if (session('message'))
        <div class="mb-4 rounded-lg bg-emerald-50 border border-emerald-200 px-4 py-3 text-emerald-800">{{ session('message') }}</div>
    @endif
    @if (session('error'))
        <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-red-800">{{ session('error') }}</div>
    @endif

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-5">
        <div class="flex flex-wrap gap-2">
            @php
                $tabs = [
                    'pending' => 'Pendentes',
                    'paid' => 'Pagos',
                    'processing' => 'Em processamento',
                    'failed' => 'Falhados',
                    'cancelled' => 'Cancelados',
                ];
            @endphp
            @foreach ($tabs as $key => $label)
                <button type="button" wire:click="$set('filter', '{{ $key }}')"
                    class="px-4 py-2 rounded-full text-sm font-medium transition border
                    {{ $filter === $key ? 'bg-gray-900 text-white border-gray-900' : 'bg-white text-gray-700 border-gray-200 hover:border-gray-400' }}">
                    {{ $label }} <span class="ml-1 text-xs opacity-70">{{ $counts[$key] ?? 0 }}</span>
                </button>
            @endforeach
        </div>
        <div class="relative">
            <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/></svg>
            <input wire:model.live.debounce.400ms="search" type="search" placeholder="Nº pedido, email, telefone, transação"
                class="pl-9 pr-4 py-2 w-full md:w-80 rounded-full border border-gray-200 bg-white text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
        </div>
    </div>

    @forelse ($orders as $order)
        <div wire:key="ord-{{ $order->id }}" class="bg-white rounded-2xl border border-gray-200 mb-3 overflow-hidden">
            <button type="button" wire:click="expand({{ $order->id }})" class="w-full p-4 flex items-start gap-4 text-left hover:bg-gray-50/60">
                <div class="hidden sm:flex w-10 h-10 rounded-lg bg-blue-50 text-blue-700 items-center justify-center flex-shrink-0 font-bold">
                    {{ \Illuminate\Support\Str::limit(str_replace('ORD-', '', $order->order_number), 3, '') }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-baseline gap-x-3 gap-y-1">
                        <span class="font-mono text-gray-900 font-semibold">#{{ $order->order_number }}</span>
                        <span class="text-xs text-gray-500">{{ $order->created_at->diffForHumans() }}</span>
                        <span class="text-xs text-gray-500">{{ $order->customer_email }}</span>
                    </div>
                    <p class="text-sm text-gray-600 mt-1">{{ $order->items->count() }} item(s) · {{ $order->payment_method_label }}</p>
                </div>
                <div class="text-right flex-shrink-0">
                    @php
                        $payClass = match($order->payment_status){
                            'paid' => 'bg-emerald-100 text-emerald-800',
                            'failed' => 'bg-red-100 text-red-800',
                            default => 'bg-amber-100 text-amber-800',
                        };
                        $statClass = match($order->status){
                            'completed' => 'bg-emerald-100 text-emerald-800',
                            'processing' => 'bg-blue-100 text-blue-800',
                            'cancelled' => 'bg-gray-200 text-gray-700',
                            default => 'bg-amber-100 text-amber-800',
                        };
                    @endphp
                    <p class="font-bold text-gray-900">{{ number_format($order->total, 2, ',', '.') }} MZN</p>
                    <div class="flex gap-1.5 justify-end mt-1">
                        <span class="rounded-full px-2 py-0.5 text-[10px] font-medium {{ $payClass }}">{{ $order->payment_status_label }}</span>
                        <span class="rounded-full px-2 py-0.5 text-[10px] font-medium {{ $statClass }}">{{ $order->status_label }}</span>
                    </div>
                </div>
            </button>

            @if ($expanded === $order->id)
                <div class="border-t border-gray-100 p-5 bg-gray-50/60">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div class="md:col-span-2 space-y-3">
                            <h3 class="text-sm font-semibold text-gray-700">Items</h3>
                            @foreach ($order->items as $item)
                                <div class="flex items-center gap-3 bg-white rounded-xl border border-gray-100 p-3">
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium text-gray-900">{{ $item->product_name }}</p>
                                        <p class="text-xs text-gray-500">{{ $item->quantity }} × {{ number_format($item->price, 2, ',', '.') }} MZN</p>
                                    </div>
                                    <p class="font-semibold whitespace-nowrap">{{ number_format($item->subtotal, 2, ',', '.') }} MZN</p>
                                </div>
                            @endforeach

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1 mt-3">Nota administrativa</label>
                                <textarea wire:model="adminNote" rows="2" placeholder="Justifica ações manuais (ex: cliente confirmou pelo telefone, comprovativo recebido, etc.)"
                                    class="w-full rounded-xl border-gray-200 focus:ring-emerald-500 focus:border-emerald-500 text-sm"></textarea>
                            </div>

                            <div class="flex flex-wrap gap-2 pt-2">
                                @if ($order->payment_status === 'pending')
                                    <button type="button" wire:click="markAsPaid({{ $order->id }})"
                                        wire:confirm="Marcar este pedido como PAGO manualmente?"
                                        class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700 transition">
                                        ✓ Aprovar pagamento (marcar como pago)
                                    </button>
                                    <button type="button" wire:click="markAsFailed({{ $order->id }})"
                                        class="px-4 py-2 rounded-lg bg-red-50 text-red-700 text-sm font-medium hover:bg-red-100 transition">
                                        Marcar como falhou
                                    </button>
                                @endif

                                @if ($order->payment_status === 'paid' && $order->status === 'pending')
                                    <button type="button" wire:click="advanceStatus({{ $order->id }}, 'processing')"
                                        class="px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 transition">
                                        Marcar em processamento
                                    </button>
                                @endif

                                @if ($order->status === 'processing')
                                    <button type="button" wire:click="advanceStatus({{ $order->id }}, 'completed')"
                                        class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700 transition">
                                        Marcar concluído
                                    </button>
                                @endif

                                @if (!in_array($order->status, ['cancelled', 'completed']))
                                    <button type="button" wire:click="advanceStatus({{ $order->id }}, 'cancelled')"
                                        wire:confirm="Cancelar este pedido?"
                                        class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700 text-sm font-medium hover:bg-gray-200 transition">
                                        Cancelar pedido
                                    </button>
                                @endif
                            </div>
                        </div>

                        <div class="space-y-3 text-sm">
                            <div class="bg-white rounded-xl border border-gray-100 p-4">
                                <h3 class="font-semibold text-gray-700 mb-2">Cliente</h3>
                                <p class="text-gray-900">{{ $order->customer_name }}</p>
                                <p class="text-gray-500">{{ $order->customer_email }}</p>
                                <p class="text-gray-500">{{ $order->customer_phone }}</p>
                                @if ($order->customer_address)
                                    <p class="text-gray-500 mt-1">{{ $order->customer_address }}</p>
                                @endif
                            </div>
                            <div class="bg-white rounded-xl border border-gray-100 p-4">
                                <h3 class="font-semibold text-gray-700 mb-2">Pagamento</h3>
                                <p>Método: <strong>{{ $order->payment_method_label }}</strong></p>
                                @if ($order->transaction_id)
                                    <p class="text-xs text-gray-600 mt-1">Tx: <span class="font-mono break-all">{{ $order->transaction_id }}</span></p>
                                @endif
                                @if ($order->paid_at)
                                    <p class="text-xs text-emerald-700 mt-1">Pago em {{ $order->paid_at->format('d/m/Y H:i') }}</p>
                                @endif
                                @if ($order->payment_proof)
                                    <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank" class="text-xs text-blue-700 hover:underline mt-1 inline-block">Ver comprovativo →</a>
                                @endif
                                <div class="flex justify-between mt-2 pt-2 border-t border-gray-100 font-semibold text-gray-900">
                                    <span>Total</span><span>{{ number_format($order->total, 2, ',', '.') }} MZN</span>
                                </div>
                            </div>
                            @if ($order->notes)
                                <div class="bg-white rounded-xl border border-gray-100 p-4">
                                    <h3 class="font-semibold text-gray-700 mb-1">Histórico de notas</h3>
                                    <pre class="text-xs text-gray-700 whitespace-pre-wrap font-sans">{{ $order->notes }}</pre>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @empty
        <div class="bg-white rounded-2xl border border-gray-100 p-10 text-center">
            <p class="text-gray-500">Nenhum pedido nesta categoria.</p>
        </div>
    @endforelse

    @if ($orders->hasPages())
        <div class="mt-4">{{ $orders->links() }}</div>
    @endif
</div>
