<div>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Cotacoes</h2>
                <p class="mt-1 text-sm text-gray-600">Gerencie as solicitacoes de cotacao dos clientes</p>
            </div>

            <!-- Filter -->
            <div class="flex items-center gap-3">
                <select wire:model.live="statusFilter" class="rounded-lg border-gray-300 text-sm focus:ring-green-500 focus:border-green-500">
                    <option value="">Todos os status</option>
                    <option value="pending">Pendentes</option>
                    <option value="contacted">Contactados</option>
                    <option value="quoted">Cotados</option>
                    <option value="completed">Concluidos</option>
                    <option value="cancelled">Cancelados</option>
                </select>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <div class="bg-white rounded-xl p-4 shadow-sm border">
                <p class="text-xs text-gray-500 uppercase tracking-wide">Total</p>
                <p class="text-2xl font-bold text-gray-900">{{ $quotes->count() }}</p>
            </div>
            <div class="bg-yellow-50 rounded-xl p-4 shadow-sm border border-yellow-200">
                <p class="text-xs text-yellow-600 uppercase tracking-wide">Pendentes</p>
                <p class="text-2xl font-bold text-yellow-700">{{ $quotes->where('status', 'pending')->count() }}</p>
            </div>
            <div class="bg-blue-50 rounded-xl p-4 shadow-sm border border-blue-200">
                <p class="text-xs text-blue-600 uppercase tracking-wide">Contactados</p>
                <p class="text-2xl font-bold text-blue-700">{{ $quotes->where('status', 'contacted')->count() }}</p>
            </div>
            <div class="bg-purple-50 rounded-xl p-4 shadow-sm border border-purple-200">
                <p class="text-xs text-purple-600 uppercase tracking-wide">Cotados</p>
                <p class="text-2xl font-bold text-purple-700">{{ $quotes->where('status', 'quoted')->count() }}</p>
            </div>
            <div class="bg-green-50 rounded-xl p-4 shadow-sm border border-green-200">
                <p class="text-xs text-green-600 uppercase tracking-wide">Concluidos</p>
                <p class="text-2xl font-bold text-green-700">{{ $quotes->where('status', 'completed')->count() }}</p>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
            @if($quotes->isEmpty())
                <div class="p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhuma cotacao</h3>
                    <p class="mt-1 text-sm text-gray-500">Ainda nao ha solicitacoes de cotacao.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produto</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantidade</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acoes</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($quotes as $quote)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-green-100 rounded-full flex items-center justify-center">
                                                <span class="text-green-600 font-medium">{{ strtoupper(substr($quote->name, 0, 1)) }}</span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $quote->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $quote->email }}</div>
                                                <div class="text-xs text-gray-400">{{ $quote->phone }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ $quote->product?->name ?? 'Produto removido' }}</div>
                                        @if($quote->company_name)
                                            <div class="text-xs text-gray-500">Empresa: {{ $quote->company_name }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-medium text-gray-900">{{ number_format($quote->quantity, 0, ',', '.') }} kg</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusColors = [
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'contacted' => 'bg-blue-100 text-blue-800',
                                                'quoted' => 'bg-purple-100 text-purple-800',
                                                'completed' => 'bg-green-100 text-green-800',
                                                'cancelled' => 'bg-red-100 text-red-800',
                                            ];
                                            $statusLabels = [
                                                'pending' => 'Pendente',
                                                'contacted' => 'Contactado',
                                                'quoted' => 'Cotado',
                                                'completed' => 'Concluido',
                                                'cancelled' => 'Cancelado',
                                            ];
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$quote->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $statusLabels[$quote->status] ?? $quote->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $quote->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-2">
                                            <button wire:click="viewDetails({{ $quote->id }})" class="text-green-600 hover:text-green-900" title="Ver detalhes">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </button>
                                            <select wire:change="updateStatus({{ $quote->id }}, $event.target.value)" class="text-xs border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
                                                <option value="pending" {{ $quote->status === 'pending' ? 'selected' : '' }}>Pendente</option>
                                                <option value="contacted" {{ $quote->status === 'contacted' ? 'selected' : '' }}>Contactado</option>
                                                <option value="quoted" {{ $quote->status === 'quoted' ? 'selected' : '' }}>Cotado</option>
                                                <option value="completed" {{ $quote->status === 'completed' ? 'selected' : '' }}>Concluido</option>
                                                <option value="cancelled" {{ $quote->status === 'cancelled' ? 'selected' : '' }}>Cancelado</option>
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

    <!-- Details Modal -->
    @if($showModal && $selectedQuote)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Detalhes da Cotacao
                                </h3>
                                <div class="mt-4 space-y-3">
                                    <div>
                                        <label class="text-xs font-medium text-gray-500 uppercase">Cliente</label>
                                        <p class="text-sm text-gray-900">{{ $selectedQuote->name }}</p>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="text-xs font-medium text-gray-500 uppercase">Email</label>
                                            <p class="text-sm text-gray-900">{{ $selectedQuote->email }}</p>
                                        </div>
                                        <div>
                                            <label class="text-xs font-medium text-gray-500 uppercase">Telefone</label>
                                            <p class="text-sm text-gray-900">{{ $selectedQuote->phone }}</p>
                                        </div>
                                    </div>
                                    @if($selectedQuote->company_name)
                                        <div>
                                            <label class="text-xs font-medium text-gray-500 uppercase">Empresa</label>
                                            <p class="text-sm text-gray-900">{{ $selectedQuote->company_name }}</p>
                                        </div>
                                    @endif
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="text-xs font-medium text-gray-500 uppercase">Produto</label>
                                            <p class="text-sm text-gray-900">{{ $selectedQuote->product?->name ?? 'N/A' }}</p>
                                        </div>
                                        <div>
                                            <label class="text-xs font-medium text-gray-500 uppercase">Quantidade</label>
                                            <p class="text-sm text-gray-900">{{ number_format($selectedQuote->quantity, 0, ',', '.') }} kg</p>
                                        </div>
                                    </div>
                                    @if($selectedQuote->message)
                                        <div>
                                            <label class="text-xs font-medium text-gray-500 uppercase">Mensagem</label>
                                            <p class="text-sm text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $selectedQuote->message }}</p>
                                        </div>
                                    @endif
                                    <div>
                                        <label class="text-xs font-medium text-gray-500 uppercase">Data</label>
                                        <p class="text-sm text-gray-900">{{ $selectedQuote->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <a href="mailto:{{ $selectedQuote->email }}" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Enviar Email
                        </a>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $selectedQuote->phone) }}" target="_blank" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            WhatsApp
                        </a>
                        <button wire:click="closeModal" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:w-auto sm:text-sm">
                            Fechar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
