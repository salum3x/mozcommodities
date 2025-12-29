<div class="min-h-screen bg-gray-50" x-data="{ mobileMenuOpen: false }">
    <!-- Navigation Bar -->
    <nav class="absolute top-0 left-0 right-0 z-50 bg-white/10 backdrop-blur-md border-b border-white/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-2 text-white font-bold text-xl">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <span>Moz<span class="text-green-200">Commodities</span></span>
                </a>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center gap-6">
                    <a href="{{ route('products') }}" class="text-white hover:text-green-200 transition-colors">Produtos</a>
                    <a href="{{ route('quote.form') }}" class="text-white hover:text-green-200 transition-colors">Cotação</a>

                    @guest
                        <div class="flex items-center gap-3 ml-4 pl-4 border-l border-white/20">
                            <a href="{{ route('register.customer') }}"
                               class="px-4 py-2 bg-white/10 text-white rounded-lg hover:bg-white/20 transition-all border border-white/20">
                                Registar como Cliente
                            </a>
                            <a href="{{ route('register.supplier') }}"
                               class="px-4 py-2 bg-white text-green-700 rounded-lg hover:bg-green-50 transition-all font-semibold">
                                Registar como Fornecedor
                            </a>
                            <a href="{{ route('login') }}"
                               class="px-4 py-2 text-white hover:text-green-200 transition-colors">
                                Entrar
                            </a>
                        </div>
                    @else
                        <a href="{{ route('dashboard') }}"
                           class="px-4 py-2 bg-white text-green-700 rounded-lg hover:bg-green-50 transition-all font-semibold">
                            Dashboard
                        </a>
                    @endguest
                </div>

                <!-- Mobile Menu Button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

            <!-- Mobile Menu -->
            <div x-show="mobileMenuOpen" @click.away="mobileMenuOpen = false" class="md:hidden pb-4">
                <div class="space-y-2">
                    <a href="{{ route('products') }}" class="block px-4 py-2 text-white hover:bg-white/10 rounded-lg">Produtos</a>
                    <a href="{{ route('quote.form') }}" class="block px-4 py-2 text-white hover:bg-white/10 rounded-lg">Cotação</a>

                    @guest
                        <div class="pt-2 mt-2 border-t border-white/20 space-y-2">
                            <a href="{{ route('register.customer') }}" class="block px-4 py-2 bg-white/10 text-white rounded-lg text-center">
                                Registar como Cliente
                            </a>
                            <a href="{{ route('register.supplier') }}" class="block px-4 py-2 bg-white text-green-700 rounded-lg font-semibold text-center">
                                Registar como Fornecedor
                            </a>
                            <a href="{{ route('login') }}" class="block px-4 py-2 text-white hover:bg-white/10 rounded-lg text-center">
                                Entrar
                            </a>
                        </div>
                    @else
                        <a href="{{ route('dashboard') }}" class="block px-4 py-2 bg-white text-green-700 rounded-lg font-semibold text-center mt-2">
                            Dashboard
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative overflow-hidden bg-gradient-to-br from-green-600 via-green-700 to-green-800">
        <!-- Pattern overlay -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute transform rotate-45 -left-48 -top-48 w-96 h-96 bg-white rounded-full blur-3xl"></div>
            <div class="absolute transform -rotate-45 -right-48 -bottom-48 w-96 h-96 bg-white rounded-full blur-3xl"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Content -->
                <div class="text-white space-y-6">
                    <div class="inline-flex items-center px-4 py-2 bg-white/10 backdrop-blur-md rounded-full border border-white/20">
                        <span class="text-sm font-semibold">🌾 Líder em Commodities Agrícolas</span>
                    </div>

                    <h1 class="text-5xl lg:text-6xl font-black leading-tight">
                        Bem-vindo à<br/>
                        <span class="text-green-200">MozCommodities</span>
                    </h1>

                    <p class="text-xl text-green-50 leading-relaxed">
                        Somos uma empresa moçambicana especializada na comercialização de produtos agrícolas de alta qualidade. Conectamos produtores e compradores, oferecendo os melhores preços do mercado.
                    </p>

                    <div class="flex flex-wrap gap-4 pt-4">
                        <a href="{{ route('products') }}"
                           class="inline-flex items-center px-8 py-4 bg-white text-green-700 rounded-xl font-bold hover:bg-green-50 transition-all shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                            Ver Produtos
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </a>
                        <a href="{{ route('quote.form') }}"
                           class="inline-flex items-center px-8 py-4 bg-white/10 backdrop-blur-md text-white rounded-xl font-bold border-2 border-white/30 hover:bg-white hover:text-green-700 transition-all">
                            Pedir Cotação
                        </a>
                    </div>
                </div>

                <!-- Stats -->
                <div class="hidden lg:block">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20">
                            <div class="text-4xl mb-2">🌾</div>
                            <div class="text-3xl font-bold text-white">{{ \App\Models\Product::count() }}+</div>
                            <div class="text-green-100">Produtos</div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20">
                            <div class="text-4xl mb-2">🤝</div>
                            <div class="text-3xl font-bold text-white">{{ \App\Models\Supplier::count() }}+</div>
                            <div class="text-green-100">Fornecedores</div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20">
                            <div class="text-4xl mb-2">⭐</div>
                            <div class="text-3xl font-bold text-white">5+</div>
                            <div class="text-green-100">Anos</div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20">
                            <div class="text-4xl mb-2">🚚</div>
                            <div class="text-3xl font-bold text-white">24h</div>
                            <div class="text-green-100">Entrega</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Categorias de Produtos</h2>
            <p class="text-gray-600">Explore nossa ampla variedade de produtos agrícolas</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($categories as $category)
            <a href="{{ route('products') }}?category={{ $category->id }}"
               class="group bg-white rounded-xl p-6 text-center hover:shadow-lg transition-all border-2 border-gray-100 hover:border-green-500">
                <div class="w-16 h-16 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-3 group-hover:bg-green-500 transition-colors">
                    <svg class="w-8 h-8 text-green-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 mb-1">{{ $category->name }}</h3>
                <p class="text-sm text-gray-500">{{ $category->products_count }} produtos</p>
            </a>
            @endforeach
        </div>
    </div>

    <!-- How it Works -->
    <div class="relative bg-gradient-to-b from-white to-gray-50 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-2 bg-green-100 text-green-700 rounded-full text-sm font-semibold mb-4">
                    Processo Simples
                </span>
                <h2 class="text-4xl font-bold text-gray-900 mb-3">Como Funciona</h2>
                <p class="text-xl text-gray-600">Compre em 4 passos simples e receba em casa</p>
            </div>

            <div class="relative">
                <!-- Connection Line -->
                <div class="hidden lg:block absolute top-24 left-0 right-0 h-1 bg-gradient-to-r from-green-200 via-green-300 to-green-200" style="margin: 0 12.5%;"></div>

                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-6">
                    <!-- Step 1 -->
                    <div class="relative group">
                        <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 border-2 border-gray-100 hover:border-green-500">
                            <div class="flex justify-center mb-6">
                                <div class="w-24 h-24 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center transform group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-xl">
                                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3 text-center">Pesquise o Produto</h3>
                            <p class="text-gray-600 text-center leading-relaxed">Navegue pelo nosso catálogo e encontre exatamente o que precisa</p>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="relative group">
                        <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 border-2 border-gray-100 hover:border-green-500">
                            <div class="flex justify-center mb-6">
                                <div class="w-24 h-24 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center transform group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-xl">
                                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3 text-center">Adicione ao Carrinho</h3>
                            <p class="text-gray-600 text-center leading-relaxed">Selecione a quantidade e adicione produtos ao seu carrinho</p>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="relative group">
                        <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 border-2 border-gray-100 hover:border-green-500">
                            <div class="flex justify-center mb-6">
                                <div class="w-24 h-24 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center transform group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-xl">
                                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3 text-center">Finalize o Pagamento</h3>
                            <p class="text-gray-600 text-center leading-relaxed">Escolha a forma de pagamento e confirme o seu pedido</p>
                        </div>
                    </div>

                    <!-- Step 4 -->
                    <div class="relative group">
                        <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 border-2 border-gray-100 hover:border-green-500">
                            <div class="flex justify-center mb-6">
                                <div class="w-24 h-24 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center transform group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-xl">
                                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                    </svg>
                                </div>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3 text-center">Receba em Casa</h3>
                            <p class="text-gray-600 text-center leading-relaxed">Entregamos na sua localização de forma rápida e segura</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CTA Button -->
            <div class="text-center mt-12">
                <a href="{{ route('products') }}"
                   class="inline-flex items-center px-8 py-4 bg-green-600 text-white rounded-xl font-bold hover:bg-green-700 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    Começar a Comprar
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Products -->
    <div class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-10">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Produtos em Destaque</h2>
                    <p class="text-gray-600">Os melhores preços do mercado</p>
                </div>
                <a href="{{ route('products') }}" class="inline-flex items-center text-green-600 hover:text-green-700 font-semibold">
                    Ver todos
                    <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
            </div>

            @if($products->count() > 0)
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($products as $product)
                <div class="bg-white rounded-xl overflow-hidden hover:shadow-xl transition-all border border-gray-200">
                    <div class="relative h-48 bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center">
                        <svg class="w-20 h-20 text-white opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        @if($product->is_company_product)
                        <div class="absolute top-3 right-3 px-3 py-1 bg-white text-green-700 text-xs font-bold rounded-full">
                            Nosso Produto
                        </div>
                        @endif
                    </div>
                    <div class="p-5">
                        <span class="inline-block px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded mb-2">
                            {{ $product->category->name }}
                        </span>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $product->name }}</h3>
                        <div class="flex items-baseline mb-4">
                            <span class="text-2xl font-bold text-green-600">{{ number_format($product->price_per_kg, 2) }}</span>
                            <span class="text-gray-600 ml-1">MT/{{ $product->unit }}</span>
                        </div>
                        @livewire('public.add-to-cart', ['productId' => $product->id], key('cart-'.$product->id))
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-12 bg-white rounded-xl">
                <p class="text-gray-500">Produtos estarão disponíveis em breve</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Benefits -->
    <div class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Por Que Escolher a MozCommodities?</h2>
                <p class="text-gray-600">A sua escolha número 1 em commodities agrícolas</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">Qualidade Garantida</h3>
                    <p class="text-sm text-gray-600">Produtos certificados e inspecionados</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">Melhores Preços</h3>
                    <p class="text-sm text-gray-600">Preços competitivos e justos</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">Entrega Rápida</h3>
                    <p class="text-sm text-gray-600">24-48h em todo Moçambique</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA -->
    <div class="bg-gradient-to-r from-green-600 to-green-700 py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
            <h2 class="text-3xl font-bold mb-4">É Fornecedor de Produtos Agrícolas?</h2>
            <p class="text-xl text-green-100 mb-8">Junte-se à nossa plataforma e expanda o seu negócio</p>
            <a href="{{ route('register') }}"
               class="inline-flex items-center px-8 py-4 bg-white text-green-700 rounded-xl font-bold hover:bg-green-50 transition-all shadow-xl">
                Registar como Fornecedor
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </a>
        </div>
    </div>

    <x-whatsapp-button />
</div>
