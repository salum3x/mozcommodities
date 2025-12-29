<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
    <!-- Header Hero -->
    <div class="bg-gradient-to-r from-orange-600 to-orange-500 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-5xl font-extrabold mb-4">
                    Produtos de Fornecedores
                </h1>
                <p class="text-xl text-orange-100 max-w-3xl mx-auto">
                    Produtos de qualidade fornecidos pelos nossos parceiros verificados
                </p>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar - Categorias -->
            <div class="lg:w-64 flex-shrink-0">
                <div class="bg-white rounded-xl shadow-lg p-6 sticky top-4">
                    <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                        </svg>
                        Categorias
                    </h3>

                    <!-- Todas as categorias -->
                    <button wire:click="clearFilter"
                        class="w-full text-left px-4 py-3 rounded-lg mb-2 transition-all {{ $selectedCategory === null ? 'bg-orange-100 text-orange-800 font-bold' : 'hover:bg-gray-100 text-gray-700' }}">
                        <div class="flex items-center justify-between">
                            <span>Todas as Categorias</span>
                            <span class="text-sm bg-gray-200 px-2 py-1 rounded-full">{{ $products->count() }}</span>
                        </div>
                    </button>

                    <!-- Lista de categorias -->
                    @foreach($categories as $category)
                        @if($category->products_count > 0)
                            <button wire:click="filterByCategory({{ $category->id }})"
                                class="w-full text-left px-4 py-3 rounded-lg mb-2 transition-all {{ $selectedCategory == $category->id ? 'bg-orange-100 text-orange-800 font-bold' : 'hover:bg-gray-100 text-gray-700' }}">
                                <div class="flex items-center justify-between">
                                    <span>{{ $category->name }}</span>
                                    <span class="text-sm bg-gray-200 px-2 py-1 rounded-full">{{ $category->products_count }}</span>
                                </div>
                            </button>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Produtos Grid -->
            <div class="flex-1">
                @if($products->count() > 0)
                    <div class="mb-6">
                        <p class="text-gray-600">
                            Exibindo <span class="font-bold text-orange-600">{{ $products->count() }}</span>
                            {{ $products->count() === 1 ? 'produto' : 'produtos' }} de fornecedores
                        </p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($products as $product)
                            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 border-2 border-orange-200">
                                <!-- Badge Fornecedor -->
                                <div class="absolute top-4 left-4 z-10">
                                    <span class="bg-white text-orange-700 px-3 py-1 rounded-full text-xs font-bold shadow-lg border-2 border-orange-500">
                                        Fornecedor
                                    </span>
                                </div>

                                <!-- Imagem do Produto -->
                                <div class="relative h-64 bg-gradient-to-br from-orange-50 to-orange-100">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}"
                                             alt="{{ $product->name }}"
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="flex items-center justify-center h-full">
                                            <svg class="w-24 h-24 text-orange-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <!-- Info do Produto -->
                                <div class="p-6">
                                    <!-- Categoria -->
                                    <div class="mb-2">
                                        <span class="inline-block bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-xs font-bold">
                                            {{ $product->category->name }}
                                        </span>
                                    </div>

                                    <!-- Nome -->
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $product->name }}</h3>

                                    <!-- Fornecedor -->
                                    @if($product->supplier && $product->supplier->user)
                                        <p class="text-sm text-gray-500 mb-2">
                                            <span class="font-medium">Fornecedor:</span> {{ $product->supplier->company_name ?? $product->supplier->user->name }}
                                        </p>
                                    @endif

                                    <!-- Descrição -->
                                    @if($product->description)
                                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $product->description }}</p>
                                    @endif

                                    <!-- Preço e Stock -->
                                    <div class="flex items-center justify-between mb-4">
                                        <div>
                                            <p class="text-sm text-gray-600">Preço por kg</p>
                                            <p class="text-3xl font-bold text-orange-600">{{ number_format($product->price_per_kg, 2) }} <span class="text-lg">MZN</span></p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm text-gray-600">Stock</p>
                                            <p class="text-xl font-bold {{ $product->stock_kg > 0 ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $product->stock_kg }} kg
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Botões de Ação -->
                                    <div class="flex gap-2">
                                        <a href="{{ route('product.detail', $product->slug) }}"
                                           class="flex-1 bg-gradient-to-r from-orange-500 to-orange-600 text-white text-center py-3 rounded-lg font-bold hover:from-orange-600 hover:to-orange-700 transition-all shadow-lg">
                                            Ver Detalhes
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- Nenhum produto encontrado -->
                    <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Nenhum Produto de Fornecedor</h2>
                        <p class="text-gray-600 mb-6">
                            {{ $selectedCategory ? 'Não há produtos de fornecedores nesta categoria no momento.' : 'Não há produtos de fornecedores disponíveis no momento.' }}
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            @if($selectedCategory)
                                <button wire:click="clearFilter"
                                    class="inline-block bg-gray-100 text-gray-700 px-6 py-3 rounded-lg font-bold hover:bg-gray-200 transition-all">
                                    Ver Todos os Produtos
                                </button>
                            @endif
                            <a href="{{ route('our.products') }}"
                                class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-green-600 to-green-700 text-white px-6 py-3 rounded-lg font-bold hover:from-green-700 hover:to-green-800 transition-all shadow-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                Ver Nossos Produtos
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
