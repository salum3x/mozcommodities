<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-2xl mx-auto px-4">
        <!-- Success Icon -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-4">
                <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-extrabold text-gray-900">Pedido Confirmado!</h1>
            <p class="text-gray-600 mt-2">Obrigado pela sua compra</p>
        </div>

        <!-- Order Details -->
        <div class="bg-white rounded-xl shadow-lg p-8 mb-6">
            <div class="flex items-center justify-between mb-6 pb-6 border-b">
                <div>
                    <p class="text-sm text-gray-600">Número do Pedido</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $order->order_number }}</p>
                </div>
                <div class="text-right">
                    <span class="px-4 py-2 bg-yellow-100 text-yellow-800 rounded-full font-bold">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
            </div>

            <div class="space-y-4 mb-6">
                <div>
                    <p class="text-sm font-semibold text-gray-700">Cliente:</p>
                    <p class="text-gray-900">{{ $order->customer_name }}</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-700">Email:</p>
                    <p class="text-gray-900">{{ $order->customer_email }}</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-700">Telefone:</p>
                    <p class="text-gray-900">{{ $order->customer_phone }}</p>
                </div>
            </div>

            <div class="border-t pt-6">
                <p class="text-sm font-semibold text-gray-700 mb-3">Itens:</p>
                @foreach($order->items as $item)
                    <div class="flex justify-between mb-2">
                        <span>{{ $item->product_name }} ({{ $item->quantity }} un)</span>
                        <span class="font-bold">{{ number_format($item->subtotal, 2) }} MT</span>
                    </div>
                @endforeach
            </div>

            <div class="border-t pt-4 mt-4">
                <div class="flex justify-between text-2xl font-bold text-green-600">
                    <span>Total:</span>
                    <span>{{ number_format($order->total, 2) }} MT</span>
                </div>
            </div>
        </div>

        <!-- Payment Info -->
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-6">
            <h3 class="font-bold text-blue-900 mb-2">Método de Pagamento:</h3>
            <p class="text-blue-800">
                <strong>{{ $order->payment_method_label }}</strong>
                @if ($order->payment_status === 'paid')
                    — <span class="text-emerald-700 font-semibold">Pago</span>
                @elseif ($order->payment_status === 'pending')
                    — <span class="text-amber-700 font-semibold">Aguardando confirmação</span>
                @elseif ($order->payment_status === 'failed')
                    — <span class="text-red-700 font-semibold">Falhou</span>
                @endif
            </p>
            @if ($order->transaction_id)
                <p class="text-xs text-blue-700 mt-1">Referência: <span class="font-mono">{{ $order->transaction_id }}</span></p>
            @endif

            @if ($order->payment_method === 'bank_transfer' && $order->payment_status !== 'paid')
                <p class="text-sm text-blue-900 mt-3">Transfere {{ number_format($order->total, 2, ',', '.') }} MZN e envia o comprovativo. Vamos confirmar e processar o teu pedido.</p>
            @endif
        </div>

        <!-- Next Steps -->
        <div class="bg-green-50 border border-green-200 rounded-xl p-6">
            <h3 class="font-bold text-green-900 mb-3">Próximos Passos:</h3>
            <ol class="text-green-800 space-y-2 list-decimal list-inside">
                @if ($order->payment_status === 'paid')
                    <li>Pagamento confirmado.</li>
                    <li>Vamos preparar o teu pedido.</li>
                    <li>Entraremos em contacto para combinar a entrega.</li>
                @elseif ($order->payment_method === 'bank_transfer')
                    <li>Transfere {{ number_format($order->total, 2, ',', '.') }} MZN para a conta indicada.</li>
                    <li>Envia o comprovativo (já foi enviado se anexaste no checkout).</li>
                    <li>Confirmamos o pagamento em até 24h úteis.</li>
                @elseif (in_array($order->payment_method, ['emola','mpesa']))
                    <li>Verifica o teu telefone — recebeste uma notificação {{ $order->payment_method_label }}.</li>
                    <li>Introduz o PIN para confirmar {{ number_format($order->total, 2, ',', '.') }} MZN.</li>
                    <li>Vamos receber a confirmação automaticamente e processar.</li>
                @else
                    <li>Vais receber um email de confirmação.</li>
                    <li>Completa o pagamento via {{ $order->payment_method_label }}.</li>
                    <li>Entraremos em contacto para combinar a entrega.</li>
                @endif
            </ol>
        </div>

        <div class="text-center mt-8">
            <a href="{{ route('home') }}" class="inline-block px-8 py-3 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700">
                Voltar à Página Inicial
            </a>
        </div>
    </div>
</div>
