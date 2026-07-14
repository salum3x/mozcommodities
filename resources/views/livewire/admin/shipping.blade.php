<div class="p-6 max-w-7xl mx-auto">
    <header class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Frete</h1>
            <p class="text-sm text-gray-500 mt-1">Configura tarifas por província (cidade opcional). Sem zona: usa fórmula por distância.</p>
        </div>
        <button type="button" wire:click="create" class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700">+ Nova zona</button>
    </header>

    @if (session('message'))
        <div class="mb-4 rounded-lg bg-emerald-50 border border-emerald-200 px-4 py-3 text-emerald-800">{{ session('message') }}</div>
    @endif

    @if ($showForm)
        <div class="bg-white border border-emerald-200 rounded-2xl p-5 mb-5 shadow-sm">
            <h2 class="font-bold text-gray-900 mb-4">{{ $editingId ? 'Editar zona' : 'Nova zona' }}</h2>
            <form wire:submit.prevent="save" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Província *</label>
                    <input wire:model="province" class="w-full rounded-lg border-gray-300 text-sm" placeholder="ex.: Sofala">
                    @error('province') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Cidade (opcional)</label>
                    <input wire:model="city" class="w-full rounded-lg border-gray-300 text-sm" placeholder="ex.: Beira">
                </div>
                <div class="flex items-end">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" wire:model="active" class="rounded text-emerald-600">
                        <span class="text-sm text-gray-700">Ativa</span>
                    </label>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Taxa base (MZN) *</label>
                    <input type="number" step="0.01" wire:model="base_fee" class="w-full rounded-lg border-gray-300 text-sm">
                    <p class="text-xs text-gray-500 mt-0.5">Custo fixo por entrega.</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Por kg (MZN/kg) *</label>
                    <input type="number" step="0.01" wire:model="per_kg_rate" class="w-full rounded-lg border-gray-300 text-sm">
                    <p class="text-xs text-gray-500 mt-0.5">Adicionado ao peso total do pedido.</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Frete grátis acima de (MZN)</label>
                    <input type="number" step="0.01" wire:model="free_above_amount" class="w-full rounded-lg border-gray-300 text-sm" placeholder="Deixa vazio para nunca">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Carrada acima de (kg)</label>
                    <input type="number" wire:model="truckload_threshold_kg" class="w-full rounded-lg border-gray-300 text-sm" placeholder="ex.: 1000">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Carrada — preço flat (MZN)</label>
                    <input type="number" step="0.01" wire:model="truckload_flat_fee" class="w-full rounded-lg border-gray-300 text-sm" placeholder="ex.: 4500">
                </div>
                <div class="md:col-span-3">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Notas internas</label>
                    <input wire:model="notes" class="w-full rounded-lg border-gray-300 text-sm">
                </div>
                <div class="md:col-span-3 flex gap-2 justify-end">
                    <button type="button" wire:click="$set('showForm', false)" class="px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-100 text-sm">Cancelar</button>
                    <button type="submit" class="px-5 py-2 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700">Guardar zona</button>
                </div>
            </form>
        </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden mb-6">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 text-xs uppercase">
                <tr>
                    <th class="text-left px-4 py-3">Província / Cidade</th>
                    <th class="text-right px-4 py-3">Base</th>
                    <th class="text-right px-4 py-3">Por kg</th>
                    <th class="text-right px-4 py-3">Grátis ≥</th>
                    <th class="text-right px-4 py-3">Carrada ≥ kg</th>
                    <th class="text-right px-4 py-3">Carrada flat</th>
                    <th class="text-center px-4 py-3">Ativa</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($zones as $z)
                    <tr wire:key="z-{{ $z->id }}" class="{{ $z->active ? '' : 'opacity-60' }}">
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-900">{{ $z->province }}{{ $z->city ? ' · ' . $z->city : '' }}</p>
                            @if ($z->notes)<p class="text-xs text-gray-500">{{ $z->notes }}</p>@endif
                        </td>
                        <td class="px-4 py-3 text-right">{{ number_format($z->base_fee, 0, ',', '.') }} MZN</td>
                        <td class="px-4 py-3 text-right">{{ number_format($z->per_kg_rate, 0, ',', '.') }} MZN</td>
                        <td class="px-4 py-3 text-right">{{ $z->free_above_amount ? number_format($z->free_above_amount, 0, ',', '.') . ' MZN' : '—' }}</td>
                        <td class="px-4 py-3 text-right">{{ $z->truckload_threshold_kg ? number_format($z->truckload_threshold_kg, 0, ',', '.') : '—' }}</td>
                        <td class="px-4 py-3 text-right">{{ $z->truckload_flat_fee ? number_format($z->truckload_flat_fee, 0, ',', '.') . ' MZN' : '—' }}</td>
                        <td class="px-4 py-3 text-center">
                            <button type="button" wire:click="toggleActive({{ $z->id }})"
                                class="inline-flex w-10 h-5 rounded-full {{ $z->active ? 'bg-emerald-500' : 'bg-gray-300' }} relative transition">
                                <span class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full transition-transform {{ $z->active ? 'translate-x-5' : '' }}"></span>
                            </button>
                        </td>
                        <td class="px-4 py-3 text-right whitespace-nowrap">
                            <button type="button" wire:click="edit({{ $z->id }})" class="text-blue-600 hover:underline text-xs mr-2">Editar</button>
                            <button type="button" wire:click="delete({{ $z->id }})" wire:confirm="Remover zona {{ $z->province }}?" class="text-red-600 hover:underline text-xs">Remover</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="px-4 py-8 text-center text-gray-500">Sem zonas configuradas. Cria uma para começar.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Fallback (distância) -->
    <div class="bg-white border border-gray-200 rounded-2xl p-5">
        <h2 class="font-bold text-gray-900">Tarifa por distância (fallback)</h2>
        <p class="text-sm text-gray-500 mt-1 mb-4">Usada quando o destino não corresponde a nenhuma zona acima. Distância Haversine entre fornecedor e cliente.</p>
        <form wire:submit.prevent="saveFallback" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Taxa base (MZN)</label>
                <input type="number" step="0.01" wire:model="shipping_base_fee" class="w-full rounded-lg border-gray-300 text-sm">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Preço por km (MZN)</label>
                <input type="number" step="0.01" wire:model="shipping_price_per_km" class="w-full rounded-lg border-gray-300 text-sm">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Mínimo (MZN)</label>
                <input type="number" step="0.01" wire:model="shipping_min_fee" class="w-full rounded-lg border-gray-300 text-sm">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Grátis acima de (MZN)</label>
                <input type="number" step="0.01" wire:model="shipping_free_over_amount" class="w-full rounded-lg border-gray-300 text-sm" placeholder="opcional">
            </div>
            <div class="md:col-span-4 flex justify-end">
                <button type="submit" class="px-5 py-2 rounded-lg bg-gray-900 text-white text-sm font-semibold hover:bg-black">Guardar fallback</button>
            </div>
        </form>
    </div>
</div>
