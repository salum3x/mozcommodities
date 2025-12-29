<div>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Relatorios</h2>
                <p class="mt-1 text-sm text-gray-600">Analise o desempenho das suas vendas</p>
            </div>

            @if($supplier)
                <!-- Period Filter -->
                <div class="flex items-center gap-2 bg-white rounded-lg border border-gray-200 p-1">
                    <button wire:click="$set('period', 'week')" class="px-4 py-2 text-sm font-medium rounded-md transition {{ $period === 'week' ? 'bg-green-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                        Semana
                    </button>
                    <button wire:click="$set('period', 'month')" class="px-4 py-2 text-sm font-medium rounded-md transition {{ $period === 'month' ? 'bg-green-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                        Mes
                    </button>
                    <button wire:click="$set('period', 'year')" class="px-4 py-2 text-sm font-medium rounded-md transition {{ $period === 'year' ? 'bg-green-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                        Ano
                    </button>
                    <button wire:click="$set('period', 'all')" class="px-4 py-2 text-sm font-medium rounded-md transition {{ $period === 'all' ? 'bg-green-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                        Tudo
                    </button>
                </div>
            @endif
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
                            <strong>Perfil incompleto!</strong> Complete o seu perfil de fornecedor para visualizar relatorios.
                        </p>
                    </div>
                </div>
            </div>
        @else
            <!-- Stats Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Receita Total -->
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-5 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-xs font-medium uppercase tracking-wide">Receita</p>
                            <p class="text-2xl font-bold mt-1">{{ number_format($stats['total_revenue'], 2, ',', '.') }} MT</p>
                            @if($stats['revenue_change'] != 0)
                                <div class="flex items-center mt-2">
                                    @if($stats['revenue_change'] > 0)
                                        <svg class="w-4 h-4 text-green-200" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-xs text-green-200">+{{ $stats['revenue_change'] }}%</span>
                                    @else
                                        <svg class="w-4 h-4 text-red-200" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-xs text-red-200">{{ $stats['revenue_change'] }}%</span>
                                    @endif
                                    <span class="text-xs text-green-200 ml-1">vs periodo anterior</span>
                                </div>
                            @endif
                        </div>
                        <div class="p-3 bg-white/20 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Itens Vendidos -->
                <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Itens Vendidos</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_items_sold'], 0, ',', '.') }}</p>
                        </div>
                        <div class="p-3 bg-blue-100 rounded-xl">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total de Pedidos -->
                <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Pedidos</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total_orders'] }}</p>
                        </div>
                        <div class="p-3 bg-purple-100 rounded-xl">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Valor Médio -->
                <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Valor Medio</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['average_order_value'], 2, ',', '.') }} MT</p>
                        </div>
                        <div class="p-3 bg-amber-100 rounded-xl">
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Stats Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-gray-100 rounded-lg">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Total Produtos</p>
                            <p class="text-lg font-bold text-gray-900">{{ $productStats['total_products'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Activos</p>
                            <p class="text-lg font-bold text-green-600">{{ $productStats['active_products'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-yellow-100 rounded-lg">
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Pendentes</p>
                            <p class="text-lg font-bold text-yellow-600">{{ $productStats['pending_approval'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-red-100 rounded-lg">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Sem Stock</p>
                            <p class="text-lg font-bold text-red-600">{{ $productStats['out_of_stock'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid lg:grid-cols-2 gap-6">
                <!-- Top Products -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900">Produtos Mais Vendidos</h3>
                    </div>
                    @if($topProducts->count() > 0)
                        <div class="divide-y divide-gray-100">
                            @foreach($topProducts as $index => $product)
                                <div class="px-6 py-4 flex items-center gap-4">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold
                                        {{ $index === 0 ? 'bg-yellow-100 text-yellow-700' : ($index === 1 ? 'bg-gray-100 text-gray-600' : ($index === 2 ? 'bg-amber-100 text-amber-700' : 'bg-gray-50 text-gray-500')) }}">
                                        {{ $index + 1 }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium text-gray-900 truncate">{{ $product->product_name }}</p>
                                        <p class="text-sm text-gray-500">{{ number_format($product->total_quantity, 0, ',', '.') }} unidades</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-semibold text-green-600">{{ number_format($product->total_revenue, 2, ',', '.') }} MT</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="px-6 py-12 text-center">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            <p class="text-gray-500">Nenhuma venda registada ainda.</p>
                        </div>
                    @endif
                </div>

                <!-- Sales Chart -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900">Vendas por Mes</h3>
                    </div>
                    @if($salesByMonth->count() > 0)
                        <div class="p-6">
                            <div class="space-y-4">
                                @php
                                    $maxValue = $salesByMonth->max('total') ?: 1;
                                @endphp
                                @foreach($salesByMonth as $sale)
                                    <div>
                                        <div class="flex items-center justify-between mb-1">
                                            <span class="text-sm font-medium text-gray-600">{{ $sale['label'] }}</span>
                                            <span class="text-sm font-bold text-gray-900">{{ number_format($sale['total'], 2, ',', '.') }} MT</span>
                                        </div>
                                        <div class="w-full bg-gray-100 rounded-full h-2.5">
                                            <div class="bg-gradient-to-r from-green-500 to-green-600 h-2.5 rounded-full" style="width: {{ ($sale['total'] / $maxValue) * 100 }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="px-6 py-12 text-center">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            <p class="text-gray-500">Sem dados de vendas para exibir.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Sales -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Ultimas Vendas</h3>
                    <a href="{{ route('supplier.sales') }}" class="text-sm text-green-600 hover:text-green-700 font-medium">Ver todas</a>
                </div>
                @if($recentSales->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Produto</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Cliente</th>
                                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Qtd</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Valor</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Data</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($recentSales as $sale)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            <span class="font-medium text-gray-900">{{ $sale->product_name }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-gray-600">{{ $sale->order->customer_name }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="text-gray-900">{{ $sale->quantity }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <span class="font-semibold text-green-600">{{ number_format($sale->subtotal, 2, ',', '.') }} MT</span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <span class="text-sm text-gray-500">{{ $sale->created_at->format('d/m/Y H:i') }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="px-6 py-12 text-center">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        <p class="text-gray-500">Nenhuma venda registada ainda.</p>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
