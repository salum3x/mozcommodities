<div class="p-6 max-w-4xl mx-auto">
    <header class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Pagamentos</h1>
        <p class="text-sm text-gray-500 mt-1">Configura as credenciais dos gateways. Sem credenciais, o método aparece como "Indisponível" no checkout.</p>
    </header>

    @if (session('message'))
        <div class="mb-4 rounded-lg bg-emerald-50 border border-emerald-200 px-4 py-3 text-emerald-800">{{ session('message') }}</div>
    @endif

    <form wire:submit.prevent="save" class="space-y-6">

        <!-- e-Mola (Movitel) -->
        <section class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <header class="p-5 border-b flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-orange-50 border border-orange-200 rounded-lg flex items-center justify-center font-bold text-orange-700">e-Mola</div>
                    <div>
                        <h2 class="font-bold text-gray-900">e-Mola — MozCommodities</h2>
                        <p class="text-xs text-gray-500">Pagamento móvel Movitel</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="rounded-full px-2.5 py-0.5 text-xs font-medium {{ $emolaReady ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                        {{ $emolaReady ? 'Pronto' : 'Falta configurar' }}
                    </span>
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" wire:model.live="payment_emola_enabled" class="rounded text-emerald-600 focus:ring-emerald-500">
                        <span class="text-sm text-gray-700">Habilitado</span>
                    </label>
                </div>
            </header>
            <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">API Key *</label>
                    <input type="password" wire:model="emola_api_key" autocomplete="off"
                           placeholder="ex.: 8iPqiX76tpWVmj9zzDvfT"
                           class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm font-mono">
                    @error('emola_api_key') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Partner Code *</label>
                    <input type="text" wire:model="emola_partner_code"
                           placeholder="ex.: 925300"
                           class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm font-mono">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Username *</label>
                    <input type="text" wire:model="emola_username" autocomplete="off"
                           placeholder="ex.: 7f029d18a9793446"
                           class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm font-mono">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password *</label>
                    <input type="password" wire:model="emola_password" autocomplete="off"
                           placeholder="hash atribuído pela Movitel"
                           class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm font-mono">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Endpoint (opcional)</label>
                    <input type="url" wire:model="emola_base_url"
                           placeholder="Padrão: http://tv.itcore.co.za/emola/file.php"
                           class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm font-mono">
                    <p class="text-xs text-gray-500 mt-1">Deixa vazio para usar o endpoint iTcore padrão.</p>
                </div>
                <div class="md:col-span-2">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" wire:model="emola_sandbox" class="rounded text-emerald-600 focus:ring-emerald-500">
                        <span class="text-sm text-gray-700">Modo sandbox (testes)</span>
                    </label>
                </div>
                <div class="md:col-span-2 bg-amber-50 border border-amber-200 rounded-lg p-3 text-xs text-amber-900">
                    <p class="font-semibold mb-1">Validação do número e-Mola</p>
                    <p>Apenas números <strong>Movitel</strong> (prefixos 86 ou 87) são aceites. Formato: +258 86 1234567 ou 861234567.</p>
                </div>
                <div class="md:col-span-2 bg-blue-50 border border-blue-200 rounded-lg p-3 text-xs text-blue-900">
                    <p class="font-semibold mb-1">Webhook de callback</p>
                    <p>Configura este URL no portal Movitel para receber confirmações assíncronas:</p>
                    <code class="block mt-1.5 px-2 py-1.5 bg-white border border-blue-200 rounded text-blue-900 font-mono break-all">{{ url('/webhooks/emola') }}</code>
                </div>
                <div class="md:col-span-2 bg-emerald-50 border border-emerald-200 rounded-lg p-3 text-xs text-emerald-900">
                    <p class="font-semibold mb-1">Transações</p>
                    <p>Prefixo das transações: <strong>MZC</strong> (MozCommodities). Aparece em <code class="bg-white px-1 rounded">refPay</code> e <code class="bg-white px-1 rounded">transId</code>, facilita conciliação.</p>
                </div>
            </div>
        </section>

        <!-- M-Pesa -->
        <section class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <header class="p-5 border-b flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-red-50 border border-red-200 rounded-lg flex items-center justify-center font-bold text-red-700">M-Pesa</div>
                    <div>
                        <h2 class="font-bold text-gray-900">M-Pesa</h2>
                        <p class="text-xs text-gray-500">Vodacom Moçambique IPG (C2B)</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="rounded-full px-2.5 py-0.5 text-xs font-medium {{ $mpesaReady ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                        {{ $mpesaReady ? 'Pronto' : 'Falta configurar' }}
                    </span>
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" wire:model.live="payment_mpesa_enabled" class="rounded text-emerald-600 focus:ring-emerald-500">
                        <span class="text-sm text-gray-700">Habilitado</span>
                    </label>
                </div>
            </header>
            <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">API Key *</label>
                    <input type="password" wire:model="mpesa_api_key" autocomplete="off"
                           class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm font-mono">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Public Key (PEM, sem cabeçalho) *</label>
                    <textarea wire:model="mpesa_public_key" rows="4" autocomplete="off"
                              placeholder="MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA..."
                              class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm font-mono"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Service Provider Code *</label>
                    <input type="text" wire:model="mpesa_service_provider_code"
                           placeholder="171717"
                           class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm font-mono">
                </div>
                <div>
                    <label class="inline-flex items-center gap-2 cursor-pointer mt-7">
                        <input type="checkbox" wire:model="mpesa_sandbox" class="rounded text-emerald-600 focus:ring-emerald-500">
                        <span class="text-sm text-gray-700">Sandbox</span>
                    </label>
                </div>
                <div class="md:col-span-2 bg-blue-50 border border-blue-200 rounded-lg p-3 text-xs text-blue-900">
                    <p class="font-semibold mb-1">Webhook de callback</p>
                    <code class="block px-2 py-1.5 bg-white border border-blue-200 rounded text-blue-900 font-mono break-all">{{ url('/webhooks/mpesa') }}</code>
                </div>
            </div>
        </section>

        <!-- Cartão -->
        <section class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <header class="p-5 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-blue-50 border border-blue-200 rounded-lg flex items-center justify-center font-bold text-blue-700">Cartão</div>
                    <div>
                        <h2 class="font-bold text-gray-900">Cartão Visa/Mastercard</h2>
                        <p class="text-xs text-gray-500">Integração futura — desabilitado por agora</p>
                    </div>
                </div>
                <label class="inline-flex items-center gap-2 cursor-not-allowed opacity-60">
                    <input type="checkbox" wire:model="payment_card_enabled" disabled class="rounded text-emerald-600">
                    <span class="text-sm text-gray-700">Habilitado</span>
                </label>
            </header>
        </section>

        <div class="flex items-center justify-end gap-2 sticky bottom-4 bg-gray-50/80 backdrop-blur-sm rounded-xl p-3 border border-gray-100">
            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-100 text-sm">Voltar</a>
            <button type="submit" wire:loading.attr="disabled" wire:target="save"
                    class="px-5 py-2.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 disabled:opacity-60 text-white text-sm font-semibold">
                <span wire:loading.remove wire:target="save">Guardar configurações</span>
                <span wire:loading wire:target="save">A guardar…</span>
            </button>
        </div>
    </form>
</div>
