<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <header class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Meus Pedidos</h1>
            <p class="text-sm text-gray-500 mt-1">Acompanha as tuas compras, faz reencomendas ou solicita devolução.</p>
        </header>

        @if (session('message'))
            <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-green-800">{{ session('message') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-red-800">{{ session('error') }}</div>
        @endif

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-6">
            <div class="flex flex-wrap gap-2">
                @php
                    $tabs = [
                        'all' => 'Todos',
                        'pending' => 'Aguardando pagamento',
                        'paid' => 'Pagos',
                        'cancelled' => 'Cancelados',
                    ];
                @endphp
                @foreach ($tabs as $key => $label)
                    <button type="button" wire:click="$set('tab', '{{ $key }}')"
                        class="px-4 py-2 rounded-full text-sm font-medium transition border
                        {{ $tab === $key
                            ? 'bg-gray-900 text-white border-gray-900'
                            : 'bg-white text-gray-700 border-gray-200 hover:border-gray-400' }}">
                        {{ $label }} <span class="ml-1 text-xs opacity-70">{{ $counts[$key] ?? 0 }}</span>
                    </button>
                @endforeach
            </div>
            <div class="relative">
                <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/></svg>
                <input wire:model.live.debounce.400ms="search" type="search" placeholder="Procurar por nº ou produto"
                    class="pl-9 pr-4 py-2 w-full md:w-72 rounded-full border border-gray-200 bg-white text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
            </div>
        </div>

        @forelse ($orders as $order)
            <div wire:key="order-{{ $order->id }}"
                 class="bg-white rounded-2xl border border-gray-200 mb-4 overflow-hidden hover:shadow-sm transition">
                <button type="button" wire:click="toggleOrder({{ $order->id }})"
                    class="w-full p-5 text-left flex items-start gap-4">
                    <div class="hidden sm:flex w-12 h-12 rounded-xl bg-emerald-50 text-emerald-700 items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-baseline gap-x-3 gap-y-1">
                            <span class="font-semibold text-gray-900">#{{ $order->order_number }}</span>
                            <span class="text-xs text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="mt-2 flex flex-wrap items-center gap-2 text-xs">
                            @php
                                $statusBadge = match ($order->status) {
                                    'completed' => 'bg-emerald-100 text-emerald-800',
                                    'processing' => 'bg-blue-100 text-blue-800',
                                    'cancelled' => 'bg-gray-100 text-gray-600',
                                    default => 'bg-amber-100 text-amber-800',
                                };
                                $payBadge = match ($order->payment_status) {
                                    'paid' => 'bg-emerald-100 text-emerald-800',
                                    'failed' => 'bg-red-100 text-red-800',
                                    default => 'bg-amber-100 text-amber-800',
                                };
                            @endphp
                            <span class="rounded-full px-2.5 py-0.5 font-medium {{ $statusBadge }}">{{ $order->status_label }}</span>
                            <span class="rounded-full px-2.5 py-0.5 font-medium {{ $payBadge }}">{{ $order->payment_status_label }}</span>
                            <span class="text-gray-500">{{ $order->payment_method_label }}</span>
                            <span class="text-gray-500">·</span>
                            <span class="text-gray-500">{{ $order->items->sum('quantity') }} {{ $order->items->sum('quantity') == 1 ? 'item' : 'itens' }}</span>
                        </div>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="text-lg font-bold text-gray-900">{{ number_format($order->total, 2, ',', '.') }} <span class="text-xs text-gray-500 font-medium">MZN</span></p>
                        <span class="inline-flex items-center text-emerald-700 text-xs mt-1">
                            {{ $expandedOrder === $order->id ? 'Recolher' : 'Detalhes' }}
                            <svg class="w-4 h-4 ml-1 transition-transform {{ $expandedOrder === $order->id ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </span>
                    </div>
                </button>

                @if ($expandedOrder === $order->id)
                    <div class="border-t border-gray-100 bg-gray-50/60 p-5">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="md:col-span-2 space-y-3">
                                <h3 class="text-sm font-semibold text-gray-700">Produtos</h3>
                                @foreach ($order->items as $item)
                                    <div class="flex items-center gap-3 bg-white rounded-xl border border-gray-100 p-3" wire:key="item-{{ $item->id }}">
                                        @if ($item->product?->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="" class="w-14 h-14 rounded-lg object-cover bg-gray-100">
                                        @else
                                            <div class="w-14 h-14 rounded-lg bg-gray-100 flex items-center justify-center text-gray-300">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                            </div>
                                        @endif
                                        <div class="flex-1 min-w-0">
                                            <p class="font-medium text-gray-900 truncate">{{ $item->product_name }}</p>
                                            <p class="text-xs text-gray-500 mt-0.5">{{ $item->quantity }} × {{ number_format($item->price, 2, ',', '.') }} MZN</p>
                                        </div>
                                        <p class="font-semibold text-gray-900 whitespace-nowrap">{{ number_format($item->subtotal, 2, ',', '.') }} MZN</p>
                                    </div>
                                @endforeach
                            </div>

                            <div class="space-y-4">
                                <div class="bg-white rounded-xl border border-gray-100 p-4 text-sm">
                                    <h3 class="font-semibold text-gray-700 mb-3">Entrega</h3>
                                    <p class="text-gray-900">{{ $order->customer_name }}</p>
                                    <p class="text-gray-500">{{ $order->customer_phone }}</p>
                                    <p class="text-gray-500 mt-1">{{ $order->customer_address ?: '—' }}</p>
                                </div>

                                <div class="bg-white rounded-xl border border-gray-100 p-4 text-sm">
                                    <h3 class="font-semibold text-gray-700 mb-3">Pagamento</h3>
                                    <div class="flex justify-between text-gray-500"><span>Subtotal</span><span class="text-gray-900">{{ number_format($order->subtotal, 2, ',', '.') }} MZN</span></div>
                                    <div class="flex justify-between text-gray-500 mt-1"><span>Método</span><span class="text-gray-900">{{ $order->payment_method_label }}</span></div>
                                    @if ($order->transaction_id)
                                        <div class="flex justify-between text-gray-500 mt-1"><span>Referência</span><span class="text-gray-900 font-mono text-xs">{{ \Illuminate\Support\Str::limit($order->transaction_id, 18) }}</span></div>
                                    @endif
                                    <div class="flex justify-between font-semibold text-gray-900 mt-3 pt-3 border-t border-gray-100"><span>Total</span><span>{{ number_format($order->total, 2, ',', '.') }} MZN</span></div>
                                </div>

                                <div class="flex flex-wrap gap-2">
                                    @if (in_array($order->status, ['pending', 'processing']) && $order->payment_status !== 'paid')
                                        <button type="button" wire:click="cancelOrder({{ $order->id }})"
                                            wire:confirm="Cancelar este pedido?"
                                            class="px-3 py-2 rounded-lg text-sm bg-red-50 text-red-700 hover:bg-red-100 transition font-medium">
                                            Cancelar pedido
                                        </button>
                                    @endif
                                    @if ($order->payment_status === 'paid' && $order->status !== 'cancelled')
                                        @php $activeReturn = $order->returns->whereIn('status', ['pending', 'approved'])->first(); @endphp
                                        @if ($activeReturn)
                                            <span class="px-3 py-2 rounded-lg text-sm bg-amber-50 text-amber-800 font-medium inline-flex items-center gap-1.5">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                Devolução {{ $activeReturn->status_label }}
                                            </span>
                                        @else
                                            <a href="{{ route('return.request', $order->id) }}"
                                                class="px-3 py-2 rounded-lg text-sm bg-gray-100 text-gray-800 hover:bg-gray-200 transition font-medium">
                                                Solicitar devolução
                                            </a>
                                        @endif
                                    @endif
                                    <a href="{{ route('order.success', $order->id) }}"
                                        class="px-3 py-2 rounded-lg text-sm bg-emerald-600 text-white hover:bg-emerald-700 transition font-medium">
                                        Ver detalhes
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @empty
            <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center">
                <div class="w-20 h-20 mx-auto rounded-full bg-gray-100 flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
                <h2 class="text-lg font-semibold text-gray-900">Nenhum pedido aqui</h2>
                <p class="text-sm text-gray-500 mt-1">Quando fizeres uma encomenda, ela aparece nesta página.</p>
                <a href="{{ route('products') }}" class="inline-flex items-center mt-5 px-5 py-2.5 rounded-xl bg-emerald-600 text-white font-medium hover:bg-emerald-700 transition">
                    Explorar produtos
                </a>
            </div>
        @endforelse

        @if ($orders->hasPages())
            <div class="mt-6">{{ $orders->links() }}</div>
        @endif

    </div>
</div>
