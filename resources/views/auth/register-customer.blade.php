<x-guest-layout>
    <x-slot name="header">
        <!-- Leaflet CSS e JS carregados no head -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
            integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
            crossorigin=""/>
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
            crossorigin=""></script>
    </x-slot>

    <div class="mb-8">
        <h2 class="text-3xl font-extrabold text-gray-900">Criar conta de Cliente</h2>
        <p class="mt-2 text-sm text-gray-600">Junte-se à nossa plataforma e comece a comprar produtos agrícolas</p>
    </div>

    <form method="POST" action="{{ route('register.customer') }}" class="space-y-4">
        @csrf

        <!-- Row 1: Name & Email -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Nome completo</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                    class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-gray-900 placeholder-gray-400"
                    placeholder="João Silva">
                <x-input-error :messages="$errors->get('name')" class="mt-1" />
            </div>

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                    class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-gray-900 placeholder-gray-400"
                    placeholder="joao@exemplo.com">
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>
        </div>

        <!-- Row 2: Phone & WhatsApp -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Phone Number -->
            <div>
                <label for="phone" class="block text-sm font-semibold text-gray-700 mb-1">Celular</label>
                <input id="phone" type="text" name="phone" value="{{ old('phone') }}" required
                    class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-gray-900 placeholder-gray-400"
                    placeholder="+258 84 000 0000">
                <x-input-error :messages="$errors->get('phone')" class="mt-1" />
            </div>

            <!-- WhatsApp (Optional) -->
            <div>
                <label for="whatsapp" class="block text-sm font-semibold text-gray-700 mb-1">WhatsApp <span class="text-gray-400 font-normal text-xs">(opcional)</span></label>
                <input id="whatsapp" type="text" name="whatsapp" value="{{ old('whatsapp') }}"
                    class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-gray-900 placeholder-gray-400"
                    placeholder="+258 84 000 0000">
                <x-input-error :messages="$errors->get('whatsapp')" class="mt-1" />
            </div>
        </div>

        <!-- Address with Map -->
        <div x-data="{
            map: null,
            marker: null,
            latitude: '',
            longitude: '',
            address: '',
            async initMap() {
                // Aguardar Leaflet carregar
                if (typeof L === 'undefined') {
                    setTimeout(() => this.initMap(), 100);
                    return;
                }

                const maputoCoords = [-25.9655, 32.5832];
                this.map = L.map(this.$refs.map).setView(maputoCoords, 12);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors',
                    maxZoom: 19
                }).addTo(this.map);

                this.map.on('click', (e) => {
                    this.setLocation(e.latlng.lat, e.latlng.lng);
                });

                this.$refs.searchInput.addEventListener('keypress', async (e) => {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        const query = e.target.value;
                        await this.searchAddress(query);
                    }
                });

                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition((position) => {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        this.map.setView([lat, lng], 15);
                        this.setLocation(lat, lng);
                    });
                }
            },
            async setLocation(lat, lng) {
                this.latitude = lat.toFixed(6);
                this.longitude = lng.toFixed(6);

                if (this.marker) {
                    this.map.removeLayer(this.marker);
                }

                this.marker = L.marker([lat, lng]).addTo(this.map);

                try {
                    const response = await fetch(
                        `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`
                    );
                    const data = await response.json();
                    this.address = data.display_name || '';
                } catch (error) {
                    console.error('Erro ao obter endereço:', error);
                    this.address = `Lat: ${lat.toFixed(6)}, Lng: ${lng.toFixed(6)}`;
                }
            },
            async searchAddress(query) {
                try {
                    const response = await fetch(
                        `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query + ', Mozambique')}&limit=1`
                    );
                    const data = await response.json();

                    if (data.length > 0) {
                        const lat = parseFloat(data[0].lat);
                        const lng = parseFloat(data[0].lon);
                        this.map.setView([lat, lng], 15);
                        this.setLocation(lat, lng);
                    } else {
                        alert('Endereço não encontrado. Tente novamente ou clique no mapa.');
                    }
                } catch (error) {
                    console.error('Erro na pesquisa:', error);
                    alert('Erro ao pesquisar endereço.');
                }
            }
        }" x-init="initMap()">
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Seu endereço de entrega
                <span class="text-gray-400 font-normal">(clique no mapa ou pesquise)</span>
            </label>

            <!-- Search Box -->
            <div class="relative mb-3">
                <input type="text" x-ref="searchInput" placeholder="Pesquisar endereço..."
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
            </div>

            <!-- Map -->
            <div x-ref="map" class="w-full rounded-lg border-2 border-gray-300 mb-3" style="height: 200px;"></div>

            <!-- Address Text -->
            <textarea name="address" x-model="address" required rows="2"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                placeholder="Endereço será preenchido automaticamente"></textarea>
            <x-input-error :messages="$errors->get('address')" class="mt-2" />

            <!-- Hidden fields for coordinates -->
            <input type="hidden" name="latitude" x-model="latitude">
            <input type="hidden" name="longitude" x-model="longitude">

            <p class="text-xs text-gray-600 mt-2">
                📍 Coordenadas: <span x-text="latitude ? `${latitude}, ${longitude}` : 'Clique no mapa para selecionar'"></span>
            </p>
        </div>

        <!-- Row: Passwords -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Palavra-passe</label>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                    class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-gray-900 placeholder-gray-400"
                    placeholder="Mínimo 8 caracteres">
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1">Confirmar</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                    class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-gray-900 placeholder-gray-400"
                    placeholder="Repita a palavra-passe">
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
            </div>
        </div>

        <!-- Info Notice -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
            <p class="text-xs text-blue-800">
                <strong>Nota:</strong> Como cliente, pode comprar produtos agrícolas de qualidade diretamente da plataforma.
            </p>
        </div>

        <!-- Terms notice -->
        <div class="bg-green-50 border border-green-200 rounded-lg p-3">
            <p class="text-xs text-green-800">
                Ao criar uma conta, concorda com nossos <a href="#" class="font-semibold underline">Termos de Serviço</a> e <a href="#" class="font-semibold underline">Política de Privacidade</a>.
            </p>
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit" class="w-full flex justify-center items-center gap-2 py-3 px-4 border border-transparent rounded-lg shadow-lg text-sm font-bold text-white bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 transform hover:scale-105">
                <span>Criar conta de Cliente</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                </svg>
            </button>
        </div>

        <!-- Alternative Registration -->
        <div class="text-center pt-4 border-t border-gray-200">
            <p class="text-sm text-gray-600 mb-2">
                Quer vender produtos?
                <a href="{{ route('register.supplier') }}" class="font-semibold text-green-600 hover:text-green-700 transition-colors">
                    Registar como Fornecedor
                </a>
            </p>
            <p class="text-sm text-gray-600">
                Já tem uma conta?
                <a href="{{ route('login') }}" class="font-semibold text-green-600 hover:text-green-700 transition-colors">
                    Entrar agora
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
