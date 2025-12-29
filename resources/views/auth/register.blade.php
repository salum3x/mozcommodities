<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="text-3xl font-extrabold text-gray-900">Criar conta</h2>
        <p class="mt-2 text-sm text-gray-600">Escolha o tipo de conta que deseja criar</p>
    </div>

    <div class="space-y-4">
        <!-- Customer Account -->
        <a href="{{ route('register.customer') }}"
           class="block p-6 bg-white border-2 border-gray-200 rounded-xl hover:border-green-500 hover:shadow-lg transition-all duration-200 group">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center group-hover:bg-green-500 transition-colors">
                    <svg class="w-7 h-7 text-green-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-900 group-hover:text-green-600 transition-colors">Cliente</h3>
                    <p class="text-sm text-gray-500">Compre produtos agricolas de qualidade</p>
                </div>
                <svg class="w-6 h-6 text-gray-400 group-hover:text-green-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
        </a>

        <!-- Supplier Account -->
        <a href="{{ route('register.supplier') }}"
           class="block p-6 bg-white border-2 border-gray-200 rounded-xl hover:border-orange-500 hover:shadow-lg transition-all duration-200 group">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-orange-100 rounded-xl flex items-center justify-center group-hover:bg-orange-500 transition-colors">
                    <svg class="w-7 h-7 text-orange-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-900 group-hover:text-orange-600 transition-colors">Fornecedor</h3>
                    <p class="text-sm text-gray-500">Venda seus produtos na plataforma</p>
                </div>
                <svg class="w-6 h-6 text-gray-400 group-hover:text-orange-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
        </a>
    </div>

    <!-- Benefits -->
    <div class="mt-8 pt-6 border-t border-gray-200">
        <h4 class="text-sm font-semibold text-gray-700 mb-4 text-center">Beneficios de criar uma conta</h4>
        <div class="grid grid-cols-2 gap-4">
            <div class="flex items-start gap-2">
                <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span class="text-xs text-gray-600">Compras seguras</span>
            </div>
            <div class="flex items-start gap-2">
                <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span class="text-xs text-gray-600">Historico de pedidos</span>
            </div>
            <div class="flex items-start gap-2">
                <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span class="text-xs text-gray-600">Ofertas exclusivas</span>
            </div>
            <div class="flex items-start gap-2">
                <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span class="text-xs text-gray-600">Suporte dedicado</span>
            </div>
        </div>
    </div>

    <!-- Login Link -->
    <div class="text-center pt-6 mt-6 border-t border-gray-200">
        <p class="text-sm text-gray-600">
            Ja tem uma conta?
            <a href="{{ route('login') }}" class="font-semibold text-green-600 hover:text-green-700 transition-colors">
                Entrar agora
            </a>
        </p>
    </div>
</x-guest-layout>
