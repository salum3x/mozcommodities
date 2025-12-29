<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'MozCommodities - Marketplace Agrícola' }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            300: '#86efac',
                            400: '#4ade80',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                        },
                        amazon: {
                            dark: '#131921',
                            blue: '#232f3e',
                            orange: '#febd69',
                            light: '#f3f3f3'
                        }
                    }
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-100" x-data="{ mobileMenuOpen: false }">

    @php $cartCount = \App\Models\CartItem::getCartCount(); @endphp

    <!-- Top Header Bar (Amazon style) -->
    <header class="bg-gray-900 sticky top-0 z-50">
        <!-- Main Header -->
        <div class="bg-gray-900">
            <div class="max-w-[1500px] mx-auto px-4">
                <div class="flex items-center h-16 gap-4">

                    <!-- Logo -->
                    <a href="{{ route('home') }}" class="flex-shrink-0 flex items-center gap-2 pr-4 border-r border-gray-700">
                        <div class="w-9 h-9 bg-green-500 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <div class="hidden sm:block">
                            <span class="text-white font-bold text-lg leading-none">Moz</span>
                            <span class="text-green-400 font-bold text-lg leading-none">Commodities</span>
                        </div>
                    </a>

                    <!-- Delivery Location -->
                    <div class="hidden lg:flex items-center text-white text-sm cursor-pointer hover:outline hover:outline-1 hover:outline-white rounded p-1">
                        <svg class="w-5 h-5 text-white mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <div>
                            <p class="text-gray-400 text-xs">Entregar em</p>
                            <p class="font-bold text-sm">Maputo</p>
                        </div>
                    </div>

                    <!-- Search Bar -->
                    <div class="flex-1 max-w-3xl">
                        <form action="{{ route('products') }}" method="GET" class="flex">
                            <select name="category" class="hidden sm:block bg-gray-200 text-gray-900 text-sm rounded-l-lg px-3 py-2.5 border-r border-gray-300 focus:outline-none cursor-pointer">
                                <option value="">Todos</option>
                                @php $categories = \App\Models\Category::where('is_active', true)->get(); @endphp
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            <input type="text"
                                   name="search"
                                   value="{{ request('search') }}"
                                   placeholder="Pesquisar produtos agrícolas..."
                                   class="flex-1 px-4 py-2.5 text-gray-900 text-sm focus:outline-none {{ request()->has('category') ? '' : 'sm:rounded-l-lg' }}">
                            <button type="submit" class="bg-green-500 hover:bg-green-600 px-4 rounded-r-lg transition">
                                <svg class="w-5 h-5 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </button>
                        </form>
                    </div>

                    <!-- Right Side Actions -->
                    <div class="flex items-center gap-1 sm:gap-2">
                        <!-- Language (optional) -->
                        <div class="hidden xl:flex items-center text-white text-sm cursor-pointer hover:outline hover:outline-1 hover:outline-white rounded p-2">
                            <span class="text-lg mr-1">🇲🇿</span>
                            <span class="font-semibold">PT</span>
                        </div>

                        <!-- Account -->
                        @auth
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" class="flex items-center gap-2 text-white cursor-pointer hover:outline hover:outline-1 hover:outline-white rounded p-2">
                                    <!-- Profile Photo -->
                                    <div class="w-8 h-8 rounded-full overflow-hidden ring-2 ring-green-500 flex-shrink-0">
                                        @if(auth()->user()->profile_photo)
                                            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center text-white font-bold text-sm">
                                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="hidden sm:block text-left">
                                        <span class="text-xs text-gray-300 block">Olá, {{ Str::words(auth()->user()->name, 1, '') }}</span>
                                        <span class="font-bold text-sm flex items-center">
                                            Minha Conta
                                            <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </span>
                                    </div>
                                </button>
                                <div x-show="open" @click.away="open = false" x-cloak
                                     class="absolute right-0 mt-2 w-72 bg-white rounded-xl shadow-2xl border z-50 overflow-hidden">
                                    <!-- User Header -->
                                    <div class="p-4 bg-gradient-to-r from-green-500 to-green-600 text-white">
                                        <div class="flex items-center gap-3">
                                            <div class="w-14 h-14 rounded-full overflow-hidden ring-3 ring-white/50 flex-shrink-0">
                                                @if(auth()->user()->profile_photo)
                                                    <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full bg-white/20 flex items-center justify-center text-white font-bold text-xl">
                                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="font-bold truncate">{{ auth()->user()->name }}</p>
                                                <p class="text-sm text-green-100 truncate">{{ auth()->user()->email }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Menu Items -->
                                    <div class="py-2">
                                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-50 transition">
                                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="font-medium text-sm">Meu Perfil</p>
                                                <p class="text-xs text-gray-500">Ver e editar dados pessoais</p>
                                            </div>
                                        </a>

                                        @if(auth()->user()->isAdmin())
                                            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-50 transition">
                                                <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="font-medium text-sm">Painel Admin</p>
                                                    <p class="text-xs text-gray-500">Gerenciar plataforma</p>
                                                </div>
                                            </a>
                                        @elseif(auth()->user()->isSupplier())
                                            <a href="{{ route('supplier.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-50 transition">
                                                <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="font-medium text-sm">Painel Fornecedor</p>
                                                    <p class="text-xs text-gray-500">Gerenciar meus produtos</p>
                                                </div>
                                            </a>
                                        @endif

                                        <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-50 transition">
                                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="font-medium text-sm">Meus Pedidos</p>
                                                <p class="text-xs text-gray-500">Histórico de compras</p>
                                            </div>
                                        </a>

                                        <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-50 transition">
                                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                                <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="font-medium text-sm">Lista de Desejos</p>
                                                <p class="text-xs text-gray-500">Produtos favoritos</p>
                                            </div>
                                        </a>
                                    </div>

                                    <!-- Logout -->
                                    <div class="border-t p-2">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-red-600 hover:bg-red-50 rounded-lg transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                                </svg>
                                                <span class="font-medium">Sair da Conta</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" class="flex items-center gap-2 text-white cursor-pointer hover:outline hover:outline-1 hover:outline-white rounded p-2">
                                    <div class="w-8 h-8 rounded-full bg-gray-600 flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                    <div class="hidden sm:block text-left">
                                        <span class="text-xs text-gray-300 block">Olá, Faça login</span>
                                        <span class="font-bold text-sm flex items-center">
                                            Entrar
                                            <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </span>
                                    </div>
                                </button>

                                <!-- Login Dropdown -->
                                <div x-show="open" @click.away="open = false" x-cloak
                                     class="absolute right-0 mt-2 w-72 bg-white rounded-xl shadow-2xl border z-50 overflow-hidden">
                                    <!-- Header -->
                                    <div class="p-4 bg-gray-50 border-b">
                                        <p class="text-sm font-semibold text-gray-900">Como deseja entrar?</p>
                                        <p class="text-xs text-gray-500 mt-1">Escolha o tipo de conta</p>
                                    </div>

                                    <!-- Login Options -->
                                    <div class="p-3 space-y-2">
                                        <!-- Cliente -->
                                        <a href="{{ route('login.customer') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-green-50 transition group">
                                            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center group-hover:bg-green-500 transition">
                                                <svg class="w-6 h-6 text-green-600 group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <p class="font-semibold text-gray-900 group-hover:text-green-600 transition">Entrar como Cliente</p>
                                                <p class="text-xs text-gray-500">Comprar produtos</p>
                                            </div>
                                            <svg class="w-5 h-5 text-gray-400 group-hover:text-green-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </a>

                                        <!-- Fornecedor -->
                                        <a href="{{ route('login.supplier') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-orange-50 transition group">
                                            <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center group-hover:bg-orange-500 transition">
                                                <svg class="w-6 h-6 text-orange-600 group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <p class="font-semibold text-gray-900 group-hover:text-orange-600 transition">Entrar como Fornecedor</p>
                                                <p class="text-xs text-gray-500">Vender produtos</p>
                                            </div>
                                            <svg class="w-5 h-5 text-gray-400 group-hover:text-orange-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    </div>

                                    <!-- Register Links -->
                                    <div class="p-3 bg-gray-50 border-t">
                                        <p class="text-xs text-gray-500 text-center mb-2">Ainda não tem conta?</p>
                                        <div class="grid grid-cols-2 gap-2">
                                            <a href="{{ route('register.customer') }}" class="py-2 px-3 bg-green-600 text-white text-center text-sm font-semibold rounded-lg hover:bg-green-700 transition">
                                                Criar Cliente
                                            </a>
                                            <a href="{{ route('register.supplier') }}" class="py-2 px-3 bg-orange-600 text-white text-center text-sm font-semibold rounded-lg hover:bg-orange-700 transition">
                                                Criar Fornecedor
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endauth

                        <!-- Orders -->
                        <a href="#" class="hidden sm:flex flex-col items-start text-white text-sm cursor-pointer hover:outline hover:outline-1 hover:outline-white rounded p-2">
                            <span class="text-xs text-gray-300">Devoluções</span>
                            <span class="font-bold">e Pedidos</span>
                        </a>

                        <!-- Cart (Amazon Style) -->
                        <a href="{{ route('cart') }}" class="flex items-center text-white cursor-pointer hover:outline hover:outline-1 hover:outline-white rounded p-2">
                            <div class="relative">
                                <svg class="w-9 h-9" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <span class="absolute -top-1 left-1/2 -translate-x-1/2 bg-green-500 text-gray-900 text-xs font-bold min-w-[20px] h-5 rounded-full flex items-center justify-center px-1">
                                    {{ $cartCount }}
                                </span>
                            </div>
                            <span class="font-bold text-sm hidden sm:block ml-1">Carrinho</span>
                        </a>

                        <!-- Mobile Menu Button -->
                        <button @click="mobileMenuOpen = true" class="p-2 text-white sm:hidden">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sub Navigation -->
        <div class="bg-gray-800 relative" x-data="{ menuOpen: false }">
            <div class="max-w-[1500px] mx-auto px-4">
                <div class="flex items-center h-10 gap-1 text-sm text-white overflow-x-auto scrollbar-hide">
                    <!-- Menu Dropdown Button -->
                    <button @click="menuOpen = !menuOpen" class="flex items-center gap-1 px-3 py-1 hover:outline hover:outline-1 hover:outline-white rounded font-bold flex-shrink-0 bg-green-600 hover:bg-green-700 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        Todos
                        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': menuOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <a href="{{ route('home') }}" class="px-3 py-1 hover:outline hover:outline-1 hover:outline-white rounded whitespace-nowrap">Início</a>
                    <a href="{{ route('about') }}" class="px-3 py-1 hover:outline hover:outline-1 hover:outline-white rounded whitespace-nowrap">Sobre Nós</a>
                    <a href="{{ route('quote.form') }}" class="px-3 py-1 hover:outline hover:outline-1 hover:outline-white rounded whitespace-nowrap">Pedir Cotação</a>
                    <a href="{{ route('product.request') }}" class="px-3 py-1 hover:outline hover:outline-1 hover:outline-white rounded whitespace-nowrap text-green-400 font-medium">Solicitar Produto</a>
                </div>
            </div>

            <!-- Mega Menu Dropdown -->
            <div x-show="menuOpen" @click.away="menuOpen = false" x-cloak
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2"
                 class="absolute left-0 right-0 top-full bg-white shadow-2xl border-t z-[9999]">
                <div class="max-w-[1500px] mx-auto px-4 py-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

                        <!-- Coluna 1: Tipos de Produtos -->
                        <div>
                            <h3 class="font-bold text-gray-900 mb-4 pb-2 border-b-2 border-green-500 flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                                Tipos de Produtos
                            </h3>
                            <div class="space-y-1">
                                <a href="{{ route('our.products') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-green-50 transition group">
                                    <span class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-500 transition">
                                        <svg class="w-4 h-4 text-green-600 group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                        </svg>
                                    </span>
                                    <div>
                                        <p class="font-medium text-gray-900 text-sm">Nossos Produtos</p>
                                        <p class="text-xs text-gray-500">Produtos da empresa</p>
                                    </div>
                                </a>
                                <a href="{{ route('supplier.products') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-orange-50 transition group">
                                    <span class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center group-hover:bg-orange-500 transition">
                                        <svg class="w-4 h-4 text-orange-600 group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </span>
                                    <div>
                                        <p class="font-medium text-gray-900 text-sm">Fornecedores</p>
                                        <p class="text-xs text-gray-500">Produtos de parceiros</p>
                                    </div>
                                </a>
                                <a href="{{ route('products') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 transition group">
                                    <span class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center group-hover:bg-gray-700 transition">
                                        <svg class="w-4 h-4 text-gray-600 group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                        </svg>
                                    </span>
                                    <div>
                                        <p class="font-medium text-gray-900 text-sm">Todos os Produtos</p>
                                        <p class="text-xs text-gray-500">Ver catálogo completo</p>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <!-- Coluna 2 & 3: Categorias -->
                        <div class="md:col-span-2">
                            <h3 class="font-bold text-gray-900 mb-4 pb-2 border-b-2 border-green-500 flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                                </svg>
                                Categorias
                            </h3>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach($categories as $category)
                                    <a href="{{ route('category.products', $category->slug) }}"
                                       class="flex items-center gap-2 px-3 py-2.5 rounded-lg hover:bg-green-50 transition group border border-transparent hover:border-green-200">
                                        <span class="w-2 h-2 bg-green-500 rounded-full group-hover:scale-125 transition"></span>
                                        <span class="text-sm text-gray-700 group-hover:text-green-700 font-medium">{{ $category->name }}</span>
                                        <span class="ml-auto text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full group-hover:bg-green-100 group-hover:text-green-700">
                                            {{ $category->products_count ?? 0 }}
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <!-- Coluna 4: Servicos -->
                        <div>
                            <h3 class="font-bold text-gray-900 mb-4 pb-2 border-b-2 border-green-500 flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                Serviços
                            </h3>
                            <div class="space-y-2">
                                <a href="{{ route('quote.form') }}" class="block px-4 py-3 bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl hover:from-blue-100 hover:to-blue-200 transition">
                                    <p class="font-bold text-blue-900 text-sm">Pedir Cotação</p>
                                    <p class="text-xs text-blue-700">Solicite preços especiais</p>
                                </a>
                                <a href="{{ route('product.request') }}" class="block px-4 py-3 bg-gradient-to-r from-green-50 to-green-100 rounded-xl hover:from-green-100 hover:to-green-200 transition">
                                    <p class="font-bold text-green-900 text-sm">Solicitar Produto</p>
                                    <p class="text-xs text-green-700">Não encontrou? Peça-nos!</p>
                                </a>
                                <a href="{{ route('register.supplier') }}" class="block px-4 py-3 bg-gradient-to-r from-orange-50 to-orange-100 rounded-xl hover:from-orange-100 hover:to-orange-200 transition">
                                    <p class="font-bold text-orange-900 text-sm">Seja Fornecedor</p>
                                    <p class="text-xs text-orange-700">Venda na plataforma</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile Menu -->
    <div x-show="mobileMenuOpen" x-cloak class="fixed inset-0 z-50">
        <div @click="mobileMenuOpen = false" class="fixed inset-0 bg-black/60"></div>
        <div class="fixed inset-y-0 left-0 w-80 bg-white shadow-xl overflow-y-auto">
            <!-- User Header -->
            <div class="bg-gray-800 text-white p-4 flex items-center gap-3">
                <div class="w-10 h-10 bg-gray-600 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                @auth
                    <span class="font-bold">Olá, {{ auth()->user()->name }}</span>
                @else
                    <a href="{{ route('login') }}" class="font-bold">Olá, faça login</a>
                @endauth
                <button @click="mobileMenuOpen = false" class="ml-auto">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Menu Items -->
            <div class="py-2">
                <!-- Tipos de Produtos -->
                <div class="px-4 py-2 font-bold text-sm text-gray-500 uppercase tracking-wider">Tipos de Produtos</div>
                <a href="{{ route('our.products') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-green-50 transition">
                    <span class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                        </svg>
                    </span>
                    <div>
                        <p class="font-medium text-gray-900">Nossos Produtos</p>
                        <p class="text-xs text-gray-500">Produtos da empresa</p>
                    </div>
                </a>
                <a href="{{ route('supplier.products') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-orange-50 transition">
                    <span class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </span>
                    <div>
                        <p class="font-medium text-gray-900">Fornecedores</p>
                        <p class="text-xs text-gray-500">Produtos de parceiros</p>
                    </div>
                </a>
                <a href="{{ route('products') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-100 transition border-b">
                    <span class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                    </span>
                    <div>
                        <p class="font-medium text-gray-900">Todos os Produtos</p>
                        <p class="text-xs text-gray-500">Ver catálogo completo</p>
                    </div>
                </a>

                <!-- Categorias -->
                <div class="px-4 py-2 font-bold text-sm text-gray-500 uppercase tracking-wider mt-2">Categorias</div>
                @foreach($categories as $category)
                    <a href="{{ route('category.products', $category->slug) }}" class="flex items-center justify-between px-4 py-3 hover:bg-green-50 transition">
                        <span class="flex items-center gap-2">
                            <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                            <span class="text-gray-700">{{ $category->name }}</span>
                        </span>
                        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">{{ $category->products_count ?? 0 }}</span>
                    </a>
                @endforeach

                <!-- Serviços -->
                <div class="px-4 py-2 font-bold text-sm text-gray-500 uppercase tracking-wider mt-2 border-t pt-4">Serviços</div>
                <a href="{{ route('quote.form') }}" class="block px-6 py-3 hover:bg-gray-100">Pedir Cotação</a>
                <a href="{{ route('product.request') }}" class="block px-6 py-3 hover:bg-gray-100 text-green-600">Solicitar Produto</a>

                <div class="px-4 py-2 font-bold text-lg border-t border-b mt-2">Ajuda e Definições</div>
                <a href="{{ route('cart') }}" class="block px-6 py-3 hover:bg-gray-100">Seu Carrinho</a>
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="block px-6 py-3 hover:bg-gray-100">Painel Admin</a>
                    @elseif(auth()->user()->isSupplier())
                        <a href="{{ route('supplier.dashboard') }}" class="block px-6 py-3 hover:bg-gray-100">Meu Painel</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="border-t">
                        @csrf
                        <button type="submit" class="block w-full text-left px-6 py-3 text-red-600 hover:bg-red-50">Sair</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block px-6 py-3 hover:bg-gray-100 font-semibold text-green-600">Entrar</a>
                    <a href="{{ route('register.customer') }}" class="block px-6 py-3 hover:bg-gray-100">Criar Conta</a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Page Content -->
    <main class="min-h-screen">
        {{ $slot }}
    </main>

    <!-- Footer (Amazon style) -->
    <footer class="bg-gray-800 text-white mt-12">
        <!-- Back to Top -->
        <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" class="w-full py-4 bg-gray-700 hover:bg-gray-600 text-sm font-medium transition">
            Voltar ao topo
        </button>

        <!-- Footer Links -->
        <div class="max-w-[1500px] mx-auto px-4 py-10">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <!-- Column 1 -->
                <div>
                    <h3 class="font-bold mb-4">Conheça-nos</h3>
                    <ul class="space-y-2 text-sm text-gray-300">
                        <li><a href="#" class="hover:text-white hover:underline">Sobre MozCommodities</a></li>
                        <li><a href="#" class="hover:text-white hover:underline">Carreiras</a></li>
                        <li><a href="#" class="hover:text-white hover:underline">Sustentabilidade</a></li>
                    </ul>
                </div>

                <!-- Column 2 -->
                <div>
                    <h3 class="font-bold mb-4">Ganhe Dinheiro</h3>
                    <ul class="space-y-2 text-sm text-gray-300">
                        <li><a href="{{ route('register.supplier') }}" class="hover:text-white hover:underline">Venda no MozCommodities</a></li>
                        <li><a href="{{ route('register.supplier') }}" class="hover:text-white hover:underline">Seja um Fornecedor</a></li>
                        <li><a href="{{ route('register.supplier') }}" class="hover:text-white hover:underline">Anuncie seus Produtos</a></li>
                    </ul>
                </div>

                <!-- Column 3 -->
                <div>
                    <h3 class="font-bold mb-4">Pagamento</h3>
                    <ul class="space-y-2 text-sm text-gray-300">
                        <li><a href="#" class="hover:text-white hover:underline">M-Pesa</a></li>
                        <li><a href="#" class="hover:text-white hover:underline">e-Mola</a></li>
                        <li><a href="#" class="hover:text-white hover:underline">Cartão de Crédito</a></li>
                    </ul>
                </div>

                <!-- Column 4 -->
                <div>
                    <h3 class="font-bold mb-4">Ajuda</h3>
                    <ul class="space-y-2 text-sm text-gray-300">
                        <li><a href="{{ route('profile.edit') }}" class="hover:text-white hover:underline">Sua Conta</a></li>
                        <li><a href="{{ route('cart') }}" class="hover:text-white hover:underline">Seu Carrinho</a></li>
                        <li><a href="{{ route('quote.form') }}" class="hover:text-white hover:underline">Pedir Cotação</a></li>
                        <li><a href="{{ route('product.request') }}" class="hover:text-white hover:underline">Solicitar Produto</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Bottom Footer -->
        <div class="border-t border-gray-700">
            <div class="max-w-[1500px] mx-auto px-4 py-6">
                <div class="flex flex-col md:flex-row items-center justify-center gap-4">
                    <a href="{{ route('home') }}" class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <span class="font-bold">MozCommodities</span>
                    </a>
                </div>
                <div class="text-center text-xs text-gray-400 mt-4">
                    &copy; {{ date('Y') }} MozCommodities. Todos os direitos reservados. Moçambique
                </div>
            </div>
        </div>
    </footer>

    <!-- WhatsApp Float -->
    <a href="https://wa.me/258840000000" target="_blank"
       class="fixed bottom-6 right-6 w-14 h-14 bg-green-500 rounded-full flex items-center justify-center shadow-lg hover:scale-110 transition-transform z-40">
        <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
        </svg>
    </a>

    @livewireScripts
</body>
</html>
