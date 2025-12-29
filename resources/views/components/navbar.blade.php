@props(['transparent' => false])

<nav x-data="{ mobileMenuOpen: false, registerDropdown: false }"
     class="{{ $transparent ? 'absolute top-0 left-0 right-0 z-50 bg-transparent' : 'bg-white shadow-sm sticky top-0 z-50' }}">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 justify-between">
            <div class="flex">
                <!-- Logo -->
                <div class="flex flex-shrink-0 items-center">
                    <a href="{{ route('home') }}">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg {{ $transparent ? 'bg-white/20 backdrop-blur-sm' : 'bg-gradient-to-br from-green-600 to-green-700' }}">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                    </a>
                </div>

                <!-- Desktop Navigation Links -->
                <div class="hidden sm:ml-8 sm:flex sm:space-x-1">
                    <a href="{{ route('home') }}"
                       class="{{ request()->routeIs('home') ? ($transparent ? 'bg-white/20 text-white' : 'bg-green-50 text-green-700') : ($transparent ? 'text-white/90 hover:bg-white/10 hover:text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900') }} inline-flex items-center rounded-md px-3 py-2 text-sm font-medium transition-colors">
                        Início
                    </a>
                    <a href="{{ route('our.products') }}"
                       class="{{ request()->routeIs('our.products') ? ($transparent ? 'bg-white/20 text-white' : 'bg-green-50 text-green-700') : ($transparent ? 'text-white/90 hover:bg-white/10 hover:text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900') }} inline-flex items-center rounded-md px-3 py-2 text-sm font-medium transition-colors">
                        Nossos Produtos
                    </a>
                    <a href="{{ route('products') }}"
                       class="{{ request()->routeIs('products') ? ($transparent ? 'bg-white/20 text-white' : 'bg-green-50 text-green-700') : ($transparent ? 'text-white/90 hover:bg-white/10 hover:text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900') }} inline-flex items-center rounded-md px-3 py-2 text-sm font-medium transition-colors">
                        Produtos
                    </a>
                    <a href="{{ route('quote.form') }}"
                       class="{{ request()->routeIs('quote.form') ? ($transparent ? 'bg-white/20 text-white' : 'bg-green-50 text-green-700') : ($transparent ? 'text-white/90 hover:bg-white/10 hover:text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900') }} inline-flex items-center rounded-md px-3 py-2 text-sm font-medium transition-colors">
                        Pedir Cotação
                    </a>
                </div>
            </div>

            <!-- Right side -->
            <div class="hidden sm:ml-6 sm:flex sm:items-center sm:space-x-4">
                @auth
                    <span class="text-sm font-medium {{ $transparent ? 'text-white' : 'text-gray-700' }}">
                        {{ auth()->user()->name }}
                    </span>

                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}"
                           class="rounded-md bg-purple-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-purple-500 transition-colors">
                            Admin
                        </a>
                    @elseif(auth()->user()->isSupplier())
                        <a href="{{ route('supplier.dashboard') }}"
                           class="rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 transition-colors">
                            Dashboard
                        </a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="{{ $transparent ? 'text-white/90 hover:text-white' : 'text-gray-600 hover:text-gray-900' }} text-sm font-medium transition-colors">
                            Sair
                        </button>
                    </form>
                @else
                    <!-- Cart -->
                    @php $cartCount = \App\Models\CartItem::getCartCount(); @endphp
                    <a href="{{ route('cart') }}" class="relative rounded-md p-2 {{ $transparent ? 'text-white/90 hover:bg-white/10 hover:text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition-colors">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        @if($cartCount > 0)
                            <span class="absolute -right-1 -top-1 flex h-5 w-5 items-center justify-center rounded-full bg-green-600 text-xs font-bold text-white">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>

                    <!-- Login -->
                    <a href="{{ route('login') }}" class="{{ $transparent ? 'text-white/90 hover:text-white' : 'text-gray-600 hover:text-gray-900' }} text-sm font-medium transition-colors">
                        Entrar
                    </a>

                    <!-- Register Dropdown -->
                    <div class="relative" @click.away="registerDropdown = false">
                        <button @click="registerDropdown = !registerDropdown"
                                type="button"
                                class="inline-flex items-center gap-x-1.5 rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600 transition-colors">
                            Registar
                            <svg class="-mr-1 h-5 w-5 transition-transform" :class="{ 'rotate-180': registerDropdown }" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/>
                            </svg>
                        </button>

                        <div x-show="registerDropdown"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                             style="display: none;">
                            <div class="py-1">
                                <a href="{{ route('register.customer') }}" class="group flex items-center px-4 py-3 text-sm hover:bg-gray-50">
                                    <svg class="mr-3 h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    <div>
                                        <p class="font-medium text-gray-900">Cliente</p>
                                        <p class="text-xs text-gray-500">Para comprar produtos</p>
                                    </div>
                                </a>
                                <a href="{{ route('register.supplier') }}" class="group flex items-center px-4 py-3 text-sm hover:bg-gray-50">
                                    <svg class="mr-3 h-5 w-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    <div>
                                        <p class="font-medium text-gray-900">Fornecedor</p>
                                        <p class="text-xs text-gray-500">Para vender produtos</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                @endauth
            </div>

            <!-- Mobile menu button -->
            <div class="flex items-center sm:hidden">
                <button @click="mobileMenuOpen = !mobileMenuOpen"
                        type="button"
                        class="inline-flex items-center justify-center rounded-md p-2 {{ $transparent ? 'text-white hover:bg-white/10' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }} transition-colors">
                    <svg class="h-6 w-6" :class="{ 'hidden': mobileMenuOpen, 'block': !mobileMenuOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg class="h-6 w-6" :class="{ 'block': mobileMenuOpen, 'hidden': !mobileMenuOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div x-show="mobileMenuOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-1"
         class="sm:hidden {{ $transparent ? 'bg-gray-900/95 backdrop-blur-sm' : 'bg-white border-t border-gray-200' }}"
         style="display: none;">
        <div class="space-y-1 px-4 pb-3 pt-2">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? ($transparent ? 'bg-white/20 text-white' : 'bg-green-50 text-green-700') : ($transparent ? 'text-white/90 hover:bg-white/10' : 'text-gray-600 hover:bg-gray-50') }} block rounded-md px-3 py-2 text-base font-medium">
                Início
            </a>
            <a href="{{ route('our.products') }}" class="{{ request()->routeIs('our.products') ? ($transparent ? 'bg-white/20 text-white' : 'bg-green-50 text-green-700') : ($transparent ? 'text-white/90 hover:bg-white/10' : 'text-gray-600 hover:bg-gray-50') }} block rounded-md px-3 py-2 text-base font-medium">
                Nossos Produtos
            </a>
            <a href="{{ route('products') }}" class="{{ request()->routeIs('products') ? ($transparent ? 'bg-white/20 text-white' : 'bg-green-50 text-green-700') : ($transparent ? 'text-white/90 hover:bg-white/10' : 'text-gray-600 hover:bg-gray-50') }} block rounded-md px-3 py-2 text-base font-medium">
                Produtos
            </a>
            <a href="{{ route('quote.form') }}" class="{{ request()->routeIs('quote.form') ? ($transparent ? 'bg-white/20 text-white' : 'bg-green-50 text-green-700') : ($transparent ? 'text-white/90 hover:bg-white/10' : 'text-gray-600 hover:bg-gray-50') }} block rounded-md px-3 py-2 text-base font-medium">
                Pedir Cotação
            </a>
        </div>

        <div class="border-t {{ $transparent ? 'border-white/20' : 'border-gray-200' }} px-4 pb-3 pt-4">
            @auth
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-green-600 text-white font-semibold">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    </div>
                    <div class="ml-3">
                        <div class="text-base font-medium {{ $transparent ? 'text-white' : 'text-gray-800' }}">{{ auth()->user()->name }}</div>
                        <div class="text-sm {{ $transparent ? 'text-white/70' : 'text-gray-500' }}">{{ auth()->user()->email }}</div>
                    </div>
                </div>
                <div class="mt-3 space-y-1">
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="block rounded-md px-3 py-2 text-base font-medium {{ $transparent ? 'text-white/90 hover:bg-white/10' : 'text-gray-600 hover:bg-gray-50' }}">
                            Painel Admin
                        </a>
                    @elseif(auth()->user()->isSupplier())
                        <a href="{{ route('supplier.dashboard') }}" class="block rounded-md px-3 py-2 text-base font-medium {{ $transparent ? 'text-white/90 hover:bg-white/10' : 'text-gray-600 hover:bg-gray-50' }}">
                            Dashboard
                        </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left rounded-md px-3 py-2 text-base font-medium {{ $transparent ? 'text-white/90 hover:bg-white/10' : 'text-gray-600 hover:bg-gray-50' }}">
                            Sair
                        </button>
                    </form>
                </div>
            @else
                <div class="space-y-2">
                    <a href="{{ route('cart') }}" class="flex items-center rounded-md px-3 py-2 text-base font-medium {{ $transparent ? 'text-white/90 hover:bg-white/10' : 'text-gray-600 hover:bg-gray-50' }}">
                        <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        Carrinho
                        @php $cartCount = \App\Models\CartItem::getCartCount(); @endphp
                        @if($cartCount > 0)
                            <span class="ml-auto rounded-full bg-green-600 px-2 py-0.5 text-xs font-semibold text-white">{{ $cartCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('login') }}" class="block rounded-md px-3 py-2 text-base font-medium {{ $transparent ? 'text-white/90 hover:bg-white/10' : 'text-gray-600 hover:bg-gray-50' }}">
                        Entrar
                    </a>
                    <a href="{{ route('register.customer') }}" class="block rounded-md bg-gray-100 px-3 py-2 text-base font-medium text-gray-900 hover:bg-gray-200">
                        Registar como Cliente
                    </a>
                    <a href="{{ route('register.supplier') }}" class="block rounded-md bg-green-600 px-3 py-2 text-base font-medium text-white hover:bg-green-500">
                        Registar como Fornecedor
                    </a>
                </div>
            @endauth
        </div>
    </div>
</nav>
