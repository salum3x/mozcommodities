<div>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Minhas Vendas</h2>
                <p class="mt-1 text-sm text-gray-600">Acompanhe todas as vendas dos seus produtos</p>
            </div>
        </div>

        @if(!$supplier)
            <!-- Alerta: Perfil não configurado -->
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            <strong>Perfil incompleto!</strong> Complete o seu perfil de fornecedor para visualizar vendas.
                        </p>
                    </div>
                </div>
            </div>
        @else
            <!-- Stats Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Total de Vendas -->
                <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Vendas</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total_sales'] }}</p>
                        </div>
                        <div class="p-3 bg-green-100 rounded-xl">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Receita Total -->
                <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Receita Total</p>
                            <p class="text-2xl font-bold text-green-600 mt-1">{{ number_format($stats['total_revenue'], 2, ',', '.') }} MT</p>
                        </div>
                        <div class="p-3 bg-emerald-100 rounded-xl">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Pedidos Pendentes -->
                <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Pendentes</p>
                            <p class="text-2xl font-bold text-yellow-600 mt-1">{{ $stats['pending_orders'] }}</p>
                        </div>
                        <div class="p-3 bg-yellow-100 rounded-xl">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Pedidos Concluídos -->
                <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Concluidos</p>
                            <p class="text-2xl font-bold text-blue-600 mt-1">{{ $stats['completed_orders'] }}</p>
                        </div>
                        <div class="p-3 bg-blue-100 rounded-xl">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="flex flex-col sm:flex-row gap-4">
                    <!-- Search -->
                    <div class="flex-1">
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Pesquisar por pedido, cliente..." class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <select wire:model.live="statusFilter" class="w-full sm:w-auto px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="">Todos os status</option>
                            <option value="pending">Pendente</option>
                            <option value="processing">Em Processamento</option>
                            <option value="completed">Concluido</option>
                            <option value="cancelled">Cancelado</option>
                        </select>
                    </div>

                    <!-- Date Filter -->
                    <div>
                        <select wire:model.live="dateFilter" class="w-full sm:w-auto px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="">Todo o periodo</option>
                            <option value="today">Hoje</option>
                            <option value="week">Esta semana</option>
                            <option value="month">Este mes</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Orders Table -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                @if($orders->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pedido</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Cliente</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Produtos</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Valor</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pagamento</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Data</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Acoes</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($orders as $order)
                                    @php
                                        $supplierTotal = $order->items->sum('subtotal');
                                        $supplierItemsCount = $order->items->count();
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <span class="font-mono text-sm font-semibold text-gray-900">#{{ $order->order_number }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $order->customer_name }}</p>
                                                <p class="text-xs text-gray-500">{{ $order->customer_email }}</p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm text-gray-600">{{ $supplierItemsCount }} {{ $supplierItemsCount === 1 ? 'produto' : 'produtos' }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="font-semibold text-green-600">{{ number_format($supplierTotal, 2, ',', '.') }} MT</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @php
                                                $statusColors = [
                                                    'pending' => 'bg-yellow-100 text-yellow-700',
                                                    'processing' => 'bg-blue-100 text-blue-700',
                                                    'completed' => 'bg-green-100 text-green-700',
                                                    'cancelled' => 'bg-red-100 text-red-700',
                                                ];
                                                $statusLabels = [
                                                    'pending' => 'Pendente',
                                                    'processing' => 'Processando',
                                                    'completed' => 'Concluido',
                                                    'cancelled' => 'Cancelado',
                                                ];
                                            @endphp
                                            <span class="px-2.5 py-1 text-xs font-medium rounded-full {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-700' }}">
                                                {{ $statusLabels[$order->status] ?? $order->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($order->payment_status === 'paid')
                                                <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">Pago</span>
                                            @elseif($order->payment_status === 'pending')
                                                <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-700">Aguardando</span>
                                            @else
                                                <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700">Falhou</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm text-gray-600">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <button wire:click="viewOrder({{ $order->id }})" class="p-2 text-green-600 hover:text-green-800 hover:bg-green-50 rounded-lg transition" title="Ver detalhes">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $orders->links() }}
                    </div>
                @else
                    <div class="px-6 py-16 text-center">
                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma venda encontrada</h3>
                        <p class="text-gray-500">
                            @if($search || $statusFilter || $dateFilter)
                                Nenhuma venda corresponde aos filtros selecionados.
                            @else
                                Quando os clientes comprarem os seus produtos, as vendas aparecerão aqui.
                            @endif
                        </p>
                    </div>
                @endif
            </div>
        @endif
    </div>

    <!-- Order Details Modal -->
    @if($showOrderModal && $selectedOrder)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <div class="bg-white px-6 pt-6 pb-4">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Detalhes do Pedido</h3>
                                <p class="text-sm text-gray-500 font-mono">#{{ $selectedOrder->order_number }}</p>
                            </div>
                            <button wire:click="closeModal" class="p-2 text-gray-400 hover:text-gray-600 rounded-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        <!-- Customer Info -->
                        <div class="bg-gray-50 rounded-xl p-4 mb-6">
                            <h4 class="text-sm font-semibold text-gray-700 mb-3">Informacoes do Cliente</h4>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p class="text-gray-500">Nome</p>
                                    <p class="font-medium text-gray-900">{{ $selectedOrder->customer_name }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Email</p>
                                    <p class="font-medium text-gray-900">{{ $selectedOrder->customer_email }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Telefone</p>
                                    <p class="font-medium text-gray-900">{{ $selectedOrder->customer_phone }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Data do Pedido</p>
                                    <p class="font-medium text-gray-900">{{ $selectedOrder->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                            @if($selectedOrder->customer_address)
                                <div class="mt-3">
                                    <p class="text-gray-500 text-sm">Endereco</p>
                                    <p class="font-medium text-gray-900 text-sm">{{ $selectedOrder->customer_address }}</p>
                                </div>
                            @endif
                        </div>

                        <!-- Products (Only supplier's products) -->
                        <div class="mb-6">
                            <h4 class="text-sm font-semibold text-gray-700 mb-3">Seus Produtos neste Pedido</h4>
                            <div class="border rounded-xl overflow-hidden">
                                <table class="w-full">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Produto</th>
                                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Qtd</th>
                                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Preco</th>
                                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach($selectedOrder->items as $item)
                                            <tr>
                                                <td class="px-4 py-3">
                                                    <div class="flex items-center gap-3">
                                                        @if($item->product && $item->product->image)
                                                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product_name }}" class="w-10 h-10 rounded-lg object-cover">
                                                        @else
                                                            <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center">
                                                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                                </svg>
                                                            </div>
                                                        @endif
                                                        <span class="font-medium text-gray-900">{{ $item->product_name }}</span>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3 text-center text-gray-600">{{ $item->quantity }}</td>
                                                <td class="px-4 py-3 text-right text-gray-600">{{ number_format($item->price, 2, ',', '.') }} MT</td>
                                                <td class="px-4 py-3 text-right font-semibold text-gray-900">{{ number_format($item->subtotal, 2, ',', '.') }} MT</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-gray-50">
                                        <tr>
                                            <td colspan="3" class="px-4 py-3 text-right font-semibold text-gray-700">Total dos seus produtos:</td>
                                            <td class="px-4 py-3 text-right font-bold text-green-600">{{ number_format($selectedOrder->items->sum('subtotal'), 2, ',', '.') }} MT</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <!-- Payment & Status -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gray-50 rounded-xl p-4">
                                <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Status do Pedido</p>
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-700',
                                        'processing' => 'bg-blue-100 text-blue-700',
                                        'completed' => 'bg-green-100 text-green-700',
                                        'cancelled' => 'bg-red-100 text-red-700',
                                    ];
                                    $statusLabels = [
                                        'pending' => 'Pendente',
                                        'processing' => 'Processando',
                                        'completed' => 'Concluido',
                                        'cancelled' => 'Cancelado',
                                    ];
                                @endphp
                                <span class="px-3 py-1 text-sm font-medium rounded-full {{ $statusColors[$selectedOrder->status] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ $statusLabels[$selectedOrder->status] ?? $selectedOrder->status }}
                                </span>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-4">
                                <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Pagamento</p>
                                <div class="flex items-center gap-2">
                                    @if($selectedOrder->payment_status === 'paid')
                                        <span class="px-3 py-1 text-sm font-medium rounded-full bg-green-100 text-green-700">Pago</span>
                                    @elseif($selectedOrder->payment_status === 'pending')
                                        <span class="px-3 py-1 text-sm font-medium rounded-full bg-yellow-100 text-yellow-700">Aguardando</span>
                                    @else
                                        <span class="px-3 py-1 text-sm font-medium rounded-full bg-red-100 text-red-700">Falhou</span>
                                    @endif
                                    <span class="text-sm text-gray-500">({{ $selectedOrder->payment_method_label }})</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-6 py-4 flex justify-end">
                        <button wire:click="closeModal" class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition font-medium">
                            Fechar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
