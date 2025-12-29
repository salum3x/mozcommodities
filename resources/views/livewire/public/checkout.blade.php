<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900">Finalizar Compra</h1>
            <p class="text-gray-600 mt-2">Preencha os dados para comprar {{ $product->name }}</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Form -->
            <div class="lg:col-span-2">
                <form wire:submit.prevent="placeOrder" class="space-y-6">
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
                                            class="w-full px-4 py-2 border rounded-lg">
                                        @error('payment_proof') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            @endif

                            @if($payment_method === 'mpesa')
                                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                    <p class="font-semibold text-green-900 mb-2">Instruções:</p>
                                    <ol class="text-sm text-green-800 space-y-1 list-decimal list-inside">
                                        <li>Abra M-Pesa</li>
                                        <li>Enviar Dinheiro</li>
                                        <li>Número: <strong>{{ \App\Models\Setting::get('mpesa_number', '+258 84 000 0000') }}</strong></li>
                                        <li>Valor: <strong>{{ number_format($this->total, 2) }} MT</strong></li>
                                    </ol>
                                </div>
                            @endif
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-green-600 text-white py-4 rounded-lg font-bold text-lg hover:bg-green-700">
                        Confirmar Pedido
                    </button>
                </form>
            </div>

            <!-- Summary -->
            <div>
                <div class="bg-white rounded-xl shadow-lg p-6 sticky top-24">
                    <h2 class="text-xl font-bold mb-4">Resumo</h2>

                    <div class="mb-4">
                        <h3 class="font-bold">{{ $product->name }}</h3>
                        <p class="text-sm text-gray-600">{{ number_format($product->price_per_kg, 2) }} MT/{{ $product->unit }}</p>
                    </div>

                    <div class="mb-4">
                        <label class="block font-semibold mb-2">Quantidade</label>
                        <input type="number" wire:model.live="quantity" min="1"
                            class="w-full px-4 py-2 border rounded-lg">
                    </div>

                    <div class="border-t pt-4">
                        <div class="flex justify-between text-xl font-bold text-green-600">
                            <span>Total:</span>
                            <span>{{ number_format($this->total, 2) }} MT</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
