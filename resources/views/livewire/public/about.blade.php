<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-br from-green-700 via-green-600 to-emerald-600 text-white overflow-hidden">
        <!-- Pattern overlay -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute transform rotate-45 -left-48 -top-48 w-96 h-96 bg-white rounded-full blur-3xl"></div>
            <div class="absolute transform -rotate-45 -right-48 -bottom-48 w-96 h-96 bg-white rounded-full blur-3xl"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">
            <div class="text-center max-w-4xl mx-auto">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold mb-6">
                    {{ $heroTitle }}
                </h1>
                <p class="text-xl md:text-2xl text-green-100 leading-relaxed">
                    {{ $heroSubtitle }}
                </p>
            </div>
        </div>

        <!-- Wave -->
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full">
                <path d="M0 120L60 110C120 100 240 80 360 70C480 60 600 60 720 65C840 70 960 80 1080 85C1200 90 1320 90 1380 90L1440 90V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="#F9FAFB"/>
            </svg>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-10">
        <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-8">
                <div class="text-center">
                    <div class="text-3xl md:text-4xl font-extrabold text-green-600">{{ $statsProducts }}</div>
                    <div class="text-gray-600 font-medium mt-1">Produtos</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl md:text-4xl font-extrabold text-green-600">{{ $statsSuppliers }}</div>
                    <div class="text-gray-600 font-medium mt-1">Fornecedores</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl md:text-4xl font-extrabold text-green-600">{{ $statsClients }}</div>
                    <div class="text-gray-600 font-medium mt-1">Clientes</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl md:text-4xl font-extrabold text-green-600">{{ $statsYears }}</div>
                    <div class="text-gray-600 font-medium mt-1">Anos</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Introduction -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="max-w-4xl mx-auto text-center">
            <p class="text-xl text-gray-700 leading-relaxed">
                {{ $introText }}
            </p>
        </div>
    </div>

    <!-- Mission & Vision -->
    <div class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-8 lg:gap-12">
                <!-- Mission -->
                <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-8 border border-green-100">
                    <div class="w-14 h-14 bg-green-600 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ $missionTitle }}</h2>
                    <p class="text-gray-700 leading-relaxed">{{ $missionText }}</p>
                </div>

                <!-- Vision -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-8 border border-blue-100">
                    <div class="w-14 h-14 bg-blue-600 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ $visionTitle }}</h2>
                    <p class="text-gray-700 leading-relaxed">{{ $visionText }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Values -->
    <div class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900">{{ $valuesTitle }}</h2>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Value 1 -->
                <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow border border-gray-100">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $value1Title }}</h3>
                    <p class="text-gray-600">{{ $value1Text }}</p>
                </div>

                <!-- Value 2 -->
                <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow border border-gray-100">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $value2Title }}</h3>
                    <p class="text-gray-600">{{ $value2Text }}</p>
                </div>

                <!-- Value 3 -->
                <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow border border-gray-100">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $value3Title }}</h3>
                    <p class="text-gray-600">{{ $value3Text }}</p>
                </div>

                <!-- Value 4 -->
                <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow border border-gray-100">
                    <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $value4Title }}</h3>
                    <p class="text-gray-600">{{ $value4Text }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Team -->
    <div class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">{{ $teamTitle }}</h2>
                <p class="text-xl text-gray-700 leading-relaxed">{{ $teamText }}</p>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-gradient-to-r from-green-600 to-emerald-600 py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold text-white mb-4">Pronto para Começar?</h2>
            <p class="text-xl text-green-100 mb-8">Junte-se a milhares de clientes e fornecedores que confiam na MozCommodities</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('products') }}" class="inline-flex items-center justify-center px-8 py-4 bg-white text-green-700 rounded-xl font-bold hover:bg-green-50 transition shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    Ver Produtos
                </a>
                <a href="{{ route('register.supplier') }}" class="inline-flex items-center justify-center px-8 py-4 bg-green-800 text-white rounded-xl font-bold hover:bg-green-900 transition border-2 border-green-500">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    Tornar-se Fornecedor
                </a>
            </div>
        </div>
    </div>
</div>
