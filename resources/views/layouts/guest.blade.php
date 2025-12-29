<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <!-- Additional head content -->
        {{ $header ?? '' }}
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex">
            <!-- Left side - Branding (Fixed/Sticky) -->
            <div class="hidden lg:block lg:w-1/2 bg-gradient-to-br from-green-600 via-green-700 to-green-800 relative">
                <div class="sticky top-0 h-screen overflow-hidden">
                    <!-- Decorative elements -->
                    <div class="absolute inset-0 opacity-10">
                        <div class="absolute top-0 left-0 w-96 h-96 bg-white rounded-full filter blur-3xl transform -translate-x-1/2 -translate-y-1/2"></div>
                        <div class="absolute bottom-0 right-0 w-96 h-96 bg-white rounded-full filter blur-3xl transform translate-x-1/2 translate-y-1/2"></div>
                    </div>

                    <!-- Content -->
                    <div class="relative z-10 flex flex-col justify-start h-full px-16 pt-24 text-white">
                        <a href="{{ route('home') }}" class="flex items-center gap-3 mb-8 mt-12 group">
                            <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 border border-white/30">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <span class="text-3xl font-extrabold">
                                Moz<span class="text-green-200">Commodities</span>
                            </span>
                        </a>

                        <h1 class="text-4xl font-extrabold mb-6 leading-tight">
                            Bem-vindo à plataforma líder em commodities agrícolas
                        </h1>

                        <p class="text-xl text-green-50 mb-8 leading-relaxed">
                            Conectamos produtores e compradores, oferecendo os melhores preços e qualidade premium em todo Moçambique.
                        </p>

                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <p class="text-lg text-green-50">Produtos certificados e de qualidade premium</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <p class="text-lg text-green-50">Melhores preços do mercado garantidos</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <p class="text-lg text-green-50">Plataforma segura e profissional</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right side - Form (Scrollable) -->
            <div class="w-full lg:w-1/2 px-6 py-12 bg-gray-50 overflow-y-auto">
                <div class="w-full max-w-xl mx-auto">
                    <!-- Mobile logo -->
                    <div class="lg:hidden mb-8 text-center">
                        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 group">
                            <div class="w-12 h-12 bg-gradient-to-br from-green-600 to-green-800 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-lg">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <span class="text-2xl font-extrabold">
                                <span class="text-green-600">Moz</span><span class="text-gray-800">Commodities</span>
                            </span>
                        </a>
                    </div>

                    <!-- Form card -->
                    <div class="bg-white rounded-2xl shadow-xl p-8 md:p-10">
                        {{ $slot }}
                    </div>

                    <!-- Back to home link -->
                    <div class="mt-6 text-center">
                        <a href="{{ route('home') }}" class="text-sm text-gray-600 hover:text-green-600 transition-colors inline-flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Voltar à página inicial
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <x-whatsapp-button />
    </body>
</html>
