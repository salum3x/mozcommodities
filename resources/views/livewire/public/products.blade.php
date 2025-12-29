<div>
    <!-- Page Header -->
    <div class="bg-gradient-to-r from-green-600 via-green-500 to-emerald-500 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl sm:text-4xl font-bold mb-2">Produtos Agrícolas</h1>
            <p class="text-green-100 text-lg">Qualidade garantida direto dos melhores produtores</p>
        </div>
    </div>

    <!-- Categories Pills -->
    <div class="bg-white border-b shadow-sm sticky top-0 z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-hide">
                <button wire:click="clearFilter"
                        class="flex-shrink-0 inline-flex items-center gap-2 px-4 py-2.5 rounded-full text-sm font-semibold transition-all whitespace-nowrap {{ $selectedCategory === null ? 'bg-green-600 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    Todos
                </button>

                @foreach($categories as $category)
                    @if($category->products_count > 0)
                        <button wire:click="filterByCategory({{ $category->id }})"
                                class="flex-shrink-0 inline-flex items-center gap-2 px-4 py-2.5 rounded-full text-sm font-semibold transition-all whitespace-nowrap {{ $selectedCategory == $category->id ? 'bg-green-600 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            {{ $category->name }}
                            <span class="inline-flex items-center justify-center w-5 h-5 text-xs rounded-full {{ $selectedCategory == $category->id ? 'bg-white/20' : 'bg-gray-200' }}">
                                {{ $category->products_count }}
                            </span>
                        </button>
                    @endif
                @endforeach
            </div>

            <!-- Active Filters -->
            @if($selectedCategory || $search)
                <div class="flex items-center gap-2 mt-3 pt-3 border-t border-gray-100">
                    <span class="text-sm text-gray-500">Filtros:</span>
                    @if($search)
                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-50 text-green-700 rounded-full text-sm">
                            "{{ $search }}"
                            <button wire:click="$set('search', '')" class="hover:text-green-900">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </span>
                    @endif
                    @if($selectedCategory)
                        @php $activeCat = $categories->firstWhere('id', $selectedCategory) @endphp
                        @if($activeCat)
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-50 text-green-700 rounded-full text-sm">
                                {{ $activeCat->name }}
                                <button wire:click="clearFilter" class="hover:text-green-900">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </span>
                        @endif
                    @endif
                    <button wire:click="clearFilter" class="text-sm text-red-600 hover:text-red-700 font-medium ml-auto">
                        Limpar tudo
                    </button>
                </div>
            @endif
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Results Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <span class="text-2xl font-bold text-gray-900">{{ $products->count() }}</span>
                <span class="text-gray-500 ml-1">produtos encontrados</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-500 hidden sm:inline">Ordenar por:</span>
                <select class="bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500">
                    <option>Mais relevantes</option>
                    <option>Menor preço</option>
                    <option>Maior preço</option>
                </select>
            </div>
        </div>

        @if($products->count() > 0)
            <!-- Products Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
                @foreach($products as $product)
                    <div class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100">
                        <!-- Product Image -->
                        <a href="{{ route('product.detail', $product->slug) }}" class="block">
                            <div class="relative aspect-square bg-gray-50 overflow-hidden">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}"
                                         alt="{{ $product->name }}"
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <div class="flex items-center justify-center h-full bg-gradient-to-br from-green-50 to-emerald-100">
                                        <svg class="w-16 h-16 sm:w-20 sm:h-20 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                    </div>
                                @endif

                                <!-- Badges -->
                                <div class="absolute top-3 left-3 flex flex-col gap-2">
                                    @if($product->is_company_product)
                                        <span class="bg-green-600 text-white px-2 py-1 rounded-lg text-xs font-bold shadow-sm">
                                            OFICIAL
                                        </span>
                                    @endif
                                    @if($product->stock_kg < 50)
                                        <span class="bg-orange-500 text-white px-2 py-1 rounded-lg text-xs font-bold shadow-sm">
                                            POUCO STOCK
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </a>

                        <!-- Product Info -->
                        <div class="p-3 sm:p-4">
                            <!-- Category -->
                            <span class="text-xs text-green-600 font-medium">{{ $product->category->name ?? 'Sem categoria' }}</span>

                            <!-- Name -->
                            <a href="{{ route('product.detail', $product->slug) }}">
                                <h3 class="text-sm sm:text-base text-gray-900 font-semibold mt-1 line-clamp-2 group-hover:text-green-600 transition-colors">
                                    {{ $product->name }}
                                </h3>
                            </a>

                            <!-- Price -->
                            <div class="mt-2 sm:mt-3 flex items-baseline gap-1">
                                <span class="text-xl sm:text-2xl font-bold text-gray-900">{{ number_format($product->price_per_kg, 0, ',', '.') }}</span>
                                <span class="text-xs sm:text-sm text-gray-500">MT/{{ $product->unit ?? 'kg' }}</span>
                            </div>

                            <!-- Stock Info -->
                            <div class="mt-2 flex items-center gap-2">
                                <div class="flex-1 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full {{ $product->stock_kg > 100 ? 'bg-green-500' : ($product->stock_kg > 50 ? 'bg-yellow-500' : 'bg-red-500') }} rounded-full"
                                         style="width: {{ min(100, ($product->stock_kg / 200) * 100) }}%"></div>
                                </div>
                                <span class="text-xs text-gray-500">{{ $product->stock_kg }}kg</span>
                            </div>

                            <!-- Add to Cart -->
                            <div class="mt-3 sm:mt-4">
                                @livewire('public.add-to-cart', ['productId' => $product->id], key('cart-'.$product->id))
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Promo Banner -->
            <div class="mt-10 bg-gradient-to-r from-green-600 to-emerald-600 rounded-2xl p-6 sm:p-8 text-white">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div>
                        <h3 class="text-xl sm:text-2xl font-bold mb-1">Precisa de quantidade maior?</h3>
                        <p class="text-green-100">Solicite uma cotação personalizada para grandes pedidos</p>
                    </div>
                    <a href="{{ route('quote.form') }}" class="flex-shrink-0 bg-white text-green-600 px-6 py-3 rounded-xl font-bold hover:bg-green-50 transition-colors shadow-lg">
                        Pedir Cotação
                    </a>
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Nenhum produto encontrado</h2>
                <p class="text-gray-600 mb-6">Tente ajustar os filtros ou fazer uma nova pesquisa</p>
                <button wire:click="clearFilter"
                        class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-xl font-semibold hover:bg-green-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Ver Todos os Produtos
                </button>
            </div>
        @endif
    </div>

    <!-- Features Section -->
    <div class="bg-white border-t border-gray-100 py-12 mt-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 sm:gap-8">
                <div class="text-center">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 sm:w-7 sm:h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 text-sm sm:text-base">Qualidade Garantida</h3>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">Produtos certificados</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 sm:w-7 sm:h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 text-sm sm:text-base">Entrega Rápida</h3>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">Em todo Moçambique</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 bg-purple-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 sm:w-7 sm:h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 text-sm sm:text-base">Melhor Preço</h3>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">Preços competitivos</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 bg-orange-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 sm:w-7 sm:h-7 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 text-sm sm:text-base">Suporte 24/7</h3>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">Sempre disponíveis</p>
                </div>
            </div>
        </div>
    </div>
</div>
