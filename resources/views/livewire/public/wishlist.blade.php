<div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <header class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Lista de Desejos</h1>
                <p class="text-sm text-gray-500 mt-1">{{ $items->count() }} {{ $items->count() == 1 ? 'produto guardado' : 'produtos guardados' }}.</p>
            </div>
            <a href="{{ route('products') }}" class="text-sm text-emerald-700 hover:underline">Continuar a explorar →</a>
        </header>

        @if (session('message'))
            <div class="mb-4 rounded-lg bg-emerald-50 border border-emerald-200 px-4 py-3 text-emerald-800">{{ session('message') }}</div>
        @endif

        @if ($items->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                @foreach ($items as $w)
                    @php $p = $w->product; if (!$p) continue; @endphp
                    <div wire:key="w-{{ $w->id }}" class="group relative bg-white rounded-xl border border-gray-100 hover:border-emerald-300 hover:shadow-md transition overflow-hidden flex flex-col">
                        <button type="button" wire:click="remove({{ $w->id }})" wire:confirm="Remover dos favoritos?"
                                class="absolute top-2 right-2 z-10 w-8 h-8 bg-white/95 backdrop-blur rounded-full shadow flex items-center justify-center hover:bg-red-50 transition"
                                aria-label="Remover">
                            <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                        </button>
                        <a href="{{ route('product.detail', $p->slug) }}" class="block">
                            <div class="relative aspect-square bg-gray-50 overflow-hidden">
                                @if ($p->display_image)
                                    <img src="{{ $p->display_image }}" alt="{{ $p->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                                @else
                                    @php $ph = $p->placeholder; @endphp
                                    <div class="flex items-center justify-center h-full bg-gradient-to-br {{ $ph['bg'] }}">
                                        <span class="text-5xl select-none" role="img">{{ $ph['emoji'] }}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="p-2.5">
                                <span class="text-[10px] text-emerald-700 font-medium uppercase tracking-wide truncate block">{{ $p->category->name ?? '' }}</span>
                                <h3 class="text-sm font-semibold text-gray-900 line-clamp-2 mt-0.5 group-hover:text-emerald-700">{{ $p->name }}</h3>
                                <div class="mt-2 flex items-baseline gap-1">
                                    <span class="text-base font-bold text-gray-900">{{ number_format($p->price_per_kg, 0, ',', '.') }}</span>
                                    <span class="text-[10px] text-gray-500">MZN/{{ $p->unit ?? 'kg' }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center">
                <div class="w-20 h-20 mx-auto rounded-full bg-red-50 flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-red-300" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                </div>
                <h2 class="text-lg font-semibold text-gray-900">Ainda sem favoritos</h2>
                <p class="text-sm text-gray-500 mt-1">Clica no ❤️ em qualquer produto para guardar aqui.</p>
                <a href="{{ route('products') }}" class="inline-flex items-center mt-5 px-5 py-2.5 rounded-xl bg-emerald-600 text-white font-medium hover:bg-emerald-700 transition">
                    Explorar produtos
                </a>
            </div>
        @endif
    </div>
</div>
