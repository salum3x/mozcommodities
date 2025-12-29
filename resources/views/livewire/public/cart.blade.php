<div class="min-h-screen bg-gray-50">
    <!-- Breadcrumb -->
    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <nav class="flex items-center space-x-2 text-sm">
                <a href="{{ route('products') }}" class="text-gray-500 hover:text-green-600">Loja</a>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-gray-900 font-medium">Carrinho</span>
            </nav>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if (session()->has('message'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl flex items-center">
                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('message') }}
            </div>
        @endif

        @if(count($cartItems) > 0)
            <!-- Header com contador -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Carrinho de Compras</h1>
                    <p class="text-gray-500 mt-1">{{ $cartItems->sum('quantity') }} {{ $cartItems->sum('quantity') == 1 ? 'item' : 'itens' }} no carrinho</p>
                </div>
                <button wire:click="clearCart"
                        wire:confirm="Tem certeza que deseja limpar o carrinho?"
                        class="text-sm text-gray-500 hover:text-red-600 flex items-center transition">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Limpar tudo
                </button>
            </div>

            <div class="lg:grid lg:grid-cols-12 lg:gap-8">
                <!-- Lista de Produtos -->
                <div class="lg:col-span-8">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <!-- Header da tabela (desktop) -->
                        <div class="hidden sm:grid sm:grid-cols-12 gap-4 px-6 py-4 bg-gray-50 border-b text-sm font-medium text-gray-500">
                            <div class="col-span-6">Produto</div>
                            <div class="col-span-2 text-center">Preço</div>
                            <div class="col-span-2 text-center">Quantidade</div>
                            <div class="col-span-2 text-right">Subtotal</div>
                        </div>

                        <!-- Items do carrinho -->
                        <div class="divide-y divide-gray-100">
                            @foreach($cartItems as $item)
                                <div class="p-4 sm:p-6 hover:bg-gray-50/50 transition" wire:key="cart-item-{{ $item->id }}">
                                    <div class="sm:grid sm:grid-cols-12 sm:gap-4 sm:items-center">
                                        <!-- Produto (imagem + info) -->
                                        <div class="col-span-6 flex items-center gap-4">
                                            <div class="relative flex-shrink-0">
                                                <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-xl bg-gray-100 overflow-hidden">
                                                    @if($item->product->image)
                                                        <img src="{{ asset('storage/' . $item->product->image) }}"
                                                             alt="{{ $item->product->name }}"
                                                             class="w-full h-full object-cover">
                                                    @else
                                                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-green-50 to-green-100">
                                                            <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-xs text-green-600 font-medium mb-1">{{ $item->product->category->name ?? 'Sem categoria' }}</p>
                                                <h3 class="font-semibold text-gray-900 truncate">{{ $item->product->name }}</h3>
                                                <p class="text-sm text-gray-500 mt-1">Fornecedor: {{ $item->product->supplier->company_name ?? 'N/A' }}</p>
                                                <!-- Preço mobile -->
                                                <p class="sm:hidden text-green-600 font-bold mt-2">{{ number_format($item->price_per_kg, 2, ',', '.') }} MT/{{ $item->product->unit ?? 'kg' }}</p>
                                            </div>
                                        </div>

                                        <!-- Preço (desktop) -->
                                        <div class="hidden sm:block col-span-2 text-center">
                                            <span class="font-semibold text-gray-900">{{ number_format($item->price_per_kg, 2, ',', '.') }} MT</span>
                                            <span class="text-gray-500 text-sm">/{{ $item->product->unit ?? 'kg' }}</span>
                                        </div>

                                        <!-- Quantidade -->
                                        <div class="col-span-2 flex items-center justify-center mt-4 sm:mt-0">
                                            <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden">
                                                <button wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})"
                                                        class="w-10 h-10 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition {{ $item->quantity <= 1 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                        {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                                    </svg>
                                                </button>
                                                <input type="number"
                                                       value="{{ $item->quantity }}"
                                                       min="1"
                                                       wire:change="updateQuantity({{ $item->id }}, $event.target.value)"
                                                       class="w-12 h-10 text-center border-x border-gray-200 font-semibold text-gray-900 focus:outline-none">
                                                <button wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})"
                                                        class="w-10 h-10 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Subtotal + Remover -->
                                        <div class="col-span-2 flex items-center justify-between sm:justify-end gap-4 mt-4 sm:mt-0">
                                            <span class="font-bold text-gray-900 text-lg">
                                                {{ number_format($item->quantity * $item->price_per_kg, 2, ',', '.') }} MT
                                            </span>
                                            <button wire:click="removeItem({{ $item->id }})"
                                                    class="text-gray-400 hover:text-red-500 transition p-2 -mr-2"
                                                    title="Remover">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Continuar comprando -->
                    <a href="{{ route('products') }}" class="inline-flex items-center text-green-600 hover:text-green-700 font-medium mt-6 group">
                        <svg class="w-5 h-5 mr-2 transform group-hover:-translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Continuar comprando
                    </a>
                </div>

                <!-- Resumo do Pedido -->
                <div class="lg:col-span-4 mt-8 lg:mt-0">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:sticky lg:top-24">
                        <h2 class="text-lg font-bold text-gray-900 mb-6">Resumo do Pedido</h2>

                        <!-- Detalhes -->
                        <div class="space-y-4 text-sm">
                            <div class="flex justify-between text-gray-600">
                                <span>Subtotal ({{ $cartItems->sum('quantity') }} itens)</span>
                                <span class="font-medium text-gray-900">{{ number_format($total, 2, ',', '.') }} MT</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Entrega</span>
                                <span class="text-green-600 font-medium">A calcular</span>
                            </div>
                        </div>

                        <!-- Cupom de desconto -->
                        <div class="mt-6 pt-6 border-t border-gray-100">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Cupom de desconto</label>
                            <div class="flex gap-2">
                                <input type="text"
                                       placeholder="Digite o código"
                                       class="flex-1 px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <button class="px-4 py-2.5 bg-gray-100 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-200 transition">
                                    Aplicar
                                </button>
                            </div>
                        </div>

                        <!-- Total -->
                        <div class="mt-6 pt-6 border-t border-gray-100">
                            <div class="flex justify-between items-center mb-6">
                                <span class="text-lg font-bold text-gray-900">Total</span>
                                <span class="text-2xl font-bold text-green-600">{{ number_format($total, 2, ',', '.') }} MT</span>
                            </div>

                            <a href="{{ route('checkout.cart') }}"
                               class="block w-full py-4 bg-green-600 text-white text-center font-bold rounded-xl hover:bg-green-700 transition shadow-lg shadow-green-600/30">
                                Finalizar Compra
                            </a>

                            <!-- Métodos de pagamento -->
                            <div class="mt-6 pt-6 border-t border-gray-100">
                                <p class="text-xs text-gray-500 text-center mb-3">Métodos de pagamento aceitos</p>
                                <div class="flex justify-center gap-2">
                                    <div class="w-12 h-8 bg-gray-100 rounded flex items-center justify-center">
                                        <span class="text-xs font-bold text-gray-600">M-Pesa</span>
                                    </div>
                                    <div class="w-12 h-8 bg-gray-100 rounded flex items-center justify-center">
                                        <span class="text-xs font-bold text-gray-600">e-Mola</span>
                                    </div>
                                    <div class="w-12 h-8 bg-gray-100 rounded flex items-center justify-center">
                                        <span class="text-xs font-bold text-gray-600">Visa</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Garantias -->
                        <div class="mt-6 space-y-3">
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-5 h-5 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                                Compra 100% segura
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-5 h-5 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                                Pagamento facilitado
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-5 h-5 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Entrega agendada
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Carrinho Vazio -->
            <div class="max-w-lg mx-auto text-center py-16">
                <div class="w-32 h-32 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-8">
                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-3">Seu carrinho está vazio</h2>
                <p class="text-gray-500 mb-8">Parece que você ainda não adicionou nenhum produto ao carrinho. Explore nossa loja e encontre produtos incríveis!</p>
                <a href="{{ route('products') }}"
                   class="inline-flex items-center px-8 py-4 bg-green-600 text-white font-bold rounded-xl hover:bg-green-700 transition shadow-lg shadow-green-600/30">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Explorar Produtos
                </a>

                <!-- Sugestões -->
                <div class="mt-12 pt-8 border-t border-gray-200">
                    <p class="text-sm text-gray-500 mb-4">Ou navegue por categoria</p>
                    <div class="flex flex-wrap justify-center gap-2">
                        <a href="{{ route('products') }}" class="px-4 py-2 bg-white border border-gray-200 rounded-full text-sm text-gray-700 hover:border-green-500 hover:text-green-600 transition">Cereais</a>
                        <a href="{{ route('products') }}" class="px-4 py-2 bg-white border border-gray-200 rounded-full text-sm text-gray-700 hover:border-green-500 hover:text-green-600 transition">Legumes</a>
                        <a href="{{ route('products') }}" class="px-4 py-2 bg-white border border-gray-200 rounded-full text-sm text-gray-700 hover:border-green-500 hover:text-green-600 transition">Frutas</a>
                        <a href="{{ route('products') }}" class="px-4 py-2 bg-white border border-gray-200 rounded-full text-sm text-gray-700 hover:border-green-500 hover:text-green-600 transition">Oleaginosas</a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
