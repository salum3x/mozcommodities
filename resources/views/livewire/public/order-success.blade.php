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
                @if($order->payment_method === 'mpesa')
                    M-Pesa - Envie {{ number_format($order->total, 2) }} MT para +258 84 000 0000
                @else
                    Transferência Bancária - Comprovante enviado
                @endif
            </p>
        </div>

        <!-- Next Steps -->
        <div class="bg-green-50 border border-green-200 rounded-xl p-6">
            <h3 class="font-bold text-green-900 mb-3">Próximos Passos:</h3>
            <ol class="text-green-800 space-y-2 list-decimal list-inside">
                <li>Você receberá um email de confirmação</li>
                <li>Complete o pagamento via {{ $order->payment_method === 'mpesa' ? 'M-Pesa' : 'transferência bancária' }}</li>
                <li>Entraremos em contato para combinar a entrega</li>
            </ol>
        </div>

        <div class="text-center mt-8">
            <a href="{{ route('home') }}" class="inline-block px-8 py-3 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700">
                Voltar à Página Inicial
            </a>
        </div>
    </div>
</div>
