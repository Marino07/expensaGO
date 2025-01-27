<div class="min-h-screen bg-gradient-to-br from-blue-100 via-purple-100 to-pink-100 font-sans"
    x-data="{
        activeTab: 'places',
        showScrollTop: false,
        savedPlaces: [
            {
                id: 1,
                name: 'Eiffel Tower',
                location: 'Paris, France',
                image: 'https://images.unsplash.com/photo-1543349689-9a4d426bee8e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1001&q=80',
                category: 'Landmark'
            },
            {
                id: 2,
                name: 'Colosseum',
                location: 'Rome, Italy',
                image: 'https://images.unsplash.com/photo-1552832230-c0197dd311b5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1096&q=80',
                category: 'Historical Site'
            },
            {
                id: 3,
                name: 'Machu Picchu',
                location: 'Cusco Region, Peru',
                image: 'https://images.unsplash.com/photo-1526392060635-9d6019884377?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80',
                category: 'Archaeological Site'
            },
            {
                id: 4,
                name: 'Great Wall of China',
                location: 'China',
                image: 'https://images.unsplash.com/photo-1508804185872-d7badad00f7d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80',
                category: 'Wonder of the World'
            }
        ],
        savedEvents: [
            {
                id: 1,
                name: 'Oktoberfest',
                location: 'Munich, Germany',
                date: '2023-09-16',
                image: 'https://images.unsplash.com/photo-1505489304219-85ce17010209?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1171&q=80'
            },
            {
                id: 2,
                name: 'Carnival',
                location: 'Rio de Janeiro, Brazil',
                date: '2024-02-09',
                image: 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1074&q=80'
            },
            {
                id: 3,
                name: 'Holi Festival',
                location: 'India',
                date: '2024-03-25',
                image: 'https://images.unsplash.com/photo-1610630694586-2af2f4cbed63?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80'
            },
            {
                id: 4,
                name: 'Glastonbury Festival',
                location: 'Somerset, England',
                date: '2023-06-21',
                image: 'https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80'
            }
        ],
        init() {
            window.addEventListener('scroll', () => {
                this.showScrollTop = window.pageYOffset > 300;
            })
        }
    }"
    x-init="init()"
>
    <x-barapp />

    <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0 opacity-10">
        <div class="absolute top-0 left-0 w-full h-full bg-repeat" style="background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI2MCIgaGVpZ2h0PSI2MCI+CjxyZWN0IHdpZHRoPSI2MCIgaGVpZ2h0PSI2MCIgZmlsbD0iI2ZmZiIvPgo8cGF0aCBkPSJNMzYgMzZjLTUuNSAwLTEwLTQuNS0xMC0xMHM0LjUtMTAgMTAtMTAgMTAgNC41IDEwIDEwLTQuNSAxMC0xMCAxMHptMC0xN2MtMy45IDAtNyAzLjEtNyA3czMuMSA3IDcgNyA3LTMuMSA3LTctMy4xLTctNy03eiIgZmlsbD0iIzAwMCIgZmlsbC1vcGFjaXR5PSIuMDUiLz4KPC9zdmc+');">
        </div>
    </div>

    <main class="container mx-auto px-4 py-4 relative z-10">
        <div class="flex flex-col items-center mb-6">
            <div class="flex items-center justify-center mb-2">
                <svg class="w-14 h-14 text-red-500 animate-pulse" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <h1 class="text-4xl md:text-5xl font-extrabold text-center text-gray-800 bg-clip-text text-transparent bg-gradient-to-r from-purple-500 to-pink-500 mb-3">Your Global Adventures</h1>
            <p class="text-lg text-gray-600 text-center max-w-2xl">Explore your favorite places and upcoming events from around the world.</p>
        </div>

        <!-- Improved Tab switcher -->
        <div class="flex justify-center mb-8">
            <div class="bg-white p-1 rounded-full shadow-lg">
                <button
                    @click="activeTab = 'places'"
                    :class="{ 'bg-green-500 text-white': activeTab === 'places', 'text-gray-700 hover:bg-green-100': activeTab !== 'places' }"
                    class="px-6 py-3 rounded-full font-semibold transition duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50"
                >
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Saved Places
                    </span>
                </button>
                <button
                    @click="activeTab = 'events'"
                    :class="{ 'bg-red-500 text-white': activeTab === 'events', 'text-gray-700 hover:bg-red-100': activeTab !== 'events' }"
                    class="px-6 py-3 rounded-full font-semibold transition duration-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50"
                >
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Saved Events
                    </span>
                </button>
            </div>
        </div>

        <!-- Saved Places -->
        <div x-show="activeTab === 'places'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <template x-for="place in savedPlaces" :key="place.id">
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden transform transition duration-500 hover:scale-105 hover:shadow-xl">
                        <div class="relative h-64">
                            <img :src="place.image" :alt="place.name" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity duration-300">
                                <button class="bg-white text-gray-800 px-4 py-2 rounded-full font-semibold hover:bg-gray-100 transition duration-300">View Details</button>
                            </div>
                        </div>
                        <div class="p-6">
                            <h2 class="text-2xl font-bold mb-2 text-gray-800" x-text="place.name"></h2>
                            <p class="text-gray-600 mb-4" x-text="place.location"></p>
                            <span class="inline-block bg-green-100 text-green-800 rounded-full px-3 py-1 text-sm font-semibold" x-text="place.category"></span>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Saved Events -->
        <div x-show="activeTab === 'events'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <template x-for="event in savedEvents" :key="event.id">
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden transform transition duration-500 hover:scale-105 hover:shadow-xl">
                        <div class="relative h-64">
                            <img :src="event.image" :alt="event.name" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity duration-300">
                                <button class="bg-white text-gray-800 px-4 py-2 rounded-full font-semibold hover:bg-gray-100 transition duration-300">View Details</button>
                            </div>
                        </div>
                        <div class="p-6">
                            <h2 class="text-2xl font-bold mb-2 text-gray-800" x-text="event.name"></h2>
                            <p class="text-gray-600 mb-2" x-text="event.location"></p>
                            <p class="text-red-600 font-semibold" x-text="new Date(event.date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })"></p>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </main>
    <x-footer />
    <x-AIchat />
</div>

