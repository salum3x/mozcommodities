<div>
    <!-- Hero header -->
    <div class="bg-gradient-to-br from-emerald-700 via-emerald-600 to-green-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl sm:text-4xl font-bold">Produtos Agrícolas</h1>
                    <p class="text-emerald-100 mt-2 max-w-2xl">Comparamos preços entre fornecedores. Escolhe pelo melhor preço, stock disponível ou reputação.</p>
                </div>
                <a href="{{ route('quote.form') }}" class="self-start md:self-auto inline-flex items-center gap-2 bg-white text-emerald-700 px-5 py-2.5 rounded-xl font-semibold hover:bg-emerald-50 transition shadow">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Pedir cotação por volume
                </a>
            </div>
        </div>
    </div>

    <!-- Sticky filter / search bar -->
    <div class="bg-white border-b shadow-sm sticky top-0 z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 space-y-3">

            <!-- Search + sort row -->
            <div class="flex flex-col md:flex-row md:items-center gap-3">
                <div class="relative flex-1">
                    <svg class="w-5 h-5 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/></svg>
                    <input wire:model.live.debounce.400ms="search" type="search" placeholder="Procurar produto (ex: gergelim, milho, feijão)"
                        class="w-full pl-11 pr-10 py-3 rounded-xl border border-gray-200 bg-white text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    @if ($search)
                        <button type="button" wire:click="$set('search', '')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    @endif
                </div>
                <div class="flex items-center gap-2">
                    <label class="text-sm text-gray-500 hidden sm:inline">Ordenar:</label>
                    <select wire:model.live="sort" class="bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="relevant">Mais relevantes</option>
                        <option value="price_asc">Menor preço</option>
                        <option value="price_desc">Maior preço</option>
                        <option value="name">Nome (A-Z)</option>
                        <option value="newest">Mais recentes</option>
                    </select>
                </div>
            </div>

            <!-- Categories pills -->
            <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-hide">
                <button type="button" wire:click="clearFilter"
                    class="flex-shrink-0 inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold transition whitespace-nowrap
                    {{ $selectedCategory === null ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Todos
                </button>
                @foreach($categories as $category)
                    @if($category->products_count > 0)
                        <button type="button" wire:click="filterByCategory({{ $category->id }})"
                            class="flex-shrink-0 inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold transition whitespace-nowrap
                            {{ $selectedCategory == $category->id ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            {{ $category->name }}
                            <span class="inline-flex items-center justify-center min-w-5 h-5 px-1.5 text-xs rounded-full {{ $selectedCategory == $category->id ? 'bg-white/20' : 'bg-gray-200' }}">
                                {{ $category->products_count }}
                            </span>
                        </button>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Results header -->
        <div class="flex items-center justify-between mb-6">
            <p class="text-sm text-gray-600">
                <span class="text-2xl font-bold text-gray-900">{{ $products->count() }}</span>
                {{ $products->count() == 1 ? 'produto' : 'produtos' }}
                @if ($search) para <span class="font-semibold">“{{ $search }}”</span> @endif
            </p>
            @if ($selectedCategory || $search || $sort !== 'relevant')
                <button type="button" wire:click="clearFilter" class="text-sm text-gray-500 hover:text-gray-900 font-medium underline-offset-4 hover:underline">Limpar filtros</button>
            @endif
        </div>

        @if($products->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3">
                @foreach($products as $group)
                    @php
                        $product = $group->product;
                        $hasMulti = $group->offer_count > 1;
                        $stock = $group->total_stock;
                        $stockBucket = $stock > 200 ? 'high' : ($stock > 50 ? 'mid' : 'low');
                    @endphp
                    <div wire:key="prod-{{ $product->id }}"
                         class="group relative bg-white rounded-xl border border-gray-100 hover:border-emerald-300 hover:shadow-md transition overflow-hidden flex flex-col"
                         x-data="{
                            inWishlist: false,
                            async toggle() {
                                @auth
                                try {
                                    const r = await fetch('{{ route('wishlist.toggle', $product->id) }}', {
                                        method: 'POST',
                                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                                        credentials: 'same-origin',
                                    });
                                    const j = await r.json();
                                    this.inWishlist = j.added;
                                } catch (e) {}
                                @else
                                    window.location.href = '{{ route('login') }}';
                                @endauth
                            }
                         }">
                        <button type="button" @click.prevent.stop="toggle()" aria-label="Favorito"
                                class="absolute top-2 right-2 z-10 w-7 h-7 rounded-full flex items-center justify-center transition shadow-sm"
                                :class="inWishlist ? 'bg-red-500 text-white' : 'bg-white/90 text-gray-400 hover:text-red-500'">
                            <svg class="w-3.5 h-3.5" :class="inWishlist ? 'fill-current' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z"/></svg>
                        </button>
                        <a href="{{ route('product.detail', $product->slug) }}" class="flex flex-col flex-1">

                        <!-- Image -->
                        <div class="relative aspect-square bg-gray-50 overflow-hidden">
                            @if($product->display_image)
                                <img src="{{ $product->display_image }}" alt="{{ $product->name }}" loading="lazy"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                @php $ph = $product->placeholder; @endphp
                                <div class="flex items-center justify-center h-full bg-gradient-to-br {{ $ph['bg'] }}">
                                    <span class="text-5xl sm:text-6xl select-none filter drop-shadow-sm" role="img" aria-label="{{ $product->name }}">{{ $ph['emoji'] }}</span>
                                </div>
                            @endif

                            <!-- Top-left badges -->
                            <div class="absolute top-1.5 left-1.5 flex flex-col gap-1">
                                @if ($product->is_company_product)
                                    <span class="bg-emerald-600 text-white px-1.5 py-0.5 rounded text-[9px] font-bold tracking-wide leading-none">OFICIAL</span>
                                @endif
                                @if ($hasMulti)
                                    <span class="bg-amber-500 text-white px-1.5 py-0.5 rounded text-[9px] font-bold tracking-wide leading-none">
                                        {{ $group->offer_count }}× FORNECEDORES
                                    </span>
                                @endif
                            </div>

                            @if ($stockBucket === 'low')
                                <span class="absolute top-1.5 right-1.5 bg-orange-500 text-white px-1.5 py-0.5 rounded text-[9px] font-bold leading-none">POUCO</span>
                            @endif
                        </div>

                        <!-- Info -->
                        <div class="p-2.5 flex flex-col flex-1">
                            <span class="text-[10px] text-emerald-700 font-medium uppercase tracking-wide truncate">{{ $product->category->name ?? '—' }}</span>
                            <h3 class="text-sm font-semibold text-gray-900 line-clamp-2 group-hover:text-emerald-700 transition leading-snug mt-0.5">{{ $product->name }}</h3>

                            <!-- Price -->
                            <div class="mt-2">
                                @if ($hasMulti)
                                    <div class="flex items-baseline gap-1">
                                        <span class="text-[10px] text-gray-500">desde</span>
                                        <span class="text-base font-bold text-gray-900">{{ number_format($group->min_price, 0, ',', '.') }}</span>
                                        <span class="text-[10px] text-gray-500">MZN</span>
                                    </div>
                                    <p class="text-[10px] text-gray-400 leading-tight">até {{ number_format($group->max_price, 0, ',', '.') }} MZN/{{ $product->unit ?? 'kg' }}</p>
                                @else
                                    <div class="flex items-baseline gap-1">
                                        <span class="text-base font-bold text-gray-900">{{ number_format($product->price_per_kg, 0, ',', '.') }}</span>
                                        <span class="text-[10px] text-gray-500">MZN/{{ $product->unit ?? 'kg' }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Stock + CTA hint -->
                            <div class="mt-auto pt-2 flex items-center justify-between gap-2">
                                <span class="text-[10px] text-gray-500 flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $stockBucket === 'high' ? 'bg-emerald-500' : ($stockBucket === 'mid' ? 'bg-yellow-500' : 'bg-orange-500') }}"></span>
                                    {{ number_format($stock, 0, ',', '.') }} {{ $product->unit ?? 'kg' }}
                                </span>
                                <span class="text-[10px] text-emerald-700 font-semibold group-hover:underline">
                                    {{ $hasMulti ? 'Comparar →' : 'Ver →' }}
                                </span>
                            </div>
                        </div>
                        </a>
                    </div>
                @endforeach
            </div>

            <!-- Promo CTA -->
            <div class="mt-12 bg-gradient-to-r from-emerald-600 to-green-700 rounded-2xl p-6 sm:p-8 text-white">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div>
                        <h3 class="text-xl sm:text-2xl font-bold mb-1">Precisas de quantidade grande?</h3>
                        <p class="text-emerald-100">Pede uma cotação personalizada para volumes acima de 1 tonelada.</p>
                    </div>
                    <a href="{{ route('quote.form') }}" class="flex-shrink-0 bg-white text-emerald-700 px-6 py-3 rounded-xl font-bold hover:bg-emerald-50 transition shadow">
                        Pedir cotação
                    </a>
                </div>
            </div>
        @else
            <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center">
                <div class="w-20 h-20 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-5">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/></svg>
                </div>
                <h2 class="text-xl font-bold text-gray-900">Nenhum produto encontrado</h2>
                <p class="text-gray-500 mt-1.5">Tenta ajustar os filtros ou faz uma nova pesquisa.</p>
                <button type="button" wire:click="clearFilter"
                    class="mt-5 inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 text-white rounded-xl font-semibold hover:bg-emerald-700 transition">
                    Ver todos os produtos
                </button>
            </div>
        @endif
    </div>

    <!-- Features Section -->
    <div class="bg-white border-t border-gray-100 py-12 mt-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="w-12 h-12 bg-emerald-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 text-sm">Qualidade garantida</h3>
                    <p class="text-xs text-gray-500 mt-1">Fornecedores verificados</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 text-sm">Entrega em todo o país</h3>
                    <p class="text-xs text-gray-500 mt-1">Em todo Moçambique</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-purple-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 text-sm">Comparação de preços</h3>
                    <p class="text-xs text-gray-500 mt-1">Vários fornecedores por produto</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-orange-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 text-sm">Suporte dedicado</h3>
                    <p class="text-xs text-gray-500 mt-1">Sempre disponíveis</p>
                </div>
            </div>
        </div>
    </div>
</div>
