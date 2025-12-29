<div>
    <x-public-nav />

    <div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-extrabold text-gray-900">Carrinho de Compras</h1>
            <p class="mt-2 text-gray-600">Revise seus produtos antes de finalizar a compra</p>
        </div>

        @if (session()->has('message'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                {{ session('message') }}
            </div>
        @endif

        @if(count($cartItems) > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-2 space-y-4">
                    @foreach($cartItems as $item)
                        <div class="bg-white rounded-xl shadow-md p-6 flex items-center gap-6">
                            <!-- Product Image/Icon -->
                            <div class="flex-shrink-0">
                                <div class="w-24 h-24 bg-gradient-to-br from-green-100 to-green-200 rounded-lg flex items-center justify-center">
                                    <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                            </div>

                            <!-- Product Info -->
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-gray-900">{{ $item->product->name }}</h3>
                                <p class="text-sm text-gray-600">{{ $item->product->category->name }}</p>
                                <p class="text-green-600 font-bold mt-2">
                                    {{ number_format($item->price_per_kg, 2) }} MZN/kg
                                </p>
                            </div>

                            <!-- Quantity Controls -->
                            <div class="flex items-center gap-3">
                                <button wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})"
                                    class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-700 font-bold transition-all">
                                    -
                                </button>
                                <input type="number" value="{{ $item->quantity }}" min="1"
                                    wire:change="updateQuantity({{ $item->id }}, $event.target.value)"
                                    class="w-16 text-center border border-gray-300 rounded-lg py-2 font-bold">
                                <button wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})"
                                    class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-700 font-bold transition-all">
                                    +
                                </button>
                            </div>

                            <!-- Subtotal -->
                            <div class="text-right">
                                <p class="text-lg font-bold text-gray-900">
                                    {{ number_format($item->quantity * $item->price_per_kg, 2) }} MZN
                                </p>
                                <button wire:click="removeItem({{ $item->id }})"
                                    class="text-sm text-red-600 hover:text-red-800 font-medium mt-2">
                                    Remover
                                </button>
                            </div>
                        </div>
                    @endforeach

                    <!-- Clear Cart Button -->
                    <div class="flex justify-end">
                        <button wire:click="clearCart"
                            wire:confirm="Tem certeza que deseja limpar o carrinho?"
                            class="px-6 py-2 text-red-600 hover:bg-red-50 border border-red-300 rounded-lg font-medium transition-all">
                            Limpar Carrinho
                        </button>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-lg p-6 sticky top-24">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Resumo do Pedido</h2>

                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between text-gray-700">
                                <span>Subtotal</span>
                                <span class="font-semibold">{{ number_format($total, 2) }} MZN</span>
                            </div>
                            <div class="flex justify-between text-gray-700">
                                <span>Total de itens</span>
                                <span class="font-semibold">{{ $cartItems->sum('quantity') }}</span>
                            </div>
                        </div>

                        <div class="border-t border-gray-200 pt-4 mb-6">
                            <div class="flex justify-between text-lg font-bold text-gray-900">
                                <span>Total</span>
                                <span class="text-green-600">{{ number_format($total, 2) }} MZN</span>
                            </div>
                        </div>

                        <a href="{{ route('checkout.cart') }}"
                            class="block w-full py-3 bg-gradient-to-r from-green-600 to-green-700 text-white text-center font-bold rounded-lg hover:from-green-700 hover:to-green-800 transition-all shadow-lg">
                            Finalizar Compra
                        </a>

                        <a href="{{ route('products') }}"
                            class="block w-full mt-3 py-3 bg-gray-100 text-gray-700 text-center font-medium rounded-lg hover:bg-gray-200 transition-all">
                            Continuar Comprando
                        </a>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty Cart -->
            <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Seu carrinho está vazio</h2>
                <p class="text-gray-600 mb-8">Adicione produtos ao carrinho para continuar</p>
                <a href="{{ route('products') }}"
                    class="inline-block px-8 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white font-bold rounded-lg hover:from-green-700 hover:to-green-800 transition-all shadow-lg">
                    Ver Produtos
                </a>
            </div>
        @endif
    </div>
    </div>
</div>
