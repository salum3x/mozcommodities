<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
    <!-- Header Hero with Category Info -->
    <div class="bg-gradient-to-r from-green-700 via-green-600 to-emerald-600 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <!-- Breadcrumb -->
                    <nav class="flex items-center gap-2 text-green-100 text-sm mb-3">
                        <a href="{{ route('home') }}" class="hover:text-white transition">Início</a>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                        <a href="{{ route('products') }}" class="hover:text-white transition">Produtos</a>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                        <span class="text-white font-medium">{{ $category->name }}</span>
                    </nav>

                    <h1 class="text-4xl md:text-5xl font-extrabold mb-2">
                        {{ $category->name }}
                    </h1>
                    @if($category->description)
                        <p class="text-lg text-green-100 max-w-2xl">
                            {{ $category->description }}
                        </p>
                    @endif
                </div>

                <div class="flex items-center gap-3">
                    <div class="bg-white/20 backdrop-blur-sm rounded-xl px-6 py-4 text-center">
                        <p class="text-3xl font-bold">{{ $products->count() }}</p>
                        <p class="text-sm text-green-100">{{ $products->count() === 1 ? 'Produto' : 'Produtos' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar - Categorias -->
            <div class="lg:w-64 flex-shrink-0">
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden sticky top-4">
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-green-600 to-green-700 px-5 py-4">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                            </svg>
                            Categorias
                        </h3>
                    </div>

                    <!-- Categories List -->
                    <div class="p-3">
                        <!-- All Products Link -->
                        <a href="{{ route('products') }}"
                           class="flex items-center justify-between px-4 py-3 rounded-xl mb-1 transition-all text-gray-600 hover:bg-gray-100">
                            <span class="font-medium">Todos os Produtos</span>
                        </a>

                        @foreach($categories as $cat)
                            <a href="{{ route('category.products', $cat->slug) }}"
                               class="flex items-center justify-between px-4 py-3 rounded-xl mb-1 transition-all {{ $category->id === $cat->id ? 'bg-green-100 text-green-800 font-bold' : 'text-gray-600 hover:bg-gray-100' }}">
                                <span>{{ $cat->name }}</span>
                                <span class="text-xs px-2 py-1 rounded-full {{ $category->id === $cat->id ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-600' }}">
                                    {{ $cat->products_count }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="flex-1">
                <!-- Filters Bar -->
                <div class="bg-white rounded-xl shadow-sm p-4 mb-6 flex flex-col sm:flex-row gap-4 items-center justify-between">
                    <!-- Search -->
                    <div class="relative flex-1 max-w-md">
                        <input type="text" wire:model.live.debounce.300ms="search"
                               placeholder="Pesquisar em {{ $category->name }}..."
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>

                    <!-- Sort -->
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-500">Ordenar:</span>
                        <div class="flex rounded-lg border border-gray-300 overflow-hidden">
                            <button wire:click="setSortBy('latest')"
                                    class="px-3 py-2 text-sm {{ $sortBy === 'latest' ? 'bg-green-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-100' }} transition">
                                Recentes
                            </button>
                            <button wire:click="setSortBy('price_asc')"
                                    class="px-3 py-2 text-sm border-l {{ $sortBy === 'price_asc' ? 'bg-green-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-100' }} transition">
                                Menor Preço
                            </button>
                            <button wire:click="setSortBy('price_desc')"
                                    class="px-3 py-2 text-sm border-l {{ $sortBy === 'price_desc' ? 'bg-green-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-100' }} transition">
                                Maior Preço
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Products Grid -->
                @if($products->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($products as $product)
                            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 group">
                                <!-- Product Image -->
                                <div class="relative h-56 bg-gradient-to-br from-gray-100 to-gray-200 overflow-hidden">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}"
                                             alt="{{ $product->name }}"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    @else
                                        <div class="flex items-center justify-center h-full">
                                            <svg class="w-20 h-20 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif

                                    <!-- Badges -->
                                    <div class="absolute top-3 left-3 flex flex-col gap-2">
                                        @if($product->is_company_product)
                                            <span class="bg-green-600 text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg">
                                                Nosso Produto
                                            </span>
                                        @else
                                            <span class="bg-orange-500 text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg">
                                                Fornecedor
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Stock Badge -->
                                    <div class="absolute top-3 right-3">
                                        @if($product->stock_kg > 0)
                                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">
                                                {{ $product->stock_kg }} kg
                                            </span>
                                        @else
                                            <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-medium">
                                                Esgotado
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Product Info -->
                                <div class="p-5">
                                    <h3 class="text-lg font-bold text-gray-900 mb-1 line-clamp-1">{{ $product->name }}</h3>

                                    @if($product->supplier && $product->supplier->company_name)
                                        <p class="text-sm text-gray-500 mb-3">
                                            {{ $product->supplier->company_name }}
                                        </p>
                                    @endif

                                    <!-- Price -->
                                    <div class="flex items-end justify-between mb-4">
                                        <div>
                                            <p class="text-xs text-gray-500">Preço por kg</p>
                                            <p class="text-2xl font-bold text-green-600">
                                                {{ number_format($product->price_per_kg, 2) }}
                                                <span class="text-sm font-normal text-gray-500">MZN</span>
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Action Button -->
                                    <a href="{{ route('product.detail', $product->slug) }}"
                                       class="block w-full text-center bg-gradient-to-r from-green-600 to-green-700 text-white py-3 rounded-xl font-bold hover:from-green-700 hover:to-green-800 transition-all shadow-lg hover:shadow-xl">
                                        Ver Detalhes
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Nenhum Produto Encontrado</h2>
                        <p class="text-gray-600 mb-6">
                            @if($search)
                                Não encontrámos produtos para "{{ $search }}" em {{ $category->name }}.
                            @else
                                Ainda não há produtos disponíveis nesta categoria.
                            @endif
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            @if($search)
                                <button wire:click="$set('search', '')"
                                        class="inline-block bg-gray-100 text-gray-700 px-6 py-3 rounded-lg font-bold hover:bg-gray-200 transition-all">
                                    Limpar Pesquisa
                                </button>
                            @endif
                            <a href="{{ route('products') }}"
                               class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-green-600 to-green-700 text-white px-6 py-3 rounded-lg font-bold hover:from-green-700 hover:to-green-800 transition-all shadow-lg">
                                Ver Todos os Produtos
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
