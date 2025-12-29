<div>
    @if ($showForm)
        <!-- Pagina de Criar/Editar Fornecedor -->
        <div class="mb-6">
            <button wire:click="closeForm" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 mb-4">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Voltar para Fornecedores
            </button>
            <h1 class="text-2xl font-bold text-gray-900">{{ $editingId ? 'Editar Fornecedor' : 'Novo Fornecedor' }}</h1>
            <p class="mt-1 text-sm text-gray-500">{{ $editingId ? 'Atualize as informacoes do fornecedor' : 'Preencha as informacoes para criar um novo fornecedor' }}</p>
        </div>

        @if (session()->has('message'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg" role="alert">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('message') }}
                </div>
            </div>
        @endif

        <form wire:submit="save">
            @if (!$editingId)
                <!-- Dados de Acesso -->
                <div class="bg-white rounded-lg shadow-md mb-6">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Dados de Acesso</h3>
                        <p class="text-sm text-gray-500">Informacoes para o utilizador acessar o sistema</p>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nome do Utilizador</label>
                                <input wire:model="user_name" type="text"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                       placeholder="Nome completo" required>
                                @error('user_name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input wire:model="user_email" type="email"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                       placeholder="email@exemplo.com" required>
                                @error('user_email') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Senha</label>
                                <input wire:model="user_password" type="password"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                       placeholder="******" required>
                                @error('user_password') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Dados da Empresa -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Dados da Empresa</h3>
                    <p class="text-sm text-gray-500">Informacoes sobre a empresa do fornecedor</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nome da Empresa</label>
                            <input wire:model="company_name" type="text"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                   placeholder="Nome da empresa" required>
                            @error('company_name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">NUIT/Documento</label>
                            <input wire:model="document_number" type="text"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                   placeholder="Numero do documento">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">WhatsApp</label>
                            <input wire:model="whatsapp" type="text"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                   placeholder="+258 84 xxx xxxx" required>
                            @error('whatsapp') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Endereco</label>
                            <input wire:model="address" type="text"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                   placeholder="Endereco completo">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                            <select wire:model="status"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="pending">Pendente</option>
                                <option value="approved">Aprovado</option>
                                <option value="rejected">Rejeitado</option>
                            </select>
                        </div>

                        <div class="md:col-span-2 lg:col-span-3">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Descricao</label>
                            <textarea wire:model="description" rows="4"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                      placeholder="Descreva o fornecedor..."></textarea>
                        </div>
                    </div>

                    <div class="flex gap-4 mt-8 pt-6 border-t border-gray-200">
                        <button type="submit"
                                class="px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition">
                            <span class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                {{ $editingId ? 'Atualizar Fornecedor' : 'Criar Fornecedor' }}
                            </span>
                        </button>
                        <button type="button" wire:click="closeForm"
                                class="px-6 py-3 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </form>
    @else
        <!-- Pagina de Listagem de Fornecedores -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Fornecedores</h1>
                <p class="mt-1 text-sm text-gray-500">Gerencie os fornecedores do marketplace</p>
            </div>
            <button wire:click="create"
                    class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Novo Fornecedor
            </button>
        </div>

        @if (session()->has('message'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg" role="alert">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('message') }}
                </div>
            </div>
        @endif

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-md p-4 mb-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <input wire:model.live="search" type="text" placeholder="Pesquisar fornecedores..."
                       class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">

                <select wire:model.live="statusFilter"
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    <option value="">Todos os estados</option>
                    <option value="pending">Pendentes</option>
                    <option value="approved">Aprovados</option>
                    <option value="rejected">Rejeitados</option>
                </select>
            </div>
        </div>

        <!-- Suppliers Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Empresa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contacto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">WhatsApp</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produtos</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acoes</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($suppliers as $supplier)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center mr-3">
                                        <span class="text-green-600 font-bold text-sm">{{ strtoupper(substr($supplier->company_name, 0, 2)) }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-900">{{ $supplier->company_name }}</span>
                                        <p class="text-sm text-gray-500">{{ $supplier->document_number ?: 'Sem NUIT' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-500">{{ $supplier->user->email ?? 'N/A' }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900">{{ $supplier->whatsapp }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900">{{ $supplier->products->count() }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($supplier->status === 'approved')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aprovado</span>
                                @elseif ($supplier->status === 'pending')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pendente</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Rejeitado</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button wire:click="edit({{ $supplier->id }})"
                                        class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</button>
                                @if ($supplier->status === 'pending')
                                    <button wire:click="updateStatus({{ $supplier->id }}, 'approved')"
                                            class="text-green-600 hover:text-green-900 mr-3">Aprovar</button>
                                    <button wire:click="updateStatus({{ $supplier->id }}, 'rejected')"
                                            class="text-red-600 hover:text-red-900 mr-3">Rejeitar</button>
                                @elseif ($supplier->status === 'approved')
                                    <button wire:click="updateStatus({{ $supplier->id }}, 'rejected')"
                                            class="text-yellow-600 hover:text-yellow-900 mr-3">Suspender</button>
                                @else
                                    <button wire:click="updateStatus({{ $supplier->id }}, 'approved')"
                                            class="text-green-600 hover:text-green-900 mr-3">Reativar</button>
                                @endif
                                <button wire:click="delete({{ $supplier->id }})"
                                        wire:confirm="Tem certeza que deseja excluir este fornecedor?"
                                        class="text-red-600 hover:text-red-900">Excluir</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <p class="mt-4 text-gray-500">Nenhum fornecedor encontrado.</p>
                                <button wire:click="create" class="mt-2 text-green-600 hover:text-green-700 font-medium">
                                    Criar primeiro fornecedor
                                </button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $suppliers->links() }}
        </div>
    @endif
</div>
