<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="inline-block mb-4 px-4 py-2 bg-green-100 rounded-full">
                <span class="text-sm font-bold text-green-800">💬 Pedido Especial</span>
            </div>
            <h1 class="text-4xl font-extrabold text-gray-900 mb-4">
                Não Encontrou o que Procura?
            </h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Envie-nos os detalhes do produto que procura e entraremos em contacto consigo com uma cotação personalizada
            </p>
        </div>

        <!-- Success Message -->
        @if($showSuccessMessage)
        <div class="mb-8 bg-green-50 border-2 border-green-200 rounded-xl p-6 animate-fade-in">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-green-800 mb-1">Pedido Enviado com Sucesso!</h3>
                    <p class="text-green-700">
                        Obrigado! Recebemos o seu pedido e entraremos em contacto em breve com uma cotação.
                    </p>
                </div>
            </div>
        </div>
        @endif

        <!-- Form -->
        <div class="bg-white rounded-2xl shadow-xl p-8 md:p-10 border border-gray-100">
            <form wire:submit="submit" class="space-y-6">
                <!-- Nome -->
                <div>
                    <label for="name" class="block text-sm font-bold text-gray-900 mb-2">
                        Nome Completo *
                    </label>
                    <input type="text"
                           id="name"
                           wire:model="name"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('name') border-red-500 @enderror"
                           placeholder="Seu nome completo">
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email e Telefone -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="email" class="block text-sm font-bold text-gray-900 mb-2">
                            Email *
                        </label>
                        <input type="email"
                               id="email"
                               wire:model="email"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('email') border-red-500 @enderror"
                               placeholder="seuemail@exemplo.com">
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-bold text-gray-900 mb-2">
                            Telefone *
                        </label>
                        <input type="tel"
                               id="phone"
                               wire:model="phone"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('phone') border-red-500 @enderror"
                               placeholder="+258 84 000 0000">
                        @error('phone')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Nome do Produto e Quantidade -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="product_name" class="block text-sm font-bold text-gray-900 mb-2">
                            Nome do Produto *
                        </label>
                        <input type="text"
                               id="product_name"
                               wire:model="product_name"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('product_name') border-red-500 @enderror"
                               placeholder="Ex: Milho, Arroz, Feijão...">
                        @error('product_name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="quantity_kg" class="block text-sm font-bold text-gray-900 mb-2">
                            Quantidade (kg) <span class="text-gray-500 font-normal">- Opcional</span>
                        </label>
                        <input type="number"
                               id="quantity_kg"
                               wire:model="quantity_kg"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('quantity_kg') border-red-500 @enderror"
                               placeholder="1000"
                               min="1">
                        @error('quantity_kg')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Descrição -->
                <div>
                    <label for="description" class="block text-sm font-bold text-gray-900 mb-2">
                        Descrição Detalhada *
                    </label>
                    <textarea
                        id="description"
                        wire:model="description"
                        rows="5"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('description') border-red-500 @enderror"
                        placeholder="Descreva o produto que procura, especificações, qualidade desejada, etc."></textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-sm text-gray-500">
                        Quanto mais detalhes fornecer, melhor poderemos ajudá-lo
                    </p>
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit"
                            class="w-full bg-gradient-to-r from-green-600 to-green-700 text-white py-4 px-6 rounded-lg font-bold text-lg hover:from-green-700 hover:to-green-800 transition-all duration-300 transform hover:scale-[1.02] shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        Enviar Pedido
                    </button>
                </div>
            </form>
        </div>

        <!-- Info Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
            <div class="bg-white rounded-xl shadow-md p-6 text-center">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 mb-2">Resposta Rápida</h3>
                <p class="text-sm text-gray-600">Respondemos em até 24 horas</p>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6 text-center">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 mb-2">Sem Compromisso</h3>
                <p class="text-sm text-gray-600">Cotação gratuita e sem obrigações</p>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6 text-center">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 mb-2">Melhor Preço</h3>
                <p class="text-sm text-gray-600">Garantimos o melhor preço do mercado</p>
            </div>
        </div>
    </div>
</div>
