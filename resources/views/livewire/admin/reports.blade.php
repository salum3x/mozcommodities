<div class="p-6 max-w-7xl mx-auto">
    <header class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Relatórios</h1>
            <p class="text-sm text-gray-500 mt-1">Período: <strong>{{ $fromLabel }} → {{ $toLabel }}</strong> · Apenas pedidos pagos.</p>
        </div>
        <button type="button" wire:click="exportCsv" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4"/></svg>
            Exportar Excel
        </button>
    </header>

    <!-- Filtros -->
    <div class="bg-white rounded-2xl border border-gray-200 p-4 mb-5 flex flex-wrap items-center gap-3">
        <button wire:click="setPeriod('week')" class="px-3 py-1.5 rounded-md text-sm bg-gray-100 hover:bg-gray-200">Esta semana</button>
        <button wire:click="setPeriod('month')" class="px-3 py-1.5 rounded-md text-sm bg-gray-100 hover:bg-gray-200">Este mês</button>
        <button wire:click="setPeriod('year')" class="px-3 py-1.5 rounded-md text-sm bg-gray-100 hover:bg-gray-200">Este ano</button>
        <span class="mx-2 text-gray-300">|</span>
        <input type="date" wire:model.live="dateFrom" class="rounded-md border-gray-300 text-sm">
        <span class="text-gray-400">→</span>
        <input type="date" wire:model.live="dateTo" class="rounded-md border-gray-300 text-sm">
        <span class="mx-2 text-gray-300">|</span>
        <select wire:model.live="supplierId" class="rounded-md border-gray-300 text-sm">
            <option value="">Todos os fornecedores</option>
            @foreach ($suppliers as $s)
                <option value="{{ $s->id }}">{{ $s->company_name }}</option>
            @endforeach
        </select>
    </div>

    <!-- KPIs -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wide">Receita</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totals['revenue'], 2, ',', '.') }} <span class="text-sm font-medium text-gray-500">MZN</span></p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wide">Pedidos</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totals['orders'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wide">Volume (kg)</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totals['qty'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wide">Fornecedores</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totals['suppliers'] }}</p>
        </div>
    </div>

    <!-- Top produtos vendidos -->
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden mb-6">
        <div class="p-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-bold text-gray-900">Produtos mais vendidos</h2>
            @if ($supplierId)<span class="text-xs text-gray-500">filtrado por fornecedor selecionado</span>@endif
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 text-xs uppercase">
                <tr>
                    <th class="text-left px-4 py-3">#</th>
                    <th class="text-left px-4 py-3">Produto</th>
                    <th class="text-right px-4 py-3">Vendas</th>
                    <th class="text-right px-4 py-3">% do total</th>
                    <th class="text-right px-4 py-3">Quantidade</th>
                    <th class="text-right px-4 py-3">Preço médio</th>
                    <th class="text-right px-4 py-3">Pedidos</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($topProducts as $i => $row)
                    @php $pct = $totals['revenue'] > 0 ? ($row->total_revenue / $totals['revenue']) * 100 : 0; @endphp
                    <tr>
                        <td class="px-4 py-3 text-gray-500">{{ $i + 1 }}</td>
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $row->product_name }}</td>
                        <td class="px-4 py-3 text-right font-semibold">{{ number_format($row->total_revenue, 2, ',', '.') }} MZN</td>
                        <td class="px-4 py-3 text-right">
                            <div class="inline-flex items-center gap-2">
                                <div class="w-12 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-blue-500" style="width: {{ $pct }}%"></div>
                                </div>
                                <span class="text-xs text-gray-600">{{ number_format($pct, 1, ',', '.') }}%</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-right">{{ number_format($row->total_qty, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right text-gray-600">{{ number_format($row->avg_price, 2, ',', '.') }} MZN</td>
                        <td class="px-4 py-3 text-right">{{ $row->orders_count }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-8 text-center text-gray-500">Sem produtos vendidos no período.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Categoria + Estado em grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <div class="p-4 border-b border-gray-100"><h2 class="font-bold text-gray-900">Vendas por categoria</h2></div>
            <div class="p-5 space-y-3">
                @php $catMax = $byCategory->max('total_revenue') ?: 1; @endphp
                @forelse ($byCategory as $c)
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">{{ $c->category }}</span>
                            <span class="text-sm font-semibold text-gray-900">{{ number_format($c->total_revenue, 2, ',', '.') }} MZN</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="bg-gradient-to-r from-purple-500 to-pink-500 h-2 rounded-full" style="width: {{ ($c->total_revenue / $catMax) * 100 }}%"></div>
                        </div>
                        <p class="text-[11px] text-gray-500 mt-0.5">{{ number_format($c->total_qty, 0, ',', '.') }} unidades</p>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 text-center py-4">Sem dados.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <div class="p-4 border-b border-gray-100"><h2 class="font-bold text-gray-900">Estado dos pedidos (período)</h2></div>
            <div class="p-5 space-y-3">
                @php
                    $statuses = [
                        'paid' => ['Pagos', 'bg-emerald-500', 'text-emerald-700'],
                        'pending' => ['Pendentes', 'bg-amber-500', 'text-amber-700'],
                        'failed' => ['Falhados', 'bg-red-500', 'text-red-700'],
                    ];
                @endphp
                @foreach ($statuses as $key => [$label, $bg, $text])
                    @php $s = $byStatus[$key] ?? null; @endphp
                    <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50 border border-gray-100">
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full {{ $bg }}"></span>
                            <span class="font-medium text-gray-900">{{ $label }}</span>
                        </div>
                        <div class="text-right">
                            <p class="font-bold {{ $text }}">{{ $s ? $s->cnt : 0 }} {{ ($s && $s->cnt == 1) ? 'pedido' : 'pedidos' }}</p>
                            <p class="text-xs text-gray-500">{{ number_format($s->total ?? 0, 2, ',', '.') }} MZN</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Tabela por fornecedor -->
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden mb-6">
        <div class="p-4 border-b border-gray-100"><h2 class="font-bold text-gray-900">Vendas por fornecedor</h2></div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 text-xs uppercase">
                <tr>
                    <th class="text-left px-4 py-3">Fornecedor</th>
                    <th class="text-right px-4 py-3">Receita</th>
                    <th class="text-right px-4 py-3">% do total</th>
                    <th class="text-right px-4 py-3">Pedidos</th>
                    <th class="text-right px-4 py-3">Quantidade</th>
                    <th class="text-right px-4 py-3">Produtos vendidos</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($bySupplier as $row)
                    @php $pct = $totals['revenue'] > 0 ? ($row->total_revenue / $totals['revenue']) * 100 : 0; @endphp
                    <tr>
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $row->supplier_name }}</td>
                        <td class="px-4 py-3 text-right">{{ number_format($row->total_revenue, 2, ',', '.') }} MZN</td>
                        <td class="px-4 py-3 text-right">
                            <div class="inline-flex items-center gap-2">
                                <div class="w-16 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-emerald-500" style="width: {{ $pct }}%"></div>
                                </div>
                                <span class="text-xs text-gray-600">{{ number_format($pct, 1, ',', '.') }}%</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-right">{{ $row->orders_count }}</td>
                        <td class="px-4 py-3 text-right">{{ number_format($row->total_qty, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right">{{ $row->products_sold }}</td>
                        <td class="px-4 py-3 text-right">
                            @if ($row->supplier_id)
                                <button type="button" wire:click="$set('supplierId', {{ $row->supplier_id }})" class="text-blue-600 hover:underline text-xs">Ver detalhe →</button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-8 text-center text-gray-500">Sem vendas pagas no período.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Detalhe do fornecedor seleccionado -->
    @if ($detail)
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden mb-6">
            <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-bold text-gray-900">Detalhe: {{ $detail['supplier']->company_name ?? '—' }}</h2>
                <button type="button" wire:click="$set('supplierId', null)" class="text-sm text-gray-500 hover:text-gray-900">Limpar filtro</button>
            </div>
            <div class="p-5 space-y-2">
                @if ($detail['byDay']->count())
                    @php $maxV = $detail['byDay']->max('total') ?: 1; @endphp
                    @foreach ($detail['byDay'] as $day)
                        <div>
                            <div class="flex items-center justify-between mb-1 text-sm">
                                <span><strong>{{ $day['day'] }}</strong> <span class="text-xs text-gray-500">· {{ $day['orders'] }} pedidos · {{ number_format($day['qty'], 0, ',', '.') }} kg</span></span>
                                <span class="font-bold">{{ number_format($day['total'], 2, ',', '.') }} MZN</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-1.5">
                                <div class="h-full bg-gradient-to-r from-emerald-500 to-green-600 rounded-full" style="width: {{ ($day['total'] / $maxV) * 100 }}%"></div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-sm text-gray-500 py-6 text-center">Sem vendas neste período para este fornecedor.</p>
                @endif
            </div>
        </div>
    @endif
</div>
