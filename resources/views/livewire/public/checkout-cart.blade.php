<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900">Finalizar Compra</h1>
            <p class="text-gray-600 mt-2">Complete seu pedido</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Form -->
            <div class="lg:col-span-2">
                <form wire:submit.prevent="placeOrder" class="space-y-6">
                    <!-- Cart Items Summary -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Seus Produtos</h2>
                        <div class="space-y-3">
                            @foreach($cartItems as $item)
                                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $item->product->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $item->quantity }} kg × {{ number_format($item->price_per_kg, 2) }} MZN</p>
                                    </div>
                                    <p class="font-bold text-green-600">{{ number_format($item->quantity * $item->price_per_kg, 2) }} MZN</p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Customer Info -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Seus Dados</h2>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nome Completo</label>
                                <input type="text" wire:model="customer_name" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                                @error('customer_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                                <input type="email" wire:model="customer_email" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                                @error('customer_email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Telefone (M-Pesa)</label>
                                <input type="text" wire:model="customer_phone" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                                    placeholder="+258 84 000 0000">
                                @error('customer_phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Endereço (opcional)</label>
                                <textarea wire:model="customer_address" rows="2"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Payment -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Como deseja pagar?</h2>

                        <div class="space-y-4">
                            <!-- M-Pesa -->
                            <label class="flex items-start p-4 border-2 rounded-lg cursor-pointer {{ $payment_method === 'mpesa' ? 'border-green-500 bg-green-50' : 'border-gray-200' }}">
                                <input type="radio" wire:model.live="payment_method" value="mpesa" name="payment_method" class="mt-1">
                                <div class="ml-4">
                                    <span class="text-lg font-bold">M-Pesa</span>
                                    <span class="ml-2 px-2 py-1 bg-green-100 text-green-800 text-xs font-bold rounded">Rápido</span>
                                    <p class="text-sm text-gray-600 mt-1">Pague instantaneamente pelo celular</p>
                                </div>
                            </label>

                            <!-- Bank -->
                            <label class="flex items-start p-4 border-2 rounded-lg cursor-pointer {{ $payment_method === 'bank_transfer' ? 'border-green-500 bg-green-50' : 'border-gray-200' }}">
                                <input type="radio" wire:model.live="payment_method" value="bank_transfer" name="payment_method" class="mt-1">
                                <div class="ml-4">
                                    <span class="text-lg font-bold">Transferência Bancária</span>
                                    <p class="text-sm text-gray-600 mt-1">Transfira e envie comprovante</p>
                                </div>
                            </label>

                            @if($payment_method === 'bank_transfer')
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <p class="font-semibold text-blue-900 mb-2">Dados Bancários:</p>
                                    <p class="text-sm text-blue-800">Banco: {{ \App\Models\Setting::get('bank_name', 'N/A') }}</p>
                                    <p class="text-sm text-blue-800">NIB: {{ \App\Models\Setting::get('bank_nib', 'N/A') }}</p>
                                    <p class="text-sm text-blue-800">Titular: {{ \App\Models\Setting::get('bank_account_holder', 'N/A') }}</p>

                                    <div class="mt-4">
                                        <label class="block font-semibold text-gray-700 mb-2">Comprovante</label>
                                        <input type="file" wire:model="payment_proof" accept="image/*" required
                                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                                        @error('payment_proof') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            @endif

                            @if($payment_method === 'mpesa')
                                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                    <p class="font-semibold text-green-900 mb-2">Como pagar:</p>
                                    <ol class="list-decimal list-inside text-sm text-green-800 space-y-1">
                                        <li>Abra o M-Pesa no seu celular</li>
                                        <li>Escolha "Enviar Dinheiro"</li>
                                        <li>Número: <strong>{{ \App\Models\Setting::get('mpesa_number', '+258 84 000 0000') }}</strong></li>
                                        <li>Valor: <strong>{{ number_format($total, 2) }} MZN</strong></li>
                                        <li>Confirme o pagamento</li>
                                    </ol>
                                </div>
                            @endif

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Observações (opcional)</label>
                                <textarea wire:model="notes" rows="2" placeholder="Ex: Preferência de entrega..."
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"></textarea>
                            </div>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-gradient-to-r from-green-600 to-green-700 text-white py-4 rounded-lg font-bold text-lg hover:from-green-700 hover:to-green-800 transition-all shadow-lg">
                        Confirmar Pedido
                    </button>
                </form>
            </div>

            <!-- Order Summary Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-lg p-6 sticky top-24">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Resumo do Pedido</h2>

                    <div class="space-y-3 mb-4">
                        <div class="flex justify-between text-gray-700">
                            <span>Total de itens</span>
                            <span class="font-semibold">{{ $cartItems->sum('quantity') }} kg</span>
                        </div>
                        <div class="flex justify-between text-gray-700">
                            <span>Subtotal</span>
                            <span class="font-semibold">{{ number_format($total, 2) }} MZN</span>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 pt-4 mb-6">
                        <div class="flex justify-between text-xl font-bold text-gray-900">
                            <span>Total</span>
                            <span class="text-green-600">{{ number_format($total, 2) }} MZN</span>
                        </div>
                    </div>

                    <div class="text-sm text-gray-600 space-y-2">
                        <p class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Pagamento seguro
                        </p>
                        <p class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Entrega rápida
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
