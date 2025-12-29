<div>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Gestao de Stock</h2>
                <p class="mt-1 text-sm text-gray-600">Controle e actualize o stock dos seus produtos</p>
            </div>
        </div>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl p-4 shadow-sm border">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Total Produtos</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_products'] }}</p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-full">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-4 shadow-sm border">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Stock Total</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_stock'], 0, ',', '.') }}</p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-full">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-yellow-50 rounded-xl p-4 shadow-sm border border-yellow-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-yellow-600 uppercase tracking-wide">Stock Baixo</p>
                        <p class="text-2xl font-bold text-yellow-700">{{ $stats['low_stock'] }}</p>
                    </div>
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-red-50 rounded-xl p-4 shadow-sm border border-red-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-red-600 uppercase tracking-wide">Sem Stock</p>
                        <p class="text-2xl font-bold text-red-700">{{ $stats['out_of_stock'] }}</p>
                    </div>
                    <div class="p-3 bg-red-100 rounded-full">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm border p-4">
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <div class="relative">
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Pesquisar produto..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
                        <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <select wire:model.live="stockFilter" class="w-full sm:w-auto px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
                        <option value="">Todos os produtos</option>
                        <option value="available">Com stock</option>
                        <option value="low">Stock baixo</option>
                        <option value="out">Sem stock</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Products Table -->
        <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
            @if($products->isEmpty())
                <div class="p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhum produto encontrado</h3>
                    <p class="mt-1 text-sm text-gray-500">Adicione produtos para comecar a gerir o stock.</p>
                    <div class="mt-6">
                        <a href="{{ route('supplier.products') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Adicionar Produto
                        </a>
                    </div>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produto</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoria</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Stock Actual</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Stock Minimo</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Accoes Rapidas</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Accoes</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($products as $product)
                                @php
                                    $stockQuantity = $product->stock_quantity ?? 0;
                                    $minQuantity = $product->min_quantity ?? 1;
                                    $isOutOfStock = $stockQuantity == 0;
                                    $isLowStock = $stockQuantity > 0 && $stockQuantity <= $minQuantity;
                                @endphp
                                <tr class="hover:bg-gray-50 {{ $isOutOfStock ? 'bg-red-50' : ($isLowStock ? 'bg-yellow-50' : '') }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if($product->image)
                                                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="h-10 w-10 rounded-lg object-cover">
                                            @else
                                                <div class="h-10 w-10 rounded-lg bg-gray-200 flex items-center justify-center">
                                                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                </div>
                                            @endif
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                                <div class="text-xs text-gray-500">{{ number_format($product->price_per_kg, 2, ',', '.') }} MT/{{ $product->unit ?? 'kg' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm text-gray-600">{{ $product->category?->name ?? 'Sem categoria' }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="text-lg font-bold {{ $isOutOfStock ? 'text-red-600' : ($isLowStock ? 'text-yellow-600' : 'text-gray-900') }}">
                                            {{ number_format($stockQuantity, 0, ',', '.') }}
                                        </span>
                                        <span class="text-xs text-gray-500">{{ $product->unit ?? 'kg' }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="text-sm text-gray-600">{{ number_format($minQuantity, 0, ',', '.') }} {{ $product->unit ?? 'kg' }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($isOutOfStock)
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                Sem Stock
                                            </span>
                                        @elseif($isLowStock)
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Stock Baixo
                                            </span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                Disponivel
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center gap-1">
                                            <button wire:click="quickSubtract({{ $product->id }}, 10)" class="p-1.5 text-red-600 hover:bg-red-100 rounded-lg transition-colors" title="Remover 10">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                                </svg>
                                            </button>
                                            <span class="text-xs text-gray-500 mx-1">10</span>
                                            <button wire:click="quickAdd({{ $product->id }}, 10)" class="p-1.5 text-green-600 hover:bg-green-100 rounded-lg transition-colors" title="Adicionar 10">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                                </svg>
                                            </button>
                                            <span class="text-gray-300 mx-1">|</span>
                                            <button wire:click="quickSubtract({{ $product->id }}, 100)" class="p-1.5 text-red-600 hover:bg-red-100 rounded-lg transition-colors" title="Remover 100">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                                </svg>
                                            </button>
                                            <span class="text-xs text-gray-500 mx-1">100</span>
                                            <button wire:click="quickAdd({{ $product->id }}, 100)" class="p-1.5 text-green-600 hover:bg-green-100 rounded-lg transition-colors" title="Adicionar 100">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-2">
                                            <button wire:click="openUpdateModal({{ $product->id }})" class="text-green-600 hover:text-green-900 p-1.5 hover:bg-green-100 rounded-lg transition-colors" title="Editar stock">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </button>
                                            @if($stockQuantity > 0)
                                                <button wire:click="setOutOfStock({{ $product->id }})" wire:confirm="Tem certeza que deseja marcar este produto como sem stock?" class="text-red-600 hover:text-red-900 p-1.5 hover:bg-red-100 rounded-lg transition-colors" title="Marcar sem stock">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($products->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $products->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>

    <!-- Update Stock Modal -->
    @if($showUpdateModal && $selectedProduct)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeUpdateModal"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form wire:submit="updateStock">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                        Actualizar Stock
                                    </h3>
                                    <p class="text-sm text-gray-500 mt-1">{{ $selectedProduct->name }}</p>

                                    <div class="mt-4 space-y-4">
                                        <div class="bg-gray-50 p-3 rounded-lg">
                                            <p class="text-sm text-gray-600">Stock actual:</p>
                                            <p class="text-2xl font-bold text-gray-900">{{ number_format($selectedProduct->stock_quantity ?? 0, 0, ',', '.') }} {{ $selectedProduct->unit ?? 'kg' }}</p>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Accao</label>
                                            <div class="grid grid-cols-3 gap-2">
                                                <label class="flex items-center justify-center p-3 border rounded-lg cursor-pointer {{ $stockAction === 'set' ? 'border-green-500 bg-green-50 text-green-700' : 'border-gray-300 hover:bg-gray-50' }}">
                                                    <input type="radio" wire:model.live="stockAction" value="set" class="sr-only">
                                                    <span class="text-sm font-medium">Definir</span>
                                                </label>
                                                <label class="flex items-center justify-center p-3 border rounded-lg cursor-pointer {{ $stockAction === 'add' ? 'border-green-500 bg-green-50 text-green-700' : 'border-gray-300 hover:bg-gray-50' }}">
                                                    <input type="radio" wire:model.live="stockAction" value="add" class="sr-only">
                                                    <span class="text-sm font-medium">Adicionar</span>
                                                </label>
                                                <label class="flex items-center justify-center p-3 border rounded-lg cursor-pointer {{ $stockAction === 'subtract' ? 'border-green-500 bg-green-50 text-green-700' : 'border-gray-300 hover:bg-gray-50' }}">
                                                    <input type="radio" wire:model.live="stockAction" value="subtract" class="sr-only">
                                                    <span class="text-sm font-medium">Remover</span>
                                                </label>
                                            </div>
                                        </div>

                                        <div>
                                            <label for="newStock" class="block text-sm font-medium text-gray-700 mb-1">
                                                @if($stockAction === 'set')
                                                    Novo valor do stock
                                                @elseif($stockAction === 'add')
                                                    Quantidade a adicionar
                                                @else
                                                    Quantidade a remover
                                                @endif
                                            </label>
                                            <div class="relative">
                                                <input type="number" wire:model="newStock" id="newStock" min="0" step="1"
                                                    class="w-full pr-16 border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
                                                <span class="absolute right-3 top-2 text-gray-500">{{ $selectedProduct->unit ?? 'kg' }}</span>
                                            </div>
                                            @error('newStock') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                        </div>

                                        <div>
                                            <label for="stockNote" class="block text-sm font-medium text-gray-700 mb-1">Nota (opcional)</label>
                                            <textarea wire:model="stockNote" id="stockNote" rows="2"
                                                class="w-full border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500"
                                                placeholder="Ex: Recepcao de nova remessa, venda directa, etc."></textarea>
                                            @error('stockNote') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Actualizar Stock
                            </button>
                            <button type="button" wire:click="closeUpdateModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:w-auto sm:text-sm">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
