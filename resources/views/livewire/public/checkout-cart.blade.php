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

                        @php
                            $mpesaEnabled = (bool) \App\Models\Setting::get('payment_mpesa_enabled', false);
                            $emolaEnabled = (bool) \App\Models\Setting::get('payment_emola_enabled', true);
                            $cardEnabled  = (bool) \App\Models\Setting::get('payment_card_enabled', false);
                        @endphp
                        <div class="space-y-4">
                            <!-- e-Mola (Movitel) -->
                            <label class="flex items-center p-4 border-2 rounded-xl cursor-pointer transition {{ $payment_method === 'emola' ? 'border-orange-500 bg-orange-50' : 'border-gray-200 hover:border-gray-300' }}">
                                <input type="radio" wire:model.live="payment_method" value="emola" name="payment_method" class="mt-0.5">
                                <div class="ml-4 flex-1">
                                    <div class="flex items-center gap-2">
                                        <span class="text-lg font-bold">e-Mola</span>
                                        <span class="px-2 py-0.5 bg-orange-100 text-orange-800 text-[10px] font-bold rounded">Disponível</span>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">Pagamento móvel Movitel</p>
                                </div>
                                <div class="w-14 h-14 bg-white rounded-lg border border-orange-200 flex items-center justify-center overflow-hidden">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/c/c9/Logo_Movitel_Mo%C3%A7ambique.svg/200px-Logo_Movitel_Mo%C3%A7ambique.svg.png"
                                         alt="e-Mola"
                                         class="max-w-full max-h-full object-contain p-1"
                                         onerror="this.outerHTML='<div class=\'w-full h-full bg-orange-500 flex items-center justify-center\'><span class=\'text-white font-bold text-[10px]\'>e-Mola</span></div>'">
                                </div>
                            </label>

                            <!-- M-Pesa (indisponível por padrão) -->
                            <label class="flex items-center p-4 border-2 rounded-xl transition opacity-60 cursor-not-allowed {{ $mpesaEnabled ? '' : 'bg-gray-50' }}">
                                <input type="radio" disabled name="payment_method" class="mt-0.5">
                                <div class="ml-4 flex-1">
                                    <div class="flex items-center gap-2">
                                        <span class="text-lg font-bold">M-Pesa</span>
                                        <span class="px-2 py-0.5 bg-gray-200 text-gray-700 text-[10px] font-bold rounded">Indisponível no momento</span>
                                    </div>
                                    <p class="text-sm text-gray-500 mt-1">Vai estar disponível em breve — configura no painel admin.</p>
                                </div>
                                <div class="w-14 h-14 bg-white rounded-lg border border-gray-200 flex items-center justify-center overflow-hidden grayscale">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/15/M-PESA_LOGO-01.svg/240px-M-PESA_LOGO-01.svg.png"
                                         alt="M-Pesa"
                                         class="max-w-full max-h-full object-contain p-1"
                                         onerror="this.outerHTML='<div class=\'w-full h-full bg-red-600 flex items-center justify-center\'><span class=\'text-white font-bold text-[10px]\'>M-PESA</span></div>'">
                                </div>
                            </label>

                            <!-- Cartão (indisponível por padrão) -->
                            <label class="flex items-center p-4 border-2 rounded-xl transition opacity-60 cursor-not-allowed bg-gray-50">
                                <input type="radio" disabled name="payment_method" class="mt-0.5">
                                <div class="ml-4 flex-1">
                                    <div class="flex items-center gap-2">
                                        <span class="text-lg font-bold">Cartão de Crédito/Débito</span>
                                        <span class="px-2 py-0.5 bg-gray-200 text-gray-700 text-[10px] font-bold rounded">Indisponível no momento</span>
                                    </div>
                                    <p class="text-sm text-gray-500 mt-1">Visa, Mastercard — configura no painel admin.</p>
                                </div>
                                <div class="flex gap-1 grayscale">
                                    <div class="w-10 h-6 bg-blue-900 rounded flex items-center justify-center">
                                        <span class="text-white font-bold text-[10px]">VISA</span>
                                    </div>
                                    <div class="w-10 h-6 bg-red-500 rounded flex items-center justify-center">
                                        <span class="text-white font-bold text-[10px]">MC</span>
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

    {{-- Payment confirmation popup (M-Pesa / e-Mola) --}}
    @if ($popupOpen)
        <div wire:key="payment-popup"
             @if ($popupState === 'awaiting') wire:poll.2s="pollPaymentStatus" @endif
             x-data="{ open: true }"
             x-init="$watch('$wire.popupState', s => { if (s === 'success') setTimeout(() => $wire.goToSuccess(), 1500); })"
             class="fixed inset-0 z-50 flex items-center justify-center p-4">

            <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"></div>

            <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 sm:p-8">
                @if ($popupState === 'awaiting')
                    <div class="text-center">
                        <div class="mx-auto w-20 h-20 rounded-full bg-emerald-50 flex items-center justify-center mb-5">
                            <svg class="w-10 h-10 text-emerald-600 animate-pulse" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">{{ $popupTitle }}</h3>
                        <p class="text-gray-600 mt-2 text-sm">{{ $popupBody }}</p>

                        <div class="mt-5 flex items-center justify-center gap-1.5">
                            @for ($i = 0; $i < 3; $i++)
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-bounce" style="animation-delay: {{ $i * 0.15 }}s"></span>
                            @endfor
                        </div>

                        <div class="mt-5 bg-amber-50 border border-amber-200 rounded-lg p-3 text-left text-xs text-amber-900">
                            <p class="font-medium">No teu telefone:</p>
                            <ol class="mt-1 list-decimal list-inside space-y-0.5 text-amber-800/90">
                                <li>Vais receber uma notificação <strong>{{ $payment_method === 'mpesa' ? 'M-Pesa' : 'e-Mola' }}</strong></li>
                                <li>Introduz o PIN para autorizar</li>
                                <li>Confirma o pagamento de <strong>{{ number_format($total, 2, ',', '.') }} MZN</strong></li>
                            </ol>
                        </div>

                        @if ($pollCount >= 10)
                            <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-3 text-left text-xs text-blue-900">
                                <p class="font-medium mb-1">Já confirmaste mas continua pendente?</p>
                                <p class="text-blue-800/90 mb-2">O nosso sistema às vezes demora a receber a confirmação do gateway. O teu pedido <strong>#{{ optional(\App\Models\Order::find($pendingOrderId))->order_number ?? '—' }}</strong> está registado.</p>
                                <p class="text-blue-800/90">Podes:</p>
                                <ul class="list-disc list-inside text-blue-800/90 mt-1 space-y-0.5">
                                    <li>Aguardar mais alguns segundos</li>
                                    <li>Fechar e ver em <a href="{{ route('my-orders') }}" class="underline font-medium">Meus Pedidos</a></li>
                                    <li>Contactar o admin se confirmaste mas continua pendente — o admin pode aprovar manualmente</li>
                                </ul>
                            </div>
                        @endif

                        <div class="mt-5 flex items-center justify-center gap-3">
                            <button type="button" wire:click="closePopup" class="text-sm text-gray-500 hover:text-gray-700 underline-offset-4 hover:underline">
                                Fechar
                            </button>
                            @if ($pollCount >= 5)
                                <a href="{{ route('my-orders') }}" class="text-sm font-semibold text-emerald-700 hover:text-emerald-900 underline-offset-4 hover:underline">Ver Meus Pedidos →</a>
                            @endif
                        </div>
                    </div>

                @elseif ($popupState === 'success')
                    <div class="text-center">
                        <div class="mx-auto w-20 h-20 rounded-full bg-emerald-100 flex items-center justify-center mb-5">
                            <svg class="w-12 h-12 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">{{ $popupTitle }}</h3>
                        <p class="text-gray-600 mt-2 text-sm">{{ $popupBody }}</p>
                        <button type="button" wire:click="goToSuccess" class="mt-5 inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl px-5 py-2.5 transition">
                            Ver detalhes do pedido
                        </button>
                    </div>

                @elseif ($popupState === 'failed')
                    <div class="text-center">
                        <div class="mx-auto w-20 h-20 rounded-full bg-red-100 flex items-center justify-center mb-5">
                            <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">{{ $popupTitle }}</h3>
                        <p class="text-gray-600 mt-2 text-sm">{{ $popupBody }}</p>
                        <div class="mt-5 flex gap-2 justify-center">
                            <button type="button" wire:click="closePopup" class="px-4 py-2 rounded-xl bg-gray-100 text-gray-800 text-sm font-medium hover:bg-gray-200 transition">Fechar</button>
                            <a href="{{ route('my-orders') }}" class="px-4 py-2 rounded-xl bg-gray-900 text-white text-sm font-medium hover:bg-black transition">Ver meus pedidos</a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
