<div x-data="{
    showFilters: false,
    activeTab: 'list',
    mapLoaded: false,
    initMap() {
        if (!this.mapLoaded) {
            // Initialize map here (using a library like Mapbox or Google Maps)
            this.mapLoaded = true;
        }
    }
}" class="min-h-screen bg-gray-100">
    <x-barapp />

    <!-- Hero Section -->
    <div class="bg-gradient-to-l from-blue-300 to-cyan-500 text-white py-16">
        <div class="container mx-auto px-4">
            <h1 class="text-4xl font-bold mb-4">Discover {{ ucfirst($placeType) }}s in {{ $search }}</h1>
            <p class="text-xl mb-8">Find the best local spots, read reviews, and plan your next adventure.</p>

            <!-- Search Bar -->
            <div class="flex flex-col md:flex-row gap-4 items-center bg-white rounded-lg p-2 shadow-lg">
                <div class="flex-grow">
                    <input type="text" placeholder="Search for {{ $placeType }}s..." class="w-full p-3 text-gray-700 focus:outline-none" wire:model.debounce.300ms="search">
                </div>
                <button @click="getLocation()" class="bg-gradient-to-r from-blue-500 to-cyan-500 text-white px-6 py-3 rounded-md hover:bg-blue-600 transition duration-300 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                    </svg>
                    Use My Location
                </button>
                <button class="bg-gray-200 text-gray-700 px-6 py-3 rounded-md hover:bg-gray-300 transition duration-300" @click="showFilters = !showFilters">
                    Filters
                </button>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div x-show="showFilters" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-2" class="bg-white shadow-md p-4">
        <div class="container mx-auto flex flex-wrap gap-4 items-center">
            <div>
                <label for="placeType" class="block text-sm font-medium text-gray-700 mb-1">Place Type:</label>
                <select id="placeType" name="placeType" wire:model="placeType" wire:change="searchPlaces" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                    <option value="restaurant">Restaurant</option>
                    <option value="store">Store</option>
                    <option value="cafe">Cafe</option>
                    <option value="bar">Bar</option>
                </select>
            </div>
            <div>
                <label for="sort" class="block text-sm font-medium text-gray-700 mb-1">Sort by:</label>
                <select id="sort" name="sort" wire:model="sortCriteria" wire:change="sortPlaces" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                    <option value="rating">Rating</option>
                    <option value="price">Price</option>
                </select>
            </div>
            <!-- Add more filters here as needed -->
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        <!-- Tabs -->
        <div class="flex mb-6 bg-white rounded-lg shadow-md">
            <button @click="activeTab = 'list'" :class="{ 'bg-cyan-500 text-white': activeTab === 'list', 'text-gray-700': activeTab !== 'list' }" class="flex-1 py-3 px-4 rounded-l-lg focus:outline-none transition duration-300">
                List View
            </button>
            <button @click="activeTab = 'map'; initMap()" :class="{ 'bg-cyan-500 text-white': activeTab === 'map', 'text-gray-700': activeTab !== 'map' }" class="flex-1 py-3 px-4 rounded-r-lg focus:outline-none transition duration-300">
                Map View
            </button>
        </div>

        <!-- Loading State -->
        <div wire:loading class="w-full text-center py-8">
            <div class="inline-block animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
            <p class="mt-4 text-gray-600 text-lg">Discovering amazing places...</p>
        </div>

        <!-- Results -->
        <div x-show="activeTab === 'list'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($places as $place)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        @php
                            $defaultImage = match($placeType) {
                                'restaurant' => 'images/defaults/restaurant-image.jpg',
                                'store' => 'images/defaults/local_shop.png',
                                'cafe' => 'images/defaults/coffe_shop.jpeg',
                                'bar' => 'images/defaults/bar.ong.png',
                                default => 'images/defaults/place.png',
                            };
                        @endphp
                        <img src="{{ $place['photo'] ?? asset($defaultImage) }}" alt="{{ $place['name'] }}" class="w-full h-48 object-full">
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $place['name'] }}</h3>
                            <div class="flex items-center mb-2">
                                <div class="flex text-yellow-400">
                                    @for($i = 0; $i < 5; $i++)
                                        @if($i < floor($place['rating'] ?? 0))
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                            </svg>
                                        @endif
                                    @endfor
                                </div>
                                <span class="ml-2 text-sm text-gray-600">
                                    {{ $place['rating'] ?? 'No rating' }}
                                    ({{ $place['user_ratings_total'] ?? 0 }} reviews)
                                </span>
                            </div>
                            <p class="text-gray-600 text-sm mb-4">{{ $place['vicinity'] ?? 'No address available' }}</p>
                            @if(isset($place['opening_hours']['open_now']))
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $place['opening_hours']['open_now'] ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $place['opening_hours']['open_now'] ? 'Open Now' : 'Closed' }}
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Map View -->
        <div x-show="activeTab === 'map'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div id="map" class="w-full h-[600px] rounded-lg shadow-md">
                <!-- Map will be initialized here -->
            </div>
        </div>
    </div>

    <x-footer />

    <script>
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition, showError);
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }

        function showPosition(position) {
            @this.setUserLocation(position.coords.latitude, position.coords.longitude);
        }

        function showError(error) {
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    alert("User denied the request for Geolocation.");
                    break;
                case error.POSITION_UNAVAILABLE:
                    alert("Location information is unavailable.");
                    break;
                case error.TIMEOUT:
                    alert("The request to get user location timed out.");
                    break;
                case error.UNKNOWN_ERROR:
                    alert("An unknown error occurred.");
                    break;
            }
        }

        // Add any additional JavaScript for map initialization here
    </script>
</div>

