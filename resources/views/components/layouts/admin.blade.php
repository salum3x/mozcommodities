<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Admin - AgriMarketplace' }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <span class="text-2xl font-bold text-green-600">Admin</span>
                        </div>
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('admin.dashboard') ? 'border-green-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700' }} text-sm font-medium">
                                Dashboard
                            </a>
                            <a href="{{ route('admin.categories') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('admin.categories') ? 'border-green-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700' }} text-sm font-medium">
                                Categorias
                            </a>
                            <a href="{{ route('admin.suppliers') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('admin.suppliers') ? 'border-green-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700' }} text-sm font-medium">
                                Fornecedores
                            </a>
                            <a href="{{ route('admin.products') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('admin.products') ? 'border-green-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700' }} text-sm font-medium">
                                Produtos
                            </a>
                            <a href="{{ route('admin.approvals') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('admin.approvals') ? 'border-green-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700' }} text-sm font-medium">
                                Aprovar Produtos
                            </a>
                            <a href="{{ route('admin.product.requests') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('admin.product.requests') ? 'border-green-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700' }} text-sm font-medium">
                                Solicitações
                            </a>
                            <a href="{{ route('admin.quotes') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('admin.quotes') ? 'border-green-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700' }} text-sm font-medium">
                                Cotações
                            </a>
                            <a href="{{ route('admin.announcements') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('admin.announcements') ? 'border-green-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700' }} text-sm font-medium">
                                Anúncios
                            </a>
                        </div>
                    </div>
                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        <div class="flex items-center gap-4">
                            <a href="{{ route('home') }}" class="text-sm text-gray-600 hover:text-gray-900">Ver Site</a>
                            <span class="text-sm text-gray-700">{{ auth()->user()->name }}</span>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-sm text-gray-600 hover:text-gray-900">Sair</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                {{ $slot }}
            </div>
        </main>
    </div>

    @livewireScripts
</body>
</html>
