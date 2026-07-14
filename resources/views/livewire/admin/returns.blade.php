<div class="p-6 max-w-7xl mx-auto">
    <header class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Devoluções</h1>
        <p class="text-sm text-gray-500 mt-1">Analisa pedidos de devolução e processa reembolsos.</p>
    </header>

    @if (session('message'))
        <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-green-800">{{ session('message') }}</div>
    @endif
    @if (session('error'))
        <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-red-800">{{ session('error') }}</div>
    @endif

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-5">
        <div class="flex flex-wrap gap-2">
            @php
                $tabs = [
                    'pending' => ['Em análise', 'bg-amber-100 text-amber-800'],
                    'approved' => ['Aprovadas', 'bg-blue-100 text-blue-800'],
                    'refunded' => ['Reembolsadas', 'bg-emerald-100 text-emerald-800'],
                    'rejected' => ['Recusadas', 'bg-gray-100 text-gray-700'],
                ];
            @endphp
            @foreach ($tabs as $key => [$label, $badgeClass])
                <button type="button" wire:click="$set('filter', '{{ $key }}')"
                    class="px-4 py-2 rounded-full text-sm font-medium transition border
                    {{ $filter === $key ? 'bg-gray-900 text-white border-gray-900' : 'bg-white text-gray-700 border-gray-200 hover:border-gray-400' }}">
                    {{ $label }} <span class="ml-1 text-xs opacity-70">{{ $counts[$key] ?? 0 }}</span>
                </button>
            @endforeach
        </div>
        <div class="relative">
            <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/></svg>
            <input wire:model.live.debounce.400ms="search" type="search" placeholder="Nº devolução, pedido ou email"
                class="pl-9 pr-4 py-2 w-full md:w-80 rounded-full border border-gray-200 bg-white text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
        </div>
    </div>

    @forelse ($returns as $ret)
        <div wire:key="ret-{{ $ret->id }}" class="bg-white rounded-2xl border border-gray-200 mb-3 overflow-hidden">
            <button type="button" wire:click="expand({{ $ret->id }})" class="w-full p-4 flex items-start gap-4 text-left hover:bg-gray-50/60">
                <div class="hidden sm:flex w-10 h-10 rounded-lg bg-amber-50 text-amber-700 items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h11a4 4 0 014 4v4a4 4 0 01-4 4H3m0-12L7 6m-4 4l4 4"/></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-baseline gap-x-3">
                        <span class="font-mono text-gray-900 font-semibold">{{ $ret->return_number }}</span>
                        <span class="text-xs text-gray-500">Pedido #{{ $ret->order->order_number ?? '—' }}</span>
                        <span class="text-xs text-gray-500">{{ $ret->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-sm text-gray-600 mt-1 truncate"><strong>{{ $ret->reason_label }}</strong> · {{ \Illuminate\Support\Str::limit($ret->description, 100) }}</p>
                </div>
                <div class="text-right flex-shrink-0">
                    @php
                        $statusClass = match($ret->status){
                            'pending' => 'bg-amber-100 text-amber-800',
                            'approved' => 'bg-blue-100 text-blue-800',
                            'refunded' => 'bg-emerald-100 text-emerald-800',
                            'rejected' => 'bg-gray-200 text-gray-700',
                        };
                    @endphp
                    <span class="rounded-full px-2.5 py-0.5 text-xs font-medium {{ $statusClass }}">{{ $ret->status_label }}</span>
                    <p class="text-xs text-gray-500 mt-1">{{ $ret->user->email ?? '' }}</p>
                </div>
            </button>

            @if ($expanded === $ret->id)
                <div class="border-t border-gray-100 p-5 bg-gray-50/60">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div class="md:col-span-2 space-y-4">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-700">Descrição do cliente</h3>
                                <p class="mt-1 text-sm text-gray-900 whitespace-pre-line bg-white border border-gray-100 rounded-xl p-3">{{ $ret->description }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Notas internas / Motivo da decisão</label>
                                <textarea wire:model="adminNotes" rows="3" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500 focus:border-emerald-500 text-sm" placeholder="Para rejeições, indica o motivo aqui."></textarea>
                            </div>
                            <div class="flex flex-wrap gap-2 pt-2">
                                @if ($ret->status === 'pending')
                                    <button type="button" wire:click="approve({{ $ret->id }})"
                                        class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700 transition">
                                        Aprovar devolução
                                    </button>
                                    <button type="button" wire:click="reject({{ $ret->id }})"
                                        class="px-4 py-2 rounded-lg bg-red-50 text-red-700 text-sm font-medium hover:bg-red-100 transition">
                                        Rejeitar
                                    </button>
                                @endif
                                @if ($ret->status === 'approved')
                                    <button type="button" wire:click="refund({{ $ret->id }})"
                                        wire:confirm="Marcar como reembolsado e cancelar o pedido?"
                                        class="px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 transition">
                                        Marcar como reembolsado
                                    </button>
                                @endif
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div class="bg-white rounded-xl border border-gray-100 p-4 text-sm">
                                <h3 class="font-semibold text-gray-700 mb-2">Pedido</h3>
                                <p class="text-gray-900">#{{ $ret->order->order_number ?? '—' }}</p>
                                <p class="text-gray-500 mt-1">{{ $ret->order ? number_format($ret->order->total, 2, ',', '.') . ' MZN' : '—' }}</p>
                                <p class="text-gray-500 mt-1">{{ $ret->order?->payment_method_label }}</p>
                            </div>
                            <div class="bg-white rounded-xl border border-gray-100 p-4 text-sm">
                                <h3 class="font-semibold text-gray-700 mb-2">Cliente</h3>
                                <p class="text-gray-900">{{ $ret->user->name ?? '—' }}</p>
                                <p class="text-gray-500">{{ $ret->user->email ?? '' }}</p>
                            </div>
                            @if ($ret->resolved_at)
                                <p class="text-xs text-gray-500">Resolvido {{ $ret->resolved_at->diffForHumans() }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @empty
        <div class="bg-white rounded-2xl border border-gray-100 p-10 text-center">
            <p class="text-gray-500">Nenhuma devolução nesta categoria.</p>
        </div>
    @endforelse

    @if ($returns->hasPages())
        <div class="mt-4">{{ $returns->links() }}</div>
    @endif
</div>
