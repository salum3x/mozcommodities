<div class="max-w-6xl mx-auto px-4 py-8"
     x-data="{
         toast: { show: false, type: 'success', text: '' },
         flash(type, text, ms = 3000) { this.toast = { show: true, type, text }; clearTimeout(this._t); this._t = setTimeout(() => this.toast.show = false, ms); }
     }"
     @cart-added.window="flash('success', $event.detail?.text || 'Adicionado ao carrinho')"
     @cart-error.window="flash('error', $event.detail?.text || 'Erro ao adicionar')"
     wire:key="product-detail-{{ $product->id }}">

    <!-- Toast (Alpine-driven, instant feedback) -->
    <div x-show="toast.show" x-cloak x-transition.opacity
         class="fixed top-4 right-4 z-50 max-w-sm rounded-xl shadow-lg border px-4 py-3 flex items-start gap-3"
         :class="toast.type === 'success' ? 'bg-emerald-600 text-white border-emerald-700' : 'bg-red-600 text-white border-red-700'">
        <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <template x-if="toast.type === 'success'"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></template>
            <template x-if="toast.type === 'error'"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></template>
        </svg>
        <div class="flex-1">
            <p class="font-medium text-sm" x-text="toast.text"></p>
            <template x-if="toast.type === 'success'">
                <a href="{{ route('cart') }}" class="text-xs underline opacity-90 hover:opacity-100">Ver carrinho →</a>
            </template>
        </div>
        <button type="button" @click="toast.show = false" class="opacity-70 hover:opacity-100">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    <nav class="text-sm text-gray-500 mb-4">
        <a href="{{ route('products') }}" class="hover:underline">Produtos</a>
        <span class="mx-2">/</span>
        @if ($product->category)
            <a href="{{ route('products', ['category' => $product->category->id]) }}" class="hover:underline">{{ $product->category->name }}</a>
            <span class="mx-2">/</span>
        @endif
        <span class="text-gray-900">{{ $product->name }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div>
            @if ($selected->display_image)
                <img src="{{ $selected->display_image }}" alt="{{ $selected->name }}" class="w-full rounded-2xl shadow-md object-cover aspect-square bg-white">
            @else
                @php $ph = $selected->placeholder; @endphp
                <div class="w-full aspect-square rounded-2xl bg-gradient-to-br {{ $ph['bg'] }} flex items-center justify-center">
                    <span class="text-9xl select-none filter drop-shadow-sm" role="img" aria-label="{{ $selected->name }}">{{ $ph['emoji'] }}</span>
                </div>
            @endif
        </div>

        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $product->name }}</h1>
            @if ($product->category)
                <p class="text-sm text-gray-500 mt-1">{{ $product->category->name }}</p>
            @endif

            @if ($offers->count() > 1)
                <div class="mt-4 inline-flex items-center gap-2 rounded-full bg-amber-50 px-3 py-1 text-amber-700 text-xs">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 1l2.39 6.74L19 8.5l-5 4.74L15.18 19 10 15.77 4.82 19 6 13.24 1 8.5l6.61-.76L10 1z"/></svg>
                    {{ $offers->count() }} fornecedores vendem este produto — escolhe o melhor
                </div>
            @endif

            <div class="mt-6 rounded-2xl border border-gray-200 p-5 bg-white">
                <div class="flex items-baseline gap-3">
                    <span class="text-4xl font-bold text-gray-900">{{ number_format($selected->price_per_kg, 2, ',', '.') }} MZN</span>
                    <span class="text-sm text-gray-500">/ {{ $selected->unit ?? 'kg' }}</span>
                </div>
                @if ($highestPrice && $highestPrice != $lowestPrice)
                    <p class="text-xs text-gray-500 mt-1">Variação no mercado: {{ number_format($lowestPrice, 2, ',', '.') }} – {{ number_format($highestPrice, 2, ',', '.') }} MZN/{{ $selected->unit ?? 'kg' }}</p>
                @endif

                <div class="mt-4 flex items-center gap-2 text-sm">
                    @if ($selected->supplier)
                        <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-2.5 py-1 text-blue-700">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a4 4 0 100 8 4 4 0 000-8zM2 18a8 8 0 1116 0H2z"/></svg>
                            {{ $selected->supplier->company_name }}
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 rounded-full bg-green-50 px-2.5 py-1 text-green-700">Produto próprio</span>
                    @endif
                    <span class="text-gray-500">Stock: {{ $selected->stock_quantity }} {{ $selected->unit ?? 'kg' }}</span>
                </div>

                @php
                    $minQty = max(1, (int) $selected->min_quantity);
                    $maxQty = max($minQty, (int) $selected->stock_quantity);
                    $price = (float) $selected->price_per_kg;
                @endphp
                <div class="mt-6 space-y-4"
                      x-data="{
                          qty: @js((int) $quantity ?: $minQty),
                          min: {{ $minQty }},
                          max: {{ $maxQty }},
                          price: {{ $price }},
                          inc() { if (this.qty < this.max) { this.qty++; this.sync(); } },
                          dec() { if (this.qty > this.min) { this.qty--; this.sync(); } },
                          onInput(e) {
                              let v = parseInt(e.target.value || this.min, 10);
                              if (isNaN(v)) v = this.min;
                              if (v < this.min) v = this.min;
                              if (v > this.max) v = this.max;
                              this.qty = v;
                              this.sync();
                          },
                          sync() { $wire.set('quantity', this.qty, false); },
                          get subtotal() { return (this.qty * this.price).toLocaleString('pt-PT', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); }
                      }">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Quantidade <span class="text-gray-400">({{ $selected->unit ?? 'kg' }})</span></label>
                        <div class="flex items-center gap-3 flex-wrap">
                            <div class="flex items-center bg-gray-100 rounded-full p-1 gap-1">
                                <button type="button" @click="dec()" :disabled="qty <= min" aria-label="Diminuir"
                                    class="w-10 h-10 rounded-full bg-white shadow-sm hover:shadow-md active:scale-95 flex items-center justify-center text-gray-700 transition disabled:opacity-30 disabled:cursor-not-allowed">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" d="M5 12h14"/></svg>
                                </button>
                                <input type="number" x-model.number="qty" @input="onInput($event)"
                                    :min="min" :max="max"
                                    class="w-16 bg-transparent text-center text-lg font-semibold text-gray-900 border-0 focus:ring-0 focus:outline-none [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none">
                                <button type="button" @click="inc()" :disabled="qty >= max" aria-label="Aumentar"
                                    class="w-10 h-10 rounded-full bg-white shadow-sm hover:shadow-md active:scale-95 flex items-center justify-center text-gray-700 transition disabled:opacity-30 disabled:cursor-not-allowed">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" d="M12 5v14M5 12h14"/></svg>
                                </button>
                            </div>
                            <div class="text-sm text-gray-600">
                                Subtotal:
                                <strong class="text-gray-900" x-text="subtotal + ' MZN'"></strong>
                            </div>
                        </div>
                        @error('quantity') <p class="text-xs text-red-600 mt-1.5">{{ $message }}</p> @enderror
                    </div>
                    <button type="button"
                        @click="$dispatch('cart-added', { qty: qty, text: 'Adicionado: ' + qty + ' {{ $selected->unit ?? 'kg' }} de {{ addslashes($selected->name) }}' }); $wire.addToCart()"
                        class="w-full rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-6 py-3.5 transition shadow-sm hover:shadow flex items-center justify-center gap-2 active:scale-[0.98]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        Adicionar ao carrinho
                    </button>
                </div>
            </div>

            @if ($product->description)
                <div class="mt-6">
                    <h3 class="font-semibold text-gray-900 mb-2">Descrição</h3>
                    <p class="text-gray-700 whitespace-pre-line">{{ $product->description }}</p>
                </div>
            @endif
        </div>
    </div>

    @if ($offers->count() > 1)
        <section class="mt-12">
            <h2 class="text-xl font-bold text-gray-900 mb-1">Comparar fornecedores</h2>
            <p class="text-sm text-gray-500 mb-4">Ordenado do mais caro para o mais barato. Clica para selecionar antes de adicionar ao carrinho.</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($offers as $offer)
                    @php $isSelected = $offer->id === $selected->id; @endphp
                    <button
                        type="button"
                        wire:click="selectOffer({{ $offer->id }})"
                        class="text-left rounded-2xl border-2 p-4 transition {{ $isSelected ? 'border-emerald-500 bg-emerald-50 shadow-md' : 'border-gray-200 bg-white hover:border-gray-300' }}"
                    >
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="font-semibold text-gray-900">
                                    {{ $offer->supplier?->company_name ?? 'Produto próprio' }}
                                </p>
                                <p class="text-xs text-gray-500 mt-0.5">Stock: {{ $offer->stock_quantity }} {{ $offer->unit ?? 'kg' }}</p>
                            </div>
                            @if ($isSelected)
                                <span class="rounded-full bg-emerald-500 text-white text-xs px-2 py-0.5">Selecionado</span>
                            @elseif ($offer->price_per_kg == $highestPrice)
                                <span class="rounded-full bg-amber-100 text-amber-700 text-xs px-2 py-0.5">Mais caro</span>
                            @elseif ($offer->price_per_kg == $lowestPrice)
                                <span class="rounded-full bg-green-100 text-green-700 text-xs px-2 py-0.5">Mais barato</span>
                            @endif
                        </div>
                        <div class="mt-3">
                            <span class="text-2xl font-bold text-gray-900">{{ number_format($offer->price_per_kg, 2, ',', '.') }}</span>
                            <span class="text-xs text-gray-500"> MZN/{{ $offer->unit ?? 'kg' }}</span>
                        </div>
                    </button>
                @endforeach
            </div>
        </section>
    @endif
</div>
