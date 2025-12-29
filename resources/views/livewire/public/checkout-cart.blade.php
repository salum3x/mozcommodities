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
                                        <p class="text-sm text-gray-600">{{ $item->quantity }} kg x {{ number_format($item->price_per_kg, 2) }} MZN</p>
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
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Telefone</label>
                                <input type="text" wire:model="customer_phone" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                                    placeholder="+258 84 000 0000">
                                @error('customer_phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                        </div>
                    </div>

                    <!-- Delivery Address -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-xl font-bold text-gray-900">Entregar em</h2>
                            @auth
                                <a href="{{ route('profile.edit') }}" class="text-sm text-green-600 hover:text-green-700 font-medium flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                    Editar endereco
                                </a>
                            @endauth
                        </div>

                        @auth
                            @if(auth()->user()->address)
                                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                    <div class="flex items-start gap-3">
                                        <svg class="w-6 h-6 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                                            <p class="text-gray-700">{{ auth()->user()->address }}</p>
                                            @if(auth()->user()->phone)
                                                <p class="text-sm text-gray-500 mt-1">{{ auth()->user()->phone }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" wire:model="customer_address" value="{{ auth()->user()->address }}">
                            @else
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                    <div class="flex items-start gap-3">
                                        <svg class="w-6 h-6 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                        <div>
                                            <p class="font-medium text-yellow-800">Nenhum endereco cadastrado</p>
                                            <p class="text-sm text-yellow-700 mt-1">
                                                <a href="{{ route('profile.edit') }}" class="underline hover:text-yellow-900">Adicione seu endereco no perfil</a> para facilitar suas compras.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Endereco de entrega</label>
                                    <textarea wire:model="customer_address" rows="2" required
                                        placeholder="Informe o endereco completo para entrega"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"></textarea>
                                    @error('customer_address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            @endif
                        @else
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Endereco de entrega</label>
                                    <textarea wire:model="customer_address" rows="2" required
                                        placeholder="Informe o endereco completo para entrega"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"></textarea>
                                    @error('customer_address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <p class="text-sm text-gray-500">
                                    <a href="{{ route('login') }}" class="text-green-600 hover:underline">Faca login</a> para salvar seu endereco e agilizar futuras compras.
                                </p>
                            </div>
                        @endauth
                    </div>

                    <!-- Payment Methods -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Como deseja pagar?</h2>

                        <!-- Error Message -->
                        @if($paymentError)
                            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                                <div class="flex items-center gap-2 text-red-800">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="font-medium">{{ $paymentError }}</span>
                                </div>
                            </div>
                        @endif

                        <!-- Success Message -->
                        @if($paymentMessage)
                            <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                                <div class="flex items-center gap-2 text-green-800">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="font-medium">{{ $paymentMessage }}</span>
                                </div>
                            </div>
                        @endif

                        <div class="space-y-4">
                            <!-- M-Pesa -->
                            <label class="flex items-start p-4 border-2 rounded-lg cursor-pointer transition-all {{ $payment_method === 'mpesa' ? 'border-green-500 bg-green-50' : 'border-gray-200 hover:border-gray-300' }}">
                                <input type="radio" wire:model.live="payment_method" value="mpesa" name="payment_method" class="mt-1">
                                <div class="ml-4 flex-1">
                                    <div class="flex items-center gap-2">
                                        <span class="text-lg font-bold">M-Pesa</span>
                                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-bold rounded">Rapido</span>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">Pague instantaneamente pelo celular</p>
                                </div>
                                <div class="w-12 h-12 bg-red-600 rounded-lg flex items-center justify-center">
                                    <span class="text-white font-bold text-xs">M-PESA</span>
                                </div>
                            </label>

                            <!-- e-Mola -->
                            <label class="flex items-start p-4 border-2 rounded-lg cursor-pointer transition-all {{ $payment_method === 'emola' ? 'border-orange-500 bg-orange-50' : 'border-gray-200 hover:border-gray-300' }}">
                                <input type="radio" wire:model.live="payment_method" value="emola" name="payment_method" class="mt-1">
                                <div class="ml-4 flex-1">
                                    <div class="flex items-center gap-2">
                                        <span class="text-lg font-bold">e-Mola</span>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">Pagamento movel Movitel</p>
                                </div>
                                <div class="w-12 h-12 bg-orange-500 rounded-lg flex items-center justify-center">
                                    <span class="text-white font-bold text-xs">e-Mola</span>
                                </div>
                            </label>

                            <!-- Card -->
                            <label class="flex items-start p-4 border-2 rounded-lg cursor-pointer transition-all {{ $payment_method === 'card' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300' }}">
                                <input type="radio" wire:model.live="payment_method" value="card" name="payment_method" class="mt-1">
                                <div class="ml-4 flex-1">
                                    <div class="flex items-center gap-2">
                                        <span class="text-lg font-bold">Cartao de Credito/Debito</span>
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-bold rounded">Seguro</span>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">Visa, Mastercard, American Express</p>
                                </div>
                                <div class="flex gap-1">
                                    <div class="w-10 h-6 bg-blue-900 rounded flex items-center justify-center">
                                        <span class="text-white font-bold text-xs">VISA</span>
                                    </div>
                                    <div class="w-10 h-6 bg-red-500 rounded flex items-center justify-center">
                                        <span class="text-white font-bold text-xs">MC</span>
                                    </div>
                                </div>
                            </label>

                            <!-- Bank Transfer -->
                            <label class="flex items-start p-4 border-2 rounded-lg cursor-pointer transition-all {{ $payment_method === 'bank_transfer' ? 'border-gray-500 bg-gray-50' : 'border-gray-200 hover:border-gray-300' }}">
                                <input type="radio" wire:model.live="payment_method" value="bank_transfer" name="payment_method" class="mt-1">
                                <div class="ml-4 flex-1">
                                    <span class="text-lg font-bold">Transferencia Bancaria</span>
                                    <p class="text-sm text-gray-600 mt-1">Transfira e envie comprovativo</p>
                                </div>
                                <div class="w-12 h-12 bg-gray-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                    </svg>
                                </div>
                            </label>

                            <!-- Payment Instructions -->
                            @if($payment_method === 'mpesa')
                                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                    <p class="font-semibold text-green-900 mb-2">Como pagar com M-Pesa:</p>
                                    <ol class="list-decimal list-inside text-sm text-green-800 space-y-1">
                                        <li>Ao confirmar, receberao o pedido de pagamento no celular</li>
                                        <li>Digite o PIN do M-Pesa para confirmar</li>
                                        <li>Aguarde a confirmacao automatica</li>
                                    </ol>
                                    <p class="mt-3 text-sm text-green-700">
                                        <strong>Valor:</strong> {{ number_format($total, 2) }} MZN
                                    </p>
                                </div>
                            @endif

                            @if($payment_method === 'emola')
                                <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                                    <p class="font-semibold text-orange-900 mb-2">Como pagar com e-Mola:</p>
                                    <ol class="list-decimal list-inside text-sm text-orange-800 space-y-1">
                                        <li>Ao confirmar, receberao o pedido de pagamento no celular</li>
                                        <li>Confirme o pagamento no seu telefone</li>
                                        <li>Aguarde a confirmacao automatica</li>
                                    </ol>
                                    <p class="mt-3 text-sm text-orange-700">
                                        <strong>Valor:</strong> {{ number_format($total, 2) }} MZN
                                    </p>
                                </div>
                            @endif

                            @if($payment_method === 'card')
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <p class="font-semibold text-blue-900 mb-2">Pagamento Seguro:</p>
                                    <p class="text-sm text-blue-800">Seus dados de cartao sao processados de forma segura pelo Stripe.</p>
                                    <div class="flex items-center gap-2 mt-2">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                        <span class="text-sm text-blue-700">Conexao criptografada</span>
                                    </div>
                                </div>

                                <!-- Stripe Card Element -->
                                @if($stripeClientSecret)
                                    <div id="card-element" class="p-4 border border-gray-300 rounded-lg bg-white"></div>
                                    <div id="card-errors" class="text-red-500 text-sm mt-2"></div>
                                @endif
                            @endif

                            @if($payment_method === 'bank_transfer')
                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                    <p class="font-semibold text-gray-900 mb-2">Dados Bancarios:</p>
                                    <div class="text-sm text-gray-700 space-y-1">
                                        <p><strong>Banco:</strong> {{ \App\Models\Setting::get('bank_name', 'Millennium BIM') }}</p>
                                        <p><strong>NIB:</strong> {{ \App\Models\Setting::get('bank_nib', '0001 0000 0000 0000 0000 0') }}</p>
                                        <p><strong>Titular:</strong> {{ \App\Models\Setting::get('bank_account_holder', 'MozCommodities Lda') }}</p>
                                        <p><strong>Valor:</strong> {{ number_format($total, 2) }} MZN</p>
                                    </div>

                                    <div class="mt-4">
                                        <label class="block font-semibold text-gray-700 mb-2">Comprovativo de Pagamento *</label>
                                        <input type="file" wire:model="payment_proof" accept="image/*" required
                                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                                        @error('payment_proof') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                                        <div wire:loading wire:target="payment_proof" class="mt-2 text-sm text-gray-500">
                                            Carregando...
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Observacoes (opcional)</label>
                                <textarea wire:model="notes" rows="2" placeholder="Ex: Preferencia de entrega..."
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"></textarea>
                            </div>
                        </div>
                    </div>

                    <button type="submit"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-50 cursor-not-allowed"
                        class="w-full bg-gradient-to-r from-green-600 to-green-700 text-white py-4 rounded-lg font-bold text-lg hover:from-green-700 hover:to-green-800 transition-all shadow-lg flex items-center justify-center gap-2">
                        <span wire:loading.remove wire:target="placeOrder">Confirmar Pedido</span>
                        <span wire:loading wire:target="placeOrder" class="flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Processando...
                        </span>
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

                    <!-- Payment Method Icons -->
                    <div class="border-t border-gray-200 pt-4">
                        <p class="text-sm text-gray-500 mb-3">Metodos de pagamento aceites:</p>
                        <div class="flex flex-wrap gap-2">
                            <div class="px-3 py-1 bg-red-100 rounded text-red-700 text-xs font-bold">M-Pesa</div>
                            <div class="px-3 py-1 bg-orange-100 rounded text-orange-700 text-xs font-bold">e-Mola</div>
                            <div class="px-3 py-1 bg-blue-100 rounded text-blue-700 text-xs font-bold">Cartao</div>
                            <div class="px-3 py-1 bg-gray-100 rounded text-gray-700 text-xs font-bold">Banco</div>
                        </div>
                    </div>

                    <div class="text-sm text-gray-600 space-y-2 mt-4">
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
                            Entrega rapida
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stripe JS -->
    @if($payment_method === 'card' && config('services.stripe.key'))
        @push('scripts')
        <script src="https://js.stripe.com/v3/"></script>
        <script>
            document.addEventListener('livewire:initialized', () => {
                Livewire.on('initStripe', ({ clientSecret }) => {
                    const stripe = Stripe('{{ config('services.stripe.key') }}');
                    const elements = stripe.elements();
                    const cardElement = elements.create('card', {
                        style: {
                            base: {
                                fontSize: '16px',
                                color: '#32325d',
                            }
                        }
                    });

                    cardElement.mount('#card-element');

                    cardElement.on('change', function(event) {
                        const displayError = document.getElementById('card-errors');
                        if (event.error) {
                            displayError.textContent = event.error.message;
                        } else {
                            displayError.textContent = '';
                        }
                    });

                    const form = document.querySelector('form');
                    form.addEventListener('submit', async (e) => {
                        if (clientSecret) {
                            e.preventDefault();

                            const { error, paymentIntent } = await stripe.confirmCardPayment(clientSecret, {
                                payment_method: {
                                    card: cardElement,
                                }
                            });

                            if (error) {
                                document.getElementById('card-errors').textContent = error.message;
                            } else if (paymentIntent.status === 'succeeded') {
                                @this.call('confirmStripePayment', paymentIntent.id);
                            }
                        }
                    });
                });
            });
        </script>
        @endpush
    @endif
</div>
