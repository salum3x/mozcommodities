<div class="space-y-3">
    @if($showQuantityInput)
        <div class="flex items-center gap-2 bg-gray-50 p-2 rounded-lg">
            <button wire:click="$set('quantity', {{ max(1, $quantity - 1) }})"
                class="w-8 h-8 rounded-lg bg-white hover:bg-gray-100 flex items-center justify-center text-gray-700 font-bold transition-all shadow-sm">
                -
            </button>
            <input type="number" wire:model.live="quantity" min="1"
                class="w-16 text-center border border-gray-300 rounded-lg py-1 font-bold text-sm">
            <button wire:click="$set('quantity', {{ $quantity + 1 }})"
                class="w-8 h-8 rounded-lg bg-white hover:bg-gray-100 flex items-center justify-center text-gray-700 font-bold transition-all shadow-sm">
                +
            </button>
            <span class="text-sm text-gray-600 ml-auto">kg</span>
        </div>
    @endif

    <div class="flex gap-2">
        @if(!$showQuantityInput)
            <button wire:click="toggleQuantityInput"
                class="flex-1 bg-gray-100 text-gray-700 text-center py-3 rounded-lg font-bold hover:bg-gray-200 transition-colors">
                Quantidade
            </button>
        @endif

        <button wire:click="addToCart"
            class="flex-1 bg-green-600 text-white text-center py-3 rounded-lg font-bold hover:bg-green-700 transition-colors flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
            Adicionar
        </button>
    </div>
</div>
