<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900">Aprovar Produtos</h1>
            <p class="mt-2 text-gray-600">Revise e aprove produtos dos fornecedores com aplicação de margem</p>
        </div>

        @if (session()->has('message'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                {{ session('message') }}
            </div>
        @endif

        <!-- Produtos Pendentes -->
        @if($pendingProducts->count() > 0)
            <div class="grid grid-cols-1 gap-6">
                @foreach($pendingProducts as $product)
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden border-l-4 border-yellow-500">
                        <div class="p-6">
                            <div class="flex items-start justify-between">
                                <!-- Produto Info -->
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-3">
                                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-bold rounded-full">
                                            ⏳ PENDENTE
                                        </span>
                                        <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs font-bold rounded-full">
                                            {{ $product->category->name }}
                                        </span>
                                    </div>

                                    <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $product->name }}</h3>

                                    <div class="grid grid-cols-2 gap-4 mt-4">
                                        <div>
                                            <p class="text-sm text-gray-600">Fornecedor</p>
                                            <p class="font-semibold text-gray-900">{{ $product->supplier->user->name }}</p>
                                            <p class="text-sm text-gray-600">{{ $product->supplier->company_name }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600">Preço Bruto (P.B.F.)</p>
                                            <p class="text-2xl font-bold text-gray-900">{{ number_format($product->cost_price ?? 0, 2) }} MZN/kg</p>
                                        </div>
                                    </div>

                                    @if($product->description)
                                        <div class="mt-4">
                                            <p class="text-sm text-gray-600">Descrição</p>
                                            <p class="text-gray-700">{{ $product->description }}</p>
                                        </div>
                                    @endif
                                </div>

                                <!-- Ações -->
                                <div class="flex gap-2 ml-6">
                                    <button wire:click="openApprovalModal({{ $product->id }})"
                                        class="px-6 py-3 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 transition-all shadow-lg">
                                        ✓ Aprovar
                                    </button>
                                    <button wire:click="openRejectionModal({{ $product->id }})"
                                        class="px-6 py-3 bg-red-600 text-white font-bold rounded-lg hover:bg-red-700 transition-all shadow-lg">
                                        ✗ Rejeitar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Nenhum produto pendente</h2>
                <p class="text-gray-600">Todos os produtos foram revisados!</p>
            </div>
        @endif

        <!-- Modal de Aprovação -->
        @if($showApprovalModal && $selectedProduct)
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
                <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-screen overflow-y-auto">
                    <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
                        <h2 class="text-2xl font-bold text-white">Aprovar Produto</h2>
                        <p class="text-green-100">{{ $selectedProduct->name }}</p>
                    </div>

                    <div class="p-6 space-y-6">
                        <!-- Preço de Custo -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Preço de Custo (P.B.F.) - Preço do Fornecedor
                            </label>
                            <input type="number" wire:model.live="cost_price" step="0.01" min="0"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                                placeholder="0.00">
                            @error('cost_price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Margem da Plataforma -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Margem de Lucro da Plataforma (%)
                            </label>
                            <input type="number" wire:model.live="platform_margin" step="0.1" min="0" max="100"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                                placeholder="20">
                            @error('platform_margin') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            <p class="text-xs text-gray-600 mt-1">Margem padrão: 20%</p>
                        </div>

                        <!-- Stock -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Stock Disponível (kg)
                            </label>
                            <input type="number" wire:model="stock_kg" min="0"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                                placeholder="0">
                            @error('stock_kg') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Cálculo do Preço -->
                        <div class="bg-green-50 border-2 border-green-200 rounded-lg p-4">
                            <h3 class="font-bold text-gray-900 mb-3">Cálculo do Preço de Venda</h3>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-700">Preço Bruto (P.B.F.):</span>
                                    <span class="font-semibold">{{ number_format($cost_price, 2) }} MZN</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-700">Margem da Plataforma:</span>
                                    <span class="font-semibold">{{ $platform_margin }}%</span>
                                </div>
                                <div class="border-t-2 border-green-300 pt-2 mt-2">
                                    <div class="flex justify-between items-center">
                                        <span class="font-bold text-gray-900">Preço de Venda (P.V.P.):</span>
                                        <span class="text-2xl font-bold text-green-600">{{ number_format($calculated_price, 2) }} MZN/kg</span>
                                    </div>
                                </div>
                            </div>
                            <p class="text-xs text-gray-600 mt-3">
                                Fórmula: P.V.P. = P.B.F. × (1 + Margem%)
                            </p>
                        </div>

                        <!-- Botões -->
                        <div class="flex gap-3 pt-4">
                            <button wire:click="approveProduct"
                                class="flex-1 px-6 py-3 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 transition-all">
                                Aprovar e Publicar
                            </button>
                            <button wire:click="closeModal"
                                class="px-6 py-3 bg-gray-200 text-gray-700 font-bold rounded-lg hover:bg-gray-300 transition-all">
                                Cancelar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Modal de Rejeição -->
        @if($showRejectionModal && $selectedProduct)
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
                <div class="bg-white rounded-xl shadow-2xl max-w-lg w-full">
                    <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4">
                        <h2 class="text-2xl font-bold text-white">Rejeitar Produto</h2>
                        <p class="text-red-100">{{ $selectedProduct->name }}</p>
                    </div>

                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Motivo da Rejeição (obrigatório)
                            </label>
                            <textarea wire:model="rejection_reason" rows="4"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500"
                                placeholder="Ex: Fotos de baixa qualidade, descrição incompleta, preço fora do mercado..."></textarea>
                            @error('rejection_reason') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            <p class="text-xs text-gray-600 mt-1">O fornecedor receberá esta justificativa</p>
                        </div>

                        <div class="flex gap-3 pt-4">
                            <button wire:click="rejectProduct"
                                class="flex-1 px-6 py-3 bg-red-600 text-white font-bold rounded-lg hover:bg-red-700 transition-all">
                                Confirmar Rejeição
                            </button>
                            <button wire:click="closeModal"
                                class="px-6 py-3 bg-gray-200 text-gray-700 font-bold rounded-lg hover:bg-gray-300 transition-all">
                                Cancelar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
