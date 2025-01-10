<div x-data="{
    showFilters: false,
    activeTab: 'list',
    mapLoaded: false,
    markers: [],
    showTutorial: {{$tutorialState}},
    showDirectionsModal: false,
    selectedDestination: null,
    directionsService: null,
    directionsRenderer: null,
    travelTime: null,
    selectedTravelMode: 'DRIVING',
    initMap() {
        this.mapLoaded = false;
        this.markers = [];
        const map = new google.maps.Map(document.getElementById('map'), {
            center: { lat: parseFloat('{{ explode(',', $geo_lat_lng)[0] }}'), lng: parseFloat('{{ explode(',', $geo_lat_lng)[1] }}') },
            zoom: 14
        });

        // Initialize directions service and renderer
        this.directionsService = new google.maps.DirectionsService();
        if (!this.directionsRenderer) {
            this.directionsRenderer = new google.maps.DirectionsRenderer({
                suppressMarkers: false
            });
        }
        // Set map for directionsRenderer
        this.directionsRenderer.setMap(map);

        // Create bounds object
        const bounds = new google.maps.LatLngBounds();

        // Add markers for each place and extend bounds
        @this.places.forEach(place => {
            const position = {
                lat: place.geometry.location.lat,
                lng: place.geometry.location.lng
            };
            const marker = new google.maps.Marker({
                position: position,
                map: map,
                title: place.name,
            });
            this.markers.push({
                id: place.place_id,
                marker: marker
            });
            bounds.extend(position);
        });

        // Store map reference
        this.map = map;

        // Fit map to all markers and center
        if (@this.places.length > 0) {
            map.fitBounds(bounds);
            // Prevent too much zoom for single marker
            const listener = google.maps.event.addListener(map, 'idle', function() {
                if (map.getZoom() > 16) map.setZoom(16);
                google.maps.event.removeListener(listener);
            });
        }

        this.mapLoaded = true;
    },
    focusMarker(placeId) {
        this.activeTab = 'map';
        if (!this.mapLoaded) {
            this.$nextTick(() => {
                this.initMap();
                this.$nextTick(() => this.zoomToMarker(placeId));
            });
        } else {
            this.zoomToMarker(placeId);
        }
    },
    zoomToMarker(placeId) {
        const markerData = this.markers.find(m => m.id === placeId);
        if (markerData) {
            // First pan to the marker
            this.map.panTo(markerData.marker.getPosition());

            // Then smoothly zoom in
            setTimeout(() => {
                this.map.setZoom(17);
            }, 300);

            // Bounce animation
            markerData.marker.setAnimation(google.maps.Animation.BOUNCE);
            setTimeout(() => {
                markerData.marker.setAnimation(null);
            }, 2100);
        }
    },
    changeTutorial() {
        this.showTutorial = !this.showTutorial;
        $wire.call('changeTutorial');
    },
    showDirections(placeId) {
        const place = @this.places.find(p => p.place_id === placeId);
        if (place) {
            this.selectedDestination = {
                lat: place.geometry.location.lat,
                lng: place.geometry.location.lng,
                name: place.name
            };
            this.showDirectionsModal = true;
        }
    },
    calculateRoute(startType) {
        if (!this.selectedDestination) return;

        const destination = {
            lat: this.selectedDestination.lat,
            lng: this.selectedDestination.lng
        };

        if (startType === 'current') {
            // Use current location
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition((position) => {
                    const start = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };
                    this.getDirections(start, destination);
                });
            }
        } else {
            // Use address input
            const address = document.getElementById('startAddress').value;
            const geocoder = new google.maps.Geocoder();
            geocoder.geocode({ address: address }, (results, status) => {
                if (status === 'OK') {
                    const start = results[0].geometry.location;
                    this.getDirections(start, destination);
                }
            });
        }
    },
    getDirections(start, destination) {
        if (!this.directionsService || !this.directionsRenderer) {
            this.directionsService = new google.maps.DirectionsService();
            this.directionsRenderer = new google.maps.DirectionsRenderer({
                suppressMarkers: false
            });
        }

        this.directionsRenderer.setMap(this.map);

        this.directionsService.route({
            origin: start,
            destination: destination,
            travelMode: google.maps.TravelMode[this.selectedTravelMode]
        }, (response, status) => {
            if (status === 'OK') {
                this.activeTab = 'map';
                // Clear existing markers when showing directions
                this.markers.forEach(marker => marker.marker.setMap(null));
                this.directionsRenderer.setDirections(response);

                // Extract and store travel time
                const route = response.routes[0];
                if (route && route.legs && route.legs[0]) {
                    this.travelTime = route.legs[0].duration.text;
                }

                this.showDirectionsModal = false;
            } else {
                alert('Could not calculate directions: ' + status);
            }
        });
    }
}"
class="min-h-screen bg-gray-100 relative"
x-init="
    // Wait for Google Maps to load
    window.initMap = function() {
        // Initial map setup
        if ($el.querySelector('#map')) {
            initMap();
        }
    };

    // Load Google Maps script if not already loaded
    if (!window.google) {
        const script = document.createElement('script');
        script.src = 'https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_PLACES_API_KEY') }}&libraries=places,geocoding';
        script.defer = true;
        document.head.appendChild(script);
    }

    $watch('activeTab', value => {
        if (value === 'map') {
            if (window.google) {
                initMap();
            } else {
                window.initMap = initMap;
            }
        }
    });
