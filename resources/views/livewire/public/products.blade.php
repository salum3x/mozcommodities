<div class="min-h-screen bg-gray-100">

    @auth
    <!-- Top Bar Estilo AliExpress -->
    <div class="bg-white shadow-sm">
        <div class="max-w-[1400px] mx-auto px-4 py-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <span class="text-sm text-gray-600">Bem-vindo, <span class="font-semibold text-gray-900">{{ auth()->user()->name }}</span></span>
                    <span class="text-gray-300">|</span>
                    <a href="{{ route('cart') }}" class="relative flex items-center gap-2 px-4 py-2 bg-green-50 rounded-lg hover:bg-green-100 transition-all group">
                        <svg class="w-5 h-5 text-green-600 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span class="text-sm font-semibold text-gray-900">Carrinho</span>
                        @php
                            $cartCount = \App\Models\CartItem::where('user_id', auth()->id())->count();
                        @endphp
                        @if($cartCount > 0)
                            <span class="absolute -top-2 -right-2 bg-red-600 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center shadow-lg animate-pulse">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>
                </div>
                <div class="text-sm text-gray-600">
                    🚚 Entrega Grátis acima de 500 MT
                </div>
            </div>
        </div>
    </div>

    <!-- Search Bar Proeminente -->
    <div class="bg-gradient-to-r from-green-600 to-green-700 py-6">
        <div class="max-w-[1400px] mx-auto px-4">
            <div class="relative max-w-3xl mx-auto">
                <input type="text" wire:model.live="search"
                       class="w-full px-6 py-4 text-lg rounded-xl border-0 shadow-lg focus:ring-4 focus:ring-green-300"
                       placeholder="🔍 Pesquisar produtos agrícolas...">
            </div>
        </div>
    </div>
    @else
    <!-- Header Hero for Guests -->
    <div class="bg-gradient-to-r from-green-600 to-green-700 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-5xl font-extrabold mb-4">🌾 Todos os Produtos</h1>
                <p class="text-xl text-green-100 max-w-3xl mx-auto">
                    Produtos de alta qualidade da MozCommodities e fornecedores certificados
                </p>
            </div>
        </div>
    </div>
    @endauth

    <div class="max-w-[1400px] mx-auto px-4 py-6">
        <div class="flex gap-6">
            <!-- Sidebar Compacta -->
            <div class="hidden lg:block w-56 flex-shrink-0">
                <div class="bg-white rounded-lg shadow sticky top-4 overflow-hidden">
                    <!-- Header -->
                    <div class="bg-gray-50 px-4 py-3 border-b">
                        <h3 class="font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                            </svg>
                            Categorias
                        </h3>
                    </div>

                    <!-- Categories List -->
                    <div class="py-2">
                        <button wire:click="clearFilter"
                            class="w-full text-left px-4 py-2 text-sm {{ $selectedCategory === null ? 'bg-green-50 text-green-700 font-semibold border-l-4 border-green-600' : 'text-gray-700 hover:bg-gray-50' }}">
                            Todas as Categorias
                        </button>
                        @foreach($categories as $category)
                            @if($category->products_count > 0)
                                <button wire:click="filterByCategory({{ $category->id }})"
                                    class="w-full text-left px-4 py-2 text-sm {{ $selectedCategory == $category->id ? 'bg-green-50 text-green-700 font-semibold border-l-4 border-green-600' : 'text-gray-700 hover:bg-gray-50' }}">
                                    <div class="flex items-center justify-between">
                                        <span>{{ $category->name }}</span>
                                        <span class="text-xs text-gray-500">({{ $category->products_count }})</span>
                                    </div>
                                </button>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="flex-1">
                <!-- Resultados -->
                @if($products->count() > 0)
                    <!-- Header com Contador -->
                    <div class="mb-4 flex items-center justify-between">
                        <div class="text-sm text-gray-600">
                            <span class="font-semibold text-gray-900">{{ $products->count() }}</span> produtos encontrados
                        </div>
                    </div>

                    <!-- Grid de Produtos - Estilo AliExpress -->
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3">
                        @foreach($products as $product)
                            <div class="bg-white rounded-lg overflow-hidden hover:shadow-xl transition-all cursor-pointer group border border-gray-200">
                                <!-- Imagem -->
                                <div class="relative aspect-square bg-gray-50">
                                    @if($product->image_path)
                                        <img src="{{ asset('storage/' . $product->image_path) }}"
                                             alt="{{ $product->name }}"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    @else
                                        <div class="flex items-center justify-center h-full bg-gradient-to-br from-green-50 to-green-100">
                                            <svg class="w-16 h-16 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                            </svg>
                                        </div>
                                    @endif

                                    <!-- Badge -->
                                    @if($product->is_company_product)
                                        <div class="absolute top-2 left-2">
                                            <span class="bg-green-600 text-white px-2 py-1 rounded text-xs font-bold">NOSSO</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Info -->
                                <div class="p-3">
                                    <!-- Preço em Destaque -->
                                    <div class="mb-2">
                                        <div class="flex items-baseline gap-1">
                                            <span class="text-xs text-gray-500">MT</span>
                                            <span class="text-2xl font-bold text-red-600">{{ number_format($product->price_per_kg, 0) }}</span>
                                            <span class="text-xs text-gray-500">/kg</span>
                                        </div>
                                    </div>

                                    <!-- Nome do Produto -->
                                    <h3 class="text-sm text-gray-800 mb-1 line-clamp-2 group-hover:text-green-600">{{ $product->name }}</h3>

                                    <!-- Categoria Mini -->
                                    <div class="mb-2">
                                        <span class="text-xs text-gray-500">{{ $product->category->name }}</span>
                                    </div>

                                    <!-- Stock Badge -->
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="text-xs {{ $product->stock_kg > 50 ? 'text-green-600' : 'text-orange-600' }}">
                                            {{ $product->stock_kg > 50 ? '✓ Em Stock' : '⚠️ Pouco Stock' }}
                                        </span>
                                        <span class="text-xs text-gray-400">{{ $product->stock_kg }}kg</span>
                                    </div>

                                    <!-- Botão Comprar -->
                                    @livewire('public.add-to-cart', ['productId' => $product->id], key('cart-'.$product->id))
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Call to Action -->
                    @auth
                    <div class="mt-8 bg-gradient-to-r from-orange-500 to-red-500 rounded-lg p-6 text-white text-center">
                        <h3 class="text-2xl font-bold mb-2">🔥 Oferta Limitada!</h3>
                        <p class="mb-4">Faça o seu pedido hoje e receba com desconto especial</p>
                        <a href="{{ route('cart') }}" class="inline-block bg-white text-orange-600 px-8 py-3 rounded-lg font-bold hover:bg-gray-100 transition-all shadow-lg">
                            Ver Meu Carrinho →
                        </a>
                    </div>
                    @endauth
                @else
                    <!-- Nenhum produto -->
                    <div class="bg-white rounded-lg shadow p-12 text-center">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Nenhum produto encontrado</h2>
                        <p class="text-gray-600 mb-6">Tente ajustar os filtros ou fazer uma nova busca</p>
                        <div class="flex gap-4 justify-center">
                            @if($selectedCategory || $search)
                                <button wire:click="clearFilter"
                                    class="bg-gray-100 text-gray-700 px-6 py-3 rounded-lg font-bold hover:bg-gray-200">
                                    Limpar Filtros
                                </button>
                            @endif
                            <a href="{{ route('product.request') }}"
                                class="bg-green-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-green-700 shadow-lg">
                                Solicitar Produto
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
