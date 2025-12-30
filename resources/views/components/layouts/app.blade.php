<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'MozCommodities' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-50">
    <!-- Navigation -->
    <x-navbar />

    <!-- Announcements Ticker -->
    @php
        $announcements = \App\Models\Announcement::active()->get();
    @endphp

    @if($announcements->count() > 0)
    <div class="bg-green-600 text-white py-2.5 overflow-hidden">
        <div class="relative flex overflow-x-hidden">
            <div class="animate-marquee flex whitespace-nowrap">
                @foreach($announcements as $announcement)
                    <span class="mx-8 flex items-center gap-2 text-sm font-medium">
                        <svg class="h-4 w-4 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                        </svg>
                        {{ $announcement->message }}
                    </span>
                @endforeach
            </div>
            <div class="absolute top-0 animate-marquee2 flex whitespace-nowrap">
                @foreach($announcements as $announcement)
                    <span class="mx-8 flex items-center gap-2 text-sm font-medium">
                        <svg class="h-4 w-4 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                        </svg>
                        {{ $announcement->message }}
                    </span>
                @endforeach
            </div>
        </div>
    </div>

    <style>
        @keyframes marquee {
            0% { transform: translateX(0%); }
            100% { transform: translateX(-100%); }
        }
        @keyframes marquee2 {
            0% { transform: translateX(100%); }
            100% { transform: translateX(0%); }
        }
        .animate-marquee {
            animation: marquee 25s linear infinite;
        }
        .animate-marquee2 {
            animation: marquee2 25s linear infinite;
        }
    </style>
    @endif

    <!-- Page Content -->
    <main>
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-8 md:grid-cols-4">
                <!-- Brand -->
                <div class="md:col-span-1">
                    <div class="flex items-center gap-2">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gradient-to-br from-green-600 to-green-700">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <span class="text-lg font-bold text-gray-900">MozCommodities</span>
                    </div>
                    <p class="mt-4 text-sm text-gray-600">
                        Líder na comercialização de produtos agrícolas de qualidade em Moçambique.
                    </p>
                </div>

                <!-- Links -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-900">Navegação</h3>
                    <ul class="mt-4 space-y-3">
                        <li><a href="{{ route('home') }}" class="text-sm text-gray-600 hover:text-green-600 transition-colors">Início</a></li>
                        <li><a href="{{ route('our.products') }}" class="text-sm text-gray-600 hover:text-green-600 transition-colors">Nossos Produtos</a></li>
                        <li><a href="{{ route('products') }}" class="text-sm text-gray-600 hover:text-green-600 transition-colors">Produtos</a></li>
                        <li><a href="{{ route('quote.form') }}" class="text-sm text-gray-600 hover:text-green-600 transition-colors">Pedir Cotação</a></li>
                    </ul>
                </div>

                <!-- Account -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-900">Conta</h3>
                    <ul class="mt-4 space-y-3">
                        <li><a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-green-600 transition-colors">Entrar</a></li>
                        <li><a href="{{ route('register.customer') }}" class="text-sm text-gray-600 hover:text-green-600 transition-colors">Registar como Cliente</a></li>
                        <li><a href="{{ route('register.supplier') }}" class="text-sm text-gray-600 hover:text-green-600 transition-colors">Registar como Fornecedor</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-900">Contacto</h3>
                    <ul class="mt-4 space-y-3">
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            +258 84 000 0000
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            info@mozcommodities.co.mz
                        </li>
                    </ul>
                </div>
            </div>

            <div class="mt-8 border-t border-gray-200 pt-8">
                <p class="text-center text-sm text-gray-500">
                    &copy; {{ date('Y') }} MozCommodities. Todos os direitos reservados.
                </p>
            </div>
        </div>
    </footer>

    <!-- WhatsApp Button -->
    <a href="https://wa.me/258840000000?text=Olá,%20tenho%20interesse%20nos%20produtos%20da%20MozCommodities"
       target="_blank"
       class="fixed bottom-6 right-6 z-50 flex h-14 w-14 items-center justify-center rounded-full bg-green-500 text-white shadow-lg transition-all hover:scale-110 hover:bg-green-600">
        <svg class="h-7 w-7" fill="currentColor" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
        </svg>
    </a>

    @livewireScripts
</body>
</html>
