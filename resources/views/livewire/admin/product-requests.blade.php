<div>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Solicitacoes de Produtos</h2>
                <p class="mt-1 text-sm text-gray-600">Gerencie as solicitacoes de produtos dos clientes</p>
            </div>

            <!-- Filter -->
            <div class="flex items-center gap-3">
                <select wire:model.live="statusFilter" class="rounded-lg border-gray-300 text-sm focus:ring-green-500 focus:border-green-500">
                    <option value="">Todos os status</option>
                    <option value="pending">Pendentes</option>
                    <option value="reviewing">Em Analise</option>
                    <option value="approved">Aprovados</option>
                    <option value="rejected">Rejeitados</option>
                </select>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl p-4 shadow-sm border">
                <p class="text-xs text-gray-500 uppercase tracking-wide">Total</p>
                <p class="text-2xl font-bold text-gray-900">{{ $requests->count() }}</p>
            </div>
            <div class="bg-yellow-50 rounded-xl p-4 shadow-sm border border-yellow-200">
                <p class="text-xs text-yellow-600 uppercase tracking-wide">Pendentes</p>
                <p class="text-2xl font-bold text-yellow-700">{{ $requests->where('status', 'pending')->count() }}</p>
            </div>
            <div class="bg-blue-50 rounded-xl p-4 shadow-sm border border-blue-200">
                <p class="text-xs text-blue-600 uppercase tracking-wide">Em Analise</p>
                <p class="text-2xl font-bold text-blue-700">{{ $requests->where('status', 'reviewing')->count() }}</p>
            </div>
            <div class="bg-green-50 rounded-xl p-4 shadow-sm border border-green-200">
                <p class="text-xs text-green-600 uppercase tracking-wide">Aprovados</p>
                <p class="text-2xl font-bold text-green-700">{{ $requests->where('status', 'approved')->count() }}</p>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
            @if($requests->isEmpty())
                <div class="p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhuma solicitacao</h3>
                    <p class="mt-1 text-sm text-gray-500">Ainda nao ha solicitacoes de produtos.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produto Solicitado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantidade</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acoes</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($requests as $request)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                <span class="text-blue-600 font-medium">{{ strtoupper(substr($request->name, 0, 1)) }}</span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $request->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $request->email }}</div>
                                                <div class="text-xs text-gray-400">{{ $request->phone }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $request->product_name }}</div>
                                        @if($request->description)
                                            <div class="text-xs text-gray-500 max-w-xs truncate">{{ $request->description }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-medium text-gray-900">{{ number_format($request->quantity_kg, 0, ',', '.') }} kg</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusColors = [
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'reviewing' => 'bg-blue-100 text-blue-800',
                                                'approved' => 'bg-green-100 text-green-800',
                                                'rejected' => 'bg-red-100 text-red-800',
                                            ];
                                            $statusLabels = [
                                                'pending' => 'Pendente',
                                                'reviewing' => 'Em Analise',
                                                'approved' => 'Aprovado',
                                                'rejected' => 'Rejeitado',
                                            ];
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$request->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $statusLabels[$request->status] ?? $request->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $request->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-2">
                                            <button wire:click="openNotesModal({{ $request->id }})" class="text-blue-600 hover:text-blue-900" title="Notas">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </button>
                                            <select wire:change="updateStatus({{ $request->id }}, $event.target.value)" class="text-xs border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
                                                <option value="pending" {{ $request->status === 'pending' ? 'selected' : '' }}>Pendente</option>
                                                <option value="reviewing" {{ $request->status === 'reviewing' ? 'selected' : '' }}>Em Analise</option>
                                                <option value="approved" {{ $request->status === 'approved' ? 'selected' : '' }}>Aprovado</option>
                                                <option value="rejected" {{ $request->status === 'rejected' ? 'selected' : '' }}>Rejeitado</option>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Notes Modal -->
    @if($showNotesModal && $selectedRequest)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="$set('showNotesModal', false)"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Detalhes da Solicitacao
                                </h3>
                                <div class="mt-4 space-y-4">
                                    <div class="bg-gray-50 p-4 rounded-lg space-y-2">
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="text-xs font-medium text-gray-500 uppercase">Cliente</label>
                                                <p class="text-sm text-gray-900">{{ $selectedRequest->name }}</p>
                                            </div>
                                            <div>
                                                <label class="text-xs font-medium text-gray-500 uppercase">Telefone</label>
                                                <p class="text-sm text-gray-900">{{ $selectedRequest->phone }}</p>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="text-xs font-medium text-gray-500 uppercase">Produto</label>
                                            <p class="text-sm text-gray-900">{{ $selectedRequest->product_name }}</p>
                                        </div>
                                        <div>
                                            <label class="text-xs font-medium text-gray-500 uppercase">Quantidade</label>
                                            <p class="text-sm text-gray-900">{{ number_format($selectedRequest->quantity_kg, 0, ',', '.') }} kg</p>
                                        </div>
                                        @if($selectedRequest->description)
                                            <div>
                                                <label class="text-xs font-medium text-gray-500 uppercase">Descricao</label>
                                                <p class="text-sm text-gray-900">{{ $selectedRequest->description }}</p>
                                            </div>
                                        @endif
                                    </div>

                                    <div>
                                        <label for="admin_notes" class="block text-sm font-medium text-gray-700">Notas do Administrador</label>
                                        <textarea wire:model="admin_notes" id="admin_notes" rows="4" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm" placeholder="Adicione suas notas aqui..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="saveNotes" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Salvar Notas
                        </button>
                        <a href="mailto:{{ $selectedRequest->email }}" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Enviar Email
                        </a>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $selectedRequest->phone) }}" target="_blank" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            WhatsApp
                        </a>
                        <button wire:click="$set('showNotesModal', false)" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:w-auto sm:text-sm">
                            Fechar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