"
@places-updated.window="if (activeTab === 'map' && window.google) initMap()"
@reset-to-list-view.window="activeTab = 'list'"
@search-updated.window="activeTab = 'list'"
>
    <x-barapp />

    <!-- Hero Section -->
    <div class="bg-gradient-to-l from-blue-300 to-cyan-500 text-white py-16">
        <div class="container mx-auto px-4">
            <h1 class="text-4xl font-bold mb-4">Discover {{ ucfirst($placeType) }}s in {{ $search }}</h1>
            <p class="text-xl mb-8">Find the best local spots, read reviews, and plan your next adventure.</p>

            <!-- Search Bar and Filters -->
            <div class="flex flex-col md:flex-row items-center space-y-4 md:space-y-0">
                <!-- Search and Location -->
                <div class="flex-grow flex flex-col md:flex-row gap-4 items-center bg-white rounded-lg p-2 shadow-lg">
                    <div class="flex-grow">
                        <input type="text"
                               placeholder="Search for {{ $placeType }}s..."
                               class="w-full p-3 text-gray-700 focus:outline-none"
                               wire:model.live.debounce.500ms="search"
                               @input="$dispatch('search-updated')"
                               wire:keydown.enter="searchPlaces">
                    </div>
                    <button @click="getLocation()" class="bg-gradient-to-r from-blue-500 to-cyan-500 text-white px-6 py-3 rounded-md hover:bg-blue-600 transition duration-300 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                        </svg>
                        Use My Location
                    </button>
                    <button @click="showFilters = true"  class="bg-gradient-to-r from-pink-500 to-rose-500 text-white px-4 py-3 rounded-md hover:from-pink-600 hover:to-rose-600 transition duration-300 flex items-center justify-center" :class="{ 'no-blur': showTutorial }">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Side Panel -->
    <div x-cloak
         x-show="showFilters"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-x-full"
         x-transition:enter-end="opacity-100 transform translate-x-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 transform translate-x-0"
         x-transition:leave-end="opacity-0 transform translate-x-full"
         class="fixed right-0 top-0 h-full w-80 bg-white shadow-2xl z-50">
        <!-- Filter Panel Header -->
        <div class="p-4 bg-gradient-to-r from-blue-500 to-cyan-500 text-white flex justify-between items-center">
            <h3 class="text-lg font-semibold">Filters</h3>
            <button @click="showFilters = false" class="p-2 hover:bg-white/10 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Filter Content -->
        <div class="p-6 space-y-6">
            <!-- Place Type Filter -->
            <div class="space-y-2">
                <label for="placeType" class="block text-sm font-medium text-gray-700">Place Type</label>
                <select id="placeType"
                        wire:model="placeType"
                        wire:change="searchPlaces"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500">
                    <option value="restaurant">Restaurant</option>
                    <option value="store">Store</option>
                    <option value="cafe">Cafe</option>
                    <option value="bar">Bar</option>
                </select>
            </div>

            <!-- Sort Filter -->
            <div class="space-y-2">
                <label for="sort" class="block text-sm font-medium text-gray-700">Sort by</label>
                <select id="sort"
                        wire:model="sortCriteria"
                        wire:change="sortPlaces"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500">
                    <option value="rating">Rating</option>
                    <option value="price">Price</option>
                </select>
            </div>

            <!-- Additional filters can be added here -->
        </div>

        <!-- Apply Filters Button -->
        <div class="absolute bottom-0 left-0 right-0 p-4 bg-gray-50 border-t">
            <button @click="showFilters = false"
                    class="w-full bg-gradient-to-r from-blue-500 to-cyan-500 text-white px-4 py-2 rounded-md hover:from-blue-600 hover:to-cyan-600 transition-all duration-300">
                Close
            </button>
        </div>
    </div>

    <!-- Backdrop -->
    <div x-show="showFilters"
         @click="showFilters = false"
         x-transition:enter="transition-opacity ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/50 z-40">
    </div>

    <!-- Tutorial Overlay -->
    <div x-cloak x-show="showTutorial" class="fixed inset-0 z-50 hidden md:block">
        <!-- Backdrop with blur excluding filters button -->
        <div class="absolute inset-0 bg-black/20 backdrop-blur-sm"></div>
        <div class="absolute inset-0 z-40 pointer-events-none"></div>

        <!-- Exclude filters button from blur -->
        <style>
            .no-blur {
                position: relative;
                z-index: 60;
                backdrop-filter: none !important;
            }
        </style>

        <!-- Tutorial poruka i strelica -->
        <div class="absolute top-[135px] right-[250px] flex items-start">
            <!-- Tutorial Box -->
            <div class="bg-white rounded-lg shadow-xl p-6 max-w-xs relative z-50">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Quick Tip! 💡</h3>
                <p class="text-gray-600 mb-4">
                    Use our filters to find exactly what you're looking for - sort by rating, price, and more!
                </p>
                <button @click="changeTutorial"
                        class="w-full bg-gradient-to-r from-pink-500 to-rose-500 text-white px-4 py-2 rounded-md hover:from-pink-600 hover:to-rose-600 transition-all duration-300 text-sm font-medium">
                    Got it!
                </button>
            </div>

            <!-- Curved Arrow from Tutorial to Filter Button -->
            <svg class="absolute -right-16 top-8 w-32 h-32 z-50" viewBox="0 0 100 100">
                <!-- Zakrivljena linija -->
                <path
                    d="M0,20 Q40,20 40,50 T80,80"
                    fill="none"
                    stroke="#ec4899"
                    stroke-width="5"
                    stroke-dasharray="4,4"
                    class="animate-pulse"
                />
                <!-- Strelica na kraju -->
                <path
                    d="M75,75 L85,80 L75,85"
                    fill="none"
                    stroke="#ec4899"
                    stroke-width="3"
                />
            </svg>
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
                    <div @click="focusMarker('{{ $place['place_id'] }}')"
                         class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 cursor-pointer">
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
                            @elseif(empty($place['opening_hours']))
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Unknown
                                </span>
                            @endif
                            <div class="mt-4">
                                <button @click.stop="showDirections('{{ $place['place_id'] }}')"
                                        class="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9.293 2.293a1 1 0 011.414 0l7 7A1 1 0 0117 11h-1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-3a1 1 0 00-1-1H9a1 1 0 00-1 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-6H3a1 1 0 01-.707-1.707l7-7z" />
                                    </svg>
                                    Get Directions
                                </button>
                            </div>
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
            <div x-show="travelTime"
                 x-transition
                 class="fixed bottom-4 left-1/2 transform -translate-x-1/2 bg-white rounded-lg shadow-lg p-4 z-40">
                <div class="flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="font-medium">Estimated travel time: <span x-text="travelTime" class="text-blue-600"></span></span>
                </div>
            </div>
        </div>
    </div>

    <x-footer />

    <div x-show="showDirectionsModal" x-cloak
         class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full overflow-hidden"
             @click.away="showDirectionsModal = false">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-500 to-cyan-500 p-6">
                <h3 class="text-2xl font-bold text-white" x-text="'Navigate to ' + (selectedDestination ? selectedDestination.name : '')"></h3>
                <p class="text-blue-100 mt-2">Choose how you want to start your journey</p>
            </div>

            <!-- Content -->
            <div class="p-6 space-y-6">
                <div class="space-y-4">
                    <div class="relative">
                        <input type="text"
                               id="startAddress"
                               placeholder="Enter starting address..."
                               class="w-full p-4 border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 pl-12"
                               @keydown.enter="calculateRoute('address')">
                        <svg class="absolute left-4 top-4 h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>

                    <div class="flex space-x-4">
                        <button @click="calculateRoute('address')"
                                class="flex-1 bg-gradient-to-r from-blue-500 to-cyan-500 text-white px-4 py-3 rounded-lg font-medium hover:from-blue-600 hover:to-cyan-600 transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 shadow-md">
                            <span class="flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                </svg>
                                Navigate
                            </span>
                        </button>
                        <button @click="calculateRoute('current')"
                                class="flex-1 bg-gradient-to-r from-green-500 to-emerald-500 text-white px-4 py-3 rounded-lg font-medium hover:from-green-600 hover:to-emerald-600 transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 shadow-md">
                            <span class="flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                </svg>
                                Use My Location
                            </span>
                        </button>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Travel Mode</label>
                    <div class="grid grid-cols-2 gap-2">
                        <button @click="selectedTravelMode = 'DRIVING'"
                                :class="{'bg-blue-500 text-white': selectedTravelMode === 'DRIVING', 'bg-gray-100 text-gray-700': selectedTravelMode !== 'DRIVING'}"
                                class="p-2 rounded-lg flex items-center justify-center">
                                <span class="flex justify-center items-center gap-1">
                                    <x-driving />
                                    <span class="mt-[5px]">Driving</span>
                                </span>

                        </button>
                        <button @click="selectedTravelMode = 'WALKING'"
                                :class="{'bg-blue-500 text-white': selectedTravelMode === 'WALKING', 'bg-gray-100 text-gray-700': selectedTravelMode !== 'WALKING'}"
                                class="p-2 rounded-lg flex items-center justify-center">
                            <x-walking />
                            Walking
                        </button>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="border-t border-gray-200 p-6">
                <button @click="showDirectionsModal = false"
                        class="w-full bg-gray-100 text-gray-700 px-6 py-3 rounded-lg font-medium hover:bg-gray-200 transition-all duration-300">
                    Cancel
                </button>
            </div>
        </div>
    </div>

    <script>
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition, showError, {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                });
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }

        function showPosition(position) {
            console.log("Geolocation success:", position);
            @this.setUserLocation(position.coords.latitude, position.coords.longitude);
        }

        function showError(error) {
            let message;
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    message = "User denied the request for Geolocation.";
                    break;
                case error.POSITION_UNAVAILABLE:
                    message = "Location information is unavailable. Please try again.";
                    break;
                case error.TIMEOUT:
                    message = "The request to get user location timed out. Please try again.";
                    break;
                case error.UNKNOWN_ERROR:
                    message = "An unknown error occurred. Please try again.";
                    break;
            }
            console.error("Geolocation error:", error);
            alert(message);
        }
    </script>
</div>



