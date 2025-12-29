<nav class="bg-white shadow-md sticky top-0 z-50" x-data="{ mobileMenuOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-2 text-green-700 font-bold text-xl">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <span>Moz<span class="text-green-600">Commodities</span></span>
            </a>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center gap-6">
                <a href="{{ route('home') }}" class="text-gray-700 hover:text-green-600 transition-colors">Início</a>
                <a href="{{ route('products') }}" class="text-gray-700 hover:text-green-600 transition-colors">Produtos</a>
                <a href="{{ route('quote.form') }}" class="text-gray-700 hover:text-green-600 transition-colors">Cotação</a>

                @guest
                    <div class="flex items-center gap-3 ml-4 pl-4 border-l border-gray-200">
                        <a href="{{ route('register.customer') }}"
                           class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all">
                            Registar como Cliente
                        </a>
                        <a href="{{ route('register.supplier') }}"
                           class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all font-semibold">
                            Registar como Fornecedor
                        </a>
                        <a href="{{ route('login') }}"
                           class="px-4 py-2 text-gray-700 hover:text-green-600 transition-colors">
                            Entrar
                        </a>
                    </div>
                @else
                    <a href="{{ route('dashboard') }}"
                       class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all font-semibold">
                        Dashboard
                    </a>
                @endguest
            </div>

            <!-- Mobile Menu Button -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen"
             x-transition
             @click.away="mobileMenuOpen = false"
             class="md:hidden pb-4 border-t border-gray-200 mt-2 pt-2">
            <div class="space-y-2">
                <a href="{{ route('home') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">Início</a>
                <a href="{{ route('products') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">Produtos</a>
                <a href="{{ route('quote.form') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">Cotação</a>

                @guest
                    <div class="pt-2 mt-2 border-t border-gray-200 space-y-2">
                        <a href="{{ route('register.customer') }}" class="block px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-center">
                            Registar como Cliente
                        </a>
                        <a href="{{ route('register.supplier') }}" class="block px-4 py-2 bg-green-600 text-white rounded-lg font-semibold text-center">
                            Registar como Fornecedor
                        </a>
                        <a href="{{ route('login') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg text-center">
                            Entrar
                        </a>
                    </div>
                @else
                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 bg-green-600 text-white rounded-lg font-semibold text-center mt-2">
                        Dashboard
                    </a>
                @endguest
            </div>
        </div>
    </div>
</nav>
