<div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        <nav class="text-sm text-gray-500 mb-4">
            <a href="{{ route('my-orders') }}" class="hover:underline">Meus Pedidos</a>
            <span class="mx-2">/</span>
            <span class="text-gray-900">Devolução do pedido #{{ $order->order_number }}</span>
        </nav>

        @if (session('message'))
            <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-green-800">{{ session('message') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-red-800">{{ session('error') }}</div>
        @endif

        <div class="bg-white rounded-2xl border border-gray-100 p-6 mb-5">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Solicitar devolução</h1>
                    <p class="text-sm text-gray-500 mt-1">Pedido <strong>#{{ $order->order_number }}</strong> · {{ $order->created_at->format('d/m/Y') }} · {{ number_format($order->total, 2, ',', '.') }} MZN</p>
                </div>
                <span class="rounded-full px-2.5 py-0.5 text-xs font-medium bg-emerald-100 text-emerald-800">Pago</span>
            </div>

            <div class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach ($order->items as $item)
                    <div class="flex items-center gap-3 bg-gray-50 rounded-xl p-3">
                        @if ($item->product?->image)
                            <img src="{{ asset('storage/' . $item->product->image) }}" class="w-12 h-12 rounded-lg object-cover" alt="">
                        @else
                            <div class="w-12 h-12 rounded-lg bg-gray-200"></div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-900 truncate">{{ $item->product_name }}</p>
                            <p class="text-xs text-gray-500">{{ $item->quantity }} × {{ number_format($item->price, 2, ',', '.') }} MZN</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        @if ($submitted)
            @php $last = $order->returns->sortByDesc('id')->first(); @endphp
            <div class="bg-white rounded-2xl border border-gray-100 p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div>
                        <h2 class="font-bold text-gray-900">Solicitação registada</h2>
                        <p class="text-sm text-gray-500">Vamos analisar e responder em até 48 horas úteis.</p>
                    </div>
                </div>
                @if ($last)
                    <div class="grid grid-cols-2 gap-3 text-sm border-t border-gray-100 pt-4 mt-4">
                        <div>
                            <p class="text-xs text-gray-500">Referência</p>
                            <p class="font-mono text-gray-900">{{ $last->return_number }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Estado</p>
                            <p class="font-medium text-amber-700">{{ $last->status_label }}</p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-xs text-gray-500">Motivo</p>
                            <p class="text-gray-900">{{ $last->reason_label }}</p>
                        </div>
                    </div>
                @endif
                <div class="mt-5 flex gap-2">
                    <a href="{{ route('my-orders') }}" class="px-4 py-2 rounded-lg bg-gray-900 text-white text-sm font-medium hover:bg-black transition">Ver meus pedidos</a>
                    <a href="{{ route('products') }}" class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700 text-sm font-medium hover:bg-gray-200 transition">Continuar a comprar</a>
                </div>
            </div>
        @else
            <form wire:submit.prevent="submit" class="bg-white rounded-2xl border border-gray-100 p-6 space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Motivo da devolução *</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        @foreach ([
                            'defeito' => ['Produto com defeito', 'Veio danificado ou estragado'],
                            'quantidade_errada' => ['Quantidade errada', 'Recebi menos do que pedi'],
                            'produto_errado' => ['Produto errado', 'Veio outro produto'],
                            'nao_corresponde' => ['Não corresponde', 'Diferente da descrição'],
                            'outro' => ['Outro motivo', 'Descreve abaixo'],
                        ] as $value => $info)
                            <label class="flex items-start gap-3 rounded-xl border p-3 cursor-pointer transition
                                {{ $reason === $value ? 'border-emerald-500 bg-emerald-50' : 'border-gray-200 hover:border-gray-300' }}">
                                <input type="radio" wire:model.live="reason" value="{{ $value }}" class="mt-0.5 text-emerald-600 focus:ring-emerald-500">
                                <div class="text-sm">
                                    <p class="font-medium text-gray-900">{{ $info[0] }}</p>
                                    <p class="text-xs text-gray-500">{{ $info[1] }}</p>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('reason') <p class="text-xs text-red-600 mt-1.5">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Descrição detalhada *</label>
                    <textarea wire:model.blur="description" rows="5" maxlength="1000"
                              placeholder="Descreve o que aconteceu, quantidades afetadas, e qualquer informação útil para resolvermos rapidamente."
                              class="w-full rounded-xl border-gray-200 focus:ring-emerald-500 focus:border-emerald-500 text-sm"></textarea>
                    <div class="flex justify-between mt-1">
                        @error('description') <p class="text-xs text-red-600">{{ $message }}</p> @else <span></span> @enderror
                        <p class="text-xs text-gray-400">{{ strlen($description) }}/1000</p>
                    </div>
                </div>

                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-sm text-amber-900">
                    <p class="font-medium">Política de devoluções</p>
                    <ul class="mt-1 list-disc list-inside text-amber-800/90 space-y-0.5">
                        <li>Solicitações aceites até 7 dias após a entrega.</li>
                        <li>Produtos perecíveis: comunicar nas primeiras 24h.</li>
                        <li>Reembolso processado em até 5 dias úteis após aprovação.</li>
                    </ul>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end gap-2 pt-2 border-t border-gray-100">
                    <a href="{{ route('my-orders') }}" class="px-4 py-2.5 rounded-xl text-gray-700 hover:bg-gray-100 text-sm font-medium transition text-center">Cancelar</a>
                    <button type="submit" wire:loading.attr="disabled" wire:target="submit"
                            class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 disabled:opacity-60 text-white text-sm font-semibold transition flex items-center justify-center gap-2">
                        <span wire:loading.remove wire:target="submit">Enviar solicitação</span>
                        <span wire:loading wire:target="submit">A enviar…</span>
                    </button>
                </div>
            </form>
        @endif
    </div>
</div>
