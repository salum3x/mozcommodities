<div>
    <div class="sm:flex sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Fornecedores</h1>
            <p class="mt-1 text-sm text-gray-500">Gerencie os fornecedores do marketplace</p>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            {{ session('message') }}
        </div>
    @endif

    <!-- Filters -->
    <div class="mb-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
        <input wire:model.live="search" type="text" placeholder="Pesquisar fornecedores..." class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">

        <select wire:model.live="statusFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
            <option value="">Todos os estados</option>
            <option value="pending">Pendentes</option>
            <option value="approved">Aprovados</option>
            <option value="rejected">Rejeitados</option>
        </select>
    </div>

    <!-- Table -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Empresa</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contacto</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produtos</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acoes</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($suppliers as $supplier)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center mr-3">
                                    <span class="text-green-600 font-semibold text-sm">{{ strtoupper(substr($supplier->company_name, 0, 2)) }}</span>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $supplier->company_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $supplier->document_number }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $supplier->user->email ?? 'N/A' }}</div>
                            <div class="text-sm text-gray-500">{{ $supplier->whatsapp }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $supplier->products->count() }} produtos
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($supplier->status === 'approved')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aprovado</span>
                            @elseif ($supplier->status === 'pending')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pendente</span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rejeitado</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            @if ($supplier->status === 'pending')
                                <button wire:click="updateStatus({{ $supplier->id }}, 'approved')" class="text-green-600 hover:text-green-900 mr-2">Aprovar</button>
                                <button wire:click="updateStatus({{ $supplier->id }}, 'rejected')" class="text-red-600 hover:text-red-900 mr-2">Rejeitar</button>
                            @elseif ($supplier->status === 'approved')
                                <button wire:click="updateStatus({{ $supplier->id }}, 'rejected')" class="text-red-600 hover:text-red-900 mr-2">Suspender</button>
                            @else
                                <button wire:click="updateStatus({{ $supplier->id }}, 'approved')" class="text-green-600 hover:text-green-900 mr-2">Reativar</button>
                            @endif
                            <button wire:click="delete({{ $supplier->id }})" wire:confirm="Tem certeza que deseja excluir este fornecedor?" class="text-red-600 hover:text-red-900">
                                Excluir
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            Nenhum fornecedor encontrado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $suppliers->links() }}
    </div>
</div>
