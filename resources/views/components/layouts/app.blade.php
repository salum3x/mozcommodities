<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'MozCommodities' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg border-b border-gray-100 sticky top-0 z-50 backdrop-blur-sm bg-white/95">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex">
                    <!-- Logo -->
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ route('home') }}" class="group">
                            <div class="w-12 h-12 bg-gradient-to-br from-green-600 to-green-800 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-lg">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden space-x-2 sm:-my-px sm:ml-10 sm:flex items-center">
                        <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 rounded-lg {{ request()->routeIs('home') ? 'bg-green-50 text-green-700 font-bold' : 'text-gray-600 hover:text-green-600 hover:bg-green-50/50' }} text-sm font-medium transition-all">
                            Início
                        </a>
                        <a href="{{ route('our.products') }}" class="inline-flex items-center px-4 py-2 rounded-lg {{ request()->routeIs('our.products') ? 'bg-green-50 text-green-700 font-bold' : 'text-gray-600 hover:text-green-600 hover:bg-green-50/50' }} text-sm font-medium transition-all">
                            Nossos Produtos
                        </a>
                        <a href="{{ route('products') }}" class="inline-flex items-center px-4 py-2 rounded-lg {{ request()->routeIs('products') ? 'bg-green-50 text-green-700 font-bold' : 'text-gray-600 hover:text-green-600 hover:bg-green-50/50' }} text-sm font-medium transition-all">
                            Produtos
                        </a>
                        <a href="{{ route('quote.form') }}" class="inline-flex items-center px-4 py-2 rounded-lg {{ request()->routeIs('quote.form') ? 'bg-green-50 text-green-700 font-bold' : 'text-gray-600 hover:text-green-600 hover:bg-green-50/50' }} text-sm font-medium transition-all">
                            Pedir Cotação
                        </a>
                    </div>
                </div>

                <!-- Right Side -->
                <div class="hidden sm:flex sm:items-center sm:ml-6 gap-3">
                    @auth
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</span>
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-purple-50 text-purple-700 rounded-lg text-sm font-bold hover:bg-purple-100 transition-all">
                                    Admin
                                </a>
                            @elseif(auth()->user()->isSupplier())
                                <a href="{{ route('supplier.dashboard') }}" class="px-4 py-2 bg-green-50 text-green-700 rounded-lg text-sm font-bold hover:bg-green-100 transition-all">
                                    Dashboard
                                </a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="px-4 py-2 text-gray-600 hover:text-gray-900 text-sm font-medium transition-all">
                                    Sair
                                </button>
                            </form>
                        </div>
                    @else
                        @php
                            $cartCount = \App\Models\CartItem::getCartCount();
                        @endphp
                        <a href="{{ route('cart') }}" class="relative px-4 py-2 text-gray-700 hover:text-green-600 font-medium transition-all flex items-center gap-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            @if($cartCount > 0)
                                <span class="absolute -top-1 -right-1 bg-green-600 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
                                    {{ $cartCount }}
                                </span>
                            @endif
                        </a>
                        <a href="{{ route('login') }}" class="px-4 py-2 text-gray-700 hover:text-green-600 font-medium transition-all">Entrar</a>
                        <div class="relative" x-data="{ open: false }" @click.away="open = false">
                            <button @click="open = !open" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-green-600 to-green-800 border border-transparent rounded-lg font-bold text-sm text-white hover:from-green-700 hover:to-green-900 transition-all transform hover:scale-105 shadow-lg">
                                Registar
                                <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <!-- Dropdown -->
                            <div x-show="open"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-56 z-50"
                                 style="display: none;">
                                <div class="bg-white rounded-xl shadow-xl border border-gray-200 overflow-hidden">
                                    <a href="{{ route('register.customer') }}" class="block px-4 py-3 hover:bg-blue-50 transition-colors border-b border-gray-100">
                                        <div class="flex items-start gap-3">
                                            <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                            </svg>
                                            <div>
                                                <div class="font-bold text-gray-900">Cliente</div>
                                                <div class="text-xs text-gray-600">Para comprar produtos</div>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="{{ route('register.supplier') }}" class="block px-4 py-3 hover:bg-orange-50 transition-colors">
                                        <div class="flex items-start gap-3">
                                            <svg class="w-5 h-5 text-orange-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                            </svg>
                                            <div>
                                                <div class="font-bold text-gray-900">Fornecedor</div>
                                                <div class="text-xs text-gray-600">Para vender produtos</div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endauth
                </div>

                <!-- Mobile menu button -->
                <div class="flex items-center sm:hidden">
                    <button type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Scrolling News Ticker -->
    @php
        $announcements = \App\Models\Announcement::active()->get();
    @endphp

    @if($announcements->count() > 0)
    <div class="bg-gradient-to-r from-green-600 to-green-700 text-white py-3 overflow-hidden relative">
        <div class="absolute left-0 top-0 bottom-0 w-20 bg-gradient-to-r from-green-600 to-transparent z-10"></div>
        <div class="absolute right-0 top-0 bottom-0 w-20 bg-gradient-to-l from-green-700 to-transparent z-10"></div>

        <div class="ticker-wrapper">
            <div class="ticker-content flex items-center gap-12">
                @foreach($announcements as $announcement)
                    <div class="flex items-center gap-3 whitespace-nowrap">
                        <svg class="w-5 h-5 text-green-200 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                        </svg>
                        <span class="font-medium text-lg">{{ $announcement->message }}</span>
                    </div>
                @endforeach
                <!-- Duplicate for seamless loop -->
                @foreach($announcements as $announcement)
                    <div class="flex items-center gap-3 whitespace-nowrap">
                        <svg class="w-5 h-5 text-green-200 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                        </svg>
                        <span class="font-medium text-lg">{{ $announcement->message }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <style>
        @keyframes ticker {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(-50%);
            }
        }

        .ticker-wrapper {
            display: flex;
            overflow: hidden;
        }

        .ticker-content {
            display: flex;
            animation: ticker 30s linear infinite;
            will-change: transform;
        }

        .ticker-content:hover {
            animation-play-state: paused;
        }
    </style>
    @endif

    <!-- Page Content -->
    <main>
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">MozCommodities</h3>
                    <p class="text-gray-600 text-sm">
                        Líder na comercialização de produtos agrícolas de qualidade em Moçambique.
                    </p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Links Rápidos</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('home') }}" class="text-gray-600 hover:text-green-600 text-sm">Início</a></li>
                        <li><a href="{{ route('products') }}" class="text-gray-600 hover:text-green-600 text-sm">Produtos</a></li>
                        <li><a href="{{ route('quote.form') }}" class="text-gray-600 hover:text-green-600 text-sm">Pedir Cotação</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Contato</h3>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li>WhatsApp: +258 84 000 0000</li>
                        <li>Email: info@mozcommodities.co.mz</li>
                    </ul>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-gray-200 text-center text-sm text-gray-500">
                © {{ date('Y') }} MozCommodities. Todos os direitos reservados.
            </div>
        </div>
    </footer>

    <!-- WhatsApp Flutuante -->
    <a href="https://wa.me/258840000000?text=Olá,%20tenho%20interesse%20nos%20produtos%20da%20MozCommodities"
       target="_blank"
       class="fixed bottom-6 right-6 z-50 bg-green-500 hover:bg-green-600 text-white rounded-full p-4 shadow-2xl transition-all duration-300 transform hover:scale-110 group"
       title="Fale conosco no WhatsApp">
        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
        </svg>
        <span class="absolute -top-2 -left-2 bg-red-500 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center animate-pulse">
            1
        </span>
    </a>

    @livewireScripts
</body>
</html>
