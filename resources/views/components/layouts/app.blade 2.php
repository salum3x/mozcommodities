<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'AgriMarketplace' }}</title>

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
                        <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                            <div class="w-10 h-10 bg-gradient-to-br from-green-600 to-green-800 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <span class="text-2xl font-extrabold">
                                <span class="text-green-600">Moz</span><span class="text-gray-800">Commodities</span>
                            </span>
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden space-x-2 sm:-my-px sm:ml-10 sm:flex items-center">
                        <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 rounded-lg {{ request()->routeIs('home') ? 'bg-green-50 text-green-700 font-bold' : 'text-gray-600 hover:text-green-600 hover:bg-green-50/50' }} text-sm font-medium transition-all">
                            Início
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
                        <a href="{{ route('login') }}" class="px-4 py-2 text-gray-700 hover:text-green-600 font-medium transition-all">Entrar</a>
                        <a href="{{ route('register') }}" class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-green-600 to-green-800 border border-transparent rounded-lg font-bold text-sm text-white hover:from-green-700 hover:to-green-900 transition-all transform hover:scale-105 shadow-lg">
                            Registar
                        </a>
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

    <!-- Page Content -->
    <main>
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">AgriMarketplace</h3>
                    <p class="text-gray-600 text-sm">
                        Conectando fornecedores e compradores de produtos agrícolas em Moçambique.
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
                        <li>Email: info@agrimarketplace.co.mz</li>
                    </ul>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-gray-200 text-center text-sm text-gray-500">
                © {{ date('Y') }} AgriMarketplace. Todos os direitos reservados.
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
