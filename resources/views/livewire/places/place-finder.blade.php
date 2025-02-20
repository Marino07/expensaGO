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
    userMarker: null,
    userWatchId: null,
    currentLocation: null,
    userLocationMarker: null,
    map: null,
    startMarker: null,
    endMarker: null,
    selectedPlaceType: $wire.placeType,
    initMap() {
        this.mapLoaded = false;
        this.markers = [];

        // Parse the coordinates from geo_lat_lng
        const [lat, lng] = '{{ $geo_lat_lng }}'.split(',').map(coord => parseFloat(coord));

        this.map = new google.maps.Map(document.getElementById('map'), {
            center: { lat: lat, lng: lng },
            zoom: this.activeTab === 'map' ? 17 : 14 // Increase zoom level for individual marker
        });

        // Initialize directions service and renderer
        this.directionsService = new google.maps.DirectionsService();
        if (!this.directionsRenderer) {
            this.directionsRenderer = new google.maps.DirectionsRenderer({
                suppressMarkers: false
            });
        }
        this.directionsRenderer.setMap(this.map);

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
                map: this.map,
                title: place.name,
            });
            this.markers.push({
                id: place.place_id,
                marker: marker
            });
            bounds.extend(position);
        });

        // Only fit bounds if we have places
        if (@this.places.length > 0) {
            this.map.fitBounds(bounds);
            const listener = google.maps.event.addListener(this.map, 'idle', function() {
                if (this.map.getZoom() > 16) this.map.setZoom(16);
                google.maps.event.removeListener(listener);
            });
        }

        this.mapLoaded = true;
    },
    focusMarker(placeId) {
        this.activeTab = 'map';
        this.initMap();
        this.$nextTick(() => {
            if (this.mapLoaded) {
                this.zoomToMarker(placeId);
            } else {
                this.$watch('mapLoaded', (value) => {
                    if (value) {
                        this.zoomToMarker(placeId);
                    }
                });
            }
        });
    },
    zoomToMarker(placeId) {
        const markerData = this.markers.find(m => m.id === placeId);
        if (markerData && this.map) {
            this.map.panTo(markerData.marker.getPosition());
            this.map.setZoom(20); // Increase zoom level for individual marker
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
            // Reset any existing navigation state
            if (this.directionsRenderer) {
                this.directionsRenderer.setMap(null);
                this.directionsRenderer = null;
            }
            if (this.userMarker) {
                this.userMarker.setMap(null);
                this.userMarker = null;
            }
            if (this.userWatchId) {
                navigator.geolocation.clearWatch(this.userWatchId);
                this.userWatchId = null;
            }

            // Use current location with new permission check
            if (navigator.geolocation) {
                navigator.permissions.query({ name: 'geolocation' }).then(result => {
                    if (result.state === 'granted' || result.state === 'prompt') {
                        navigator.geolocation.getCurrentPosition(
                            (position) => {
                                const start = {
                                    lat: position.coords.latitude,
                                    lng: position.coords.longitude
                                };
                                this.getDirections(start, destination, true);
                            },
                            (error) => {
                                console.error('Geolocation error:', error);
                                alert('Unable to get your location. Please try again or enter an address.');
                            },
                            {
                                enableHighAccuracy: true,
                                timeout: 5000,
                                maximumAge: 0
                            }
                        );
                    } else {
                        alert('Location permission is required for this feature.');
                    }
                });
            }
        } else {
            // Use address input
            const address = document.getElementById('startAddress').value;
            const geocoder = new google.maps.Geocoder();
            geocoder.geocode({ address: address }, (results, status) => {
                if (status === 'OK') {
                    const start = results[0].geometry.location;
                    this.getDirections(start, destination, false);
                }
            });
        }
    },
    getDirections(start, destination, trackLocation) {
        // Reset existing navigation components
        if (this.directionsRenderer) {
            this.directionsRenderer.setMap(null);
        }
        if (this.userMarker) {
            this.userMarker.setMap(null);
        }
        if (this.accuracyCircle) {
            this.accuracyCircle.setMap(null);
        }
        if (this.startMarker) {
            this.startMarker.setMap(null);
        }
        if (this.endMarker) {
            this.endMarker.setMap(null);
        }

        // Initialize new directions renderer
        this.directionsRenderer = new google.maps.DirectionsRenderer({
            suppressMarkers: true,
            polylineOptions: {
                strokeColor: '#4F46E5',
                strokeWeight: 5,
                strokeOpacity: 0.8
            }
        });

        this.directionsRenderer.setMap(this.map);

        // Create custom markers
        this.startMarker = new google.maps.Marker({
            map: this.map,
            icon: {
                url: 'https://maps.google.com/mapfiles/ms/icons/green-dot.png',
                scaledSize: new google.maps.Size(40, 40)
            }
        });

        this.endMarker = new google.maps.Marker({
            map: this.map,
            icon: {
                url: 'https://maps.google.com/mapfiles/ms/icons/red-dot.png',
                scaledSize: new google.maps.Size(40, 40)
            }
        });

        // Calculate route
        if (!this.directionsService) {
            this.directionsService = new google.maps.DirectionsService();
        }

        this.directionsService.route({
            origin: start,
            destination: destination,
            travelMode: google.maps.TravelMode[this.selectedTravelMode]
        }, (response, status) => {
            if (status === 'OK') {
                this.activeTab = 'map';
                this.markers.forEach(marker => marker.marker.setMap(null));
                this.directionsRenderer.setDirections(response);

                const route = response.routes[0];
                if (route && route.legs && route.legs[0]) {
                    this.startMarker.setPosition(route.legs[0].start_location);
                    this.endMarker.setPosition(route.legs[0].end_location);
                    this.travelTime = route.legs[0].duration.text;
                }

                // Center map on the route
                const bounds = new google.maps.LatLngBounds();
                route.legs[0].steps.forEach(step => {
                    bounds.extend(step.start_location);
                    bounds.extend(step.end_location);
                });
                this.map.fitBounds(bounds);

                // Only start tracking if it's a current location route
                if (trackLocation) {
                    this.$nextTick(() => {
                        this.watchUserPosition();
                    });
                }

                this.showDirectionsModal = false;
            } else {
                alert('Could not calculate directions: ' + status);
            }
        });
    },
    watchUserPosition() {
        if (!navigator.geolocation) return;

        // Clear existing watch
        if (this.userWatchId) {
            navigator.geolocation.clearWatch(this.userWatchId);
        }

        // Create new user marker if it doesn't exist or was removed
        if (!this.userMarker || !this.userMarker.getMap()) {
            this.userMarker = new google.maps.Marker({
                map: this.map,
                icon: {
                    path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
                    fillColor: '#EC4899',
                    fillOpacity: 1,
                    strokeColor: '#ffffff',
                    strokeWeight: 2,
                    scale: 7,
                    rotation: 0
                },
                optimized: false,
                title: 'Your Location',
                zIndex: 999
            });
        }

        // Create or recreate accuracy circle
        if (!this.accuracyCircle || !this.accuracyCircle.getMap()) {
            this.accuracyCircle = new google.maps.Circle({
                map: this.map,
                fillColor: '#EC4899',
                fillOpacity: 0.15,
                strokeColor: '#EC4899',
                strokeOpacity: 0.4,
                strokeWeight: 1
            });
        }

        let lastPosition = null;
        let lastHeading = 0;

        // Start new watch with high accuracy
        this.userWatchId = navigator.geolocation.watchPosition(
            (position) => {
                const pos = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };

                if (lastPosition) {
                    const heading = google.maps.geometry.spherical.computeHeading(
                        new google.maps.LatLng(lastPosition),
                        new google.maps.LatLng(pos)
                    );
                    if (heading !== 0) {
                        lastHeading = heading;
                    }
                }

                const icon = this.userMarker.getIcon();
                icon.rotation = position.coords.heading || lastHeading;
                this.userMarker.setIcon(icon);
                this.userMarker.setPosition(pos);

                this.accuracyCircle.setCenter(pos);
                this.accuracyCircle.setRadius(position.coords.accuracy);

                if (!lastPosition) {
                    this.map.panTo(pos);
                }

                lastPosition = pos;
            },
            (error) => {
                console.error('Geolocation watch error:', error);
            },
            {
                enableHighAccuracy: true,
                timeout: 5000,
                maximumAge: 0
            }
        );
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
        script.src = 'https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_PLACES_API_KEY') }}&libraries=places,geocoding,geometry';
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

    $cleanup = () => {
        if (userWatchId) {
            navigator.geolocation.clearWatch(userWatchId);
        }
        if (userMarker) {
            userMarker.setMap(null);
        }
    };
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
        <div class="p-6 space-y-6 overflow-y-auto max-h-[calc(100vh-180px)]">
            <!-- Place Type Filter -->
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <label class="text-lg font-semibold text-gray-900">Categories</label>
                    <span class="text-sm text-gray-500">Select a category to explore</span>
                </div>

                <!-- Food & Drinks -->
                <div class="space-y-3">
                    <h3 class="text-sm font-medium text-gray-700 uppercase tracking-wide">Food & Drinks</h3>
                    <div class="grid grid-cols-2 gap-3">
                        <button @click="
                            selectedPlaceType = 'restaurant';
                            $wire.setPlaceType('restaurant');
                        "
                        :class="{
                            'border-blue-500 bg-blue-50': selectedPlaceType === 'restaurant',
                            'border-gray-200 hover:border-blue-200': selectedPlaceType !== 'restaurant'
                        }"
                        class="flex items-center p-3 rounded-lg border-2 transition-all duration-200">
                            <svg class="w-6 h-6 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6h18M3 12h18M3 18h18"/>
                            </svg>
                            <span class="font-medium">Restaurants</span>
                        </button>

                        <button @click="
                            selectedPlaceType = 'cafe';
                            $wire.setPlaceType('cafe');
                        "
                        :class="{
                            'border-blue-500 bg-blue-50': selectedPlaceType === 'cafe',
                            'border-gray-200 hover:border-blue-200': selectedPlaceType !== 'cafe'
                        }"
                        class="flex items-center p-3 rounded-lg border-2 transition-all duration-200">
                            <svg class="w-6 h-6 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M18 8h1a4 4 0 010 8h-1M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8z"/>
                            </svg>
                            <span class="font-medium">Cafes</span>
                        </button>

                        <button @click="
                            selectedPlaceType = 'bar';
                            $wire.setPlaceType('bar');
                        "
                        :class="{
                            'border-blue-500 bg-blue-50': selectedPlaceType === 'bar',
                            'border-gray-200 hover:border-blue-200': selectedPlaceType !== 'bar'
                        }"
                        class="flex items-center p-3 rounded-lg border-2 transition-all duration-200">
                            <svg class="w-6 h-6 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span class="font-medium">Bars</span>
                        </button>

                        <button @click="
                            selectedPlaceType = 'night_club';
                            $wire.setPlaceType('night_club');
                        "
                        :class="{
                            'border-blue-500 bg-blue-50': selectedPlaceType === 'night_club',
                            'border-gray-200 hover:border-blue-200': selectedPlaceType !== 'night_club'
                        }"
                        class="flex items-center p-3 rounded-lg border-2 transition-all duration-200">
                            <svg class="w-6 h-6 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            <span class="font-medium">Night Clubs</span>
                        </button>
                    </div>
                </div>

                <!-- Entertainment & Culture -->
                <div class="space-y-3">
                    <h3 class="text-sm font-medium text-gray-700 uppercase tracking-wide">Shopping & Services</h3>
                    <div class="grid grid-cols-2 gap-3">
                        <button @click="$wire.setPlaceType('shopping_mall')"
                                class="flex items-center p-3 rounded-lg border-2 transition-all duration-200"
                                :class="{'border-blue-500 bg-blue-50': $wire.placeType === 'shopping_mall', 'border-gray-200 hover:border-blue-200': $wire.placeType !== 'shopping_mall'}">
                            <svg class="w-6 h-6 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            <span class="font-medium">Shopping Malls</span>
                        </button>

                        <button @click="$wire.setPlaceType('store')"
                                class="flex items-center p-3 rounded-lg border-2 transition-all duration-200"
                                :class="{'border-blue-500 bg-blue-50': $wire.placeType === 'store', 'border-gray-200 hover:border-blue-200': $wire.placeType !== 'store'}">
                            <svg class="w-6 h-6 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M3 3h18v18H3zM12 8v8m-4-4h8"/>
                            </svg>
                            <span class="font-medium">Stores</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Sort Options -->
            <div class="space-y-3 border-t pt-6">
                <h3 class="text-sm font-medium text-gray-700 uppercase tracking-wide">Sort Results</h3>
                <div class="flex space-x-3">
                    <button @click="$wire.setSortCriteria('rating')"
                            class="flex-1 p-3 rounded-lg border-2 transition-all duration-200"
                            :class="{'border-blue-500 bg-blue-50': $wire.sortCriteria === 'rating', 'border-gray-200': $wire.sortCriteria !== 'rating'}">
                        <div class="flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <span class="font-medium">By Rating</span>
                        </div>
                    </button>

                    <button @click="$wire.setSortCriteria('price')"
                            class="flex-1 p-3 rounded-lg border-2 transition-all duration-200"
                            :class="{'border-blue-500 bg-blue-50': $wire.sortCriteria === 'price', 'border-gray-200': $wire.sortCriteria !== 'price'}">
                        <div class="flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2-1.343-2-3-2z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2z"/>
                            </svg>
                            <span class="font-medium">By Price</span>
                        </div>
                    </button>
                </div>
            </div>
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
                         class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 cursor-pointer relative flex flex-col h-[500px]"> <!-- dodali smo flex flex-col i fiksnu visinu -->

                        <!-- Heart button - dodajte ovo odmah nakon opening div-a -->
                        <button wire:click.stop="toggleSavePlace('{{ $place['place_id'] }}')"
                                class="absolute top-4 right-4 z-10 p-2 rounded-full bg-white/80 backdrop-blur-sm hover:bg-white transition-all duration-300 group shadow-md">
                            @if($this->isPlaceSaved($place['place_id']))
                                <svg class="w-6 h-6 text-red-500 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                </svg>
                            @else
                                <svg class="w-6 h-6 text-gray-600 group-hover:text-red-500 transition-colors duration-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                            @endif
                        </button>

                        <!-- Ostatak kartice ostaje isti -->
                        @php
                            $defaultImage = match($placeType) {
                                'restaurant' => 'images/defaults/restaurant-image.jpg',
                                'store' => 'images/defaults/local_shop.png',
                                'cafe' => 'images/defaults/coffe_shop.jpeg',
                                'bar' => 'images/defaults/bar.ong.png',
                                default => 'images/defaults/place.png',
                            };
                        @endphp
                        <div class="w-full h-48 flex-shrink-0"> <!-- wrapper div za sliku -->
                            <img src="{{ $place['photo'] ?? asset($defaultImage) }}"
                                 alt="{{ $place['name'] }}"
                                 class="w-full h-full object-cover">
                        </div>

                        <!-- Content wrapper - dodajemo flex-grow za dinamičku visinu -->
                        <div class="p-6 flex flex-col flex-grow">
                            <!-- Naziv - ograničavamo na 2 reda -->
                            <h3 class="text-xl font-semibold text-gray-900 mb-2 line-clamp-2 h-14">
                                {{ $place['name'] }}
                            </h3>

                            <!-- Rating sekcija - fiksna visina -->
                            <div class="flex items-center mb-2 h-6">
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

                            <!-- Adresa - ograničavamo na 2 reda -->
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2 h-10">
                                {{ $place['vicinity'] ?? 'No address available' }}
                            </p>

                            <!-- Status (Open/Closed) -->
                            <div class="h-6 mb-4">
                                @if(isset($place['opening_hours']['open_now']))
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $place['opening_hours']['open_now'] ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $place['opening_hours']['open_now'] ? 'Open Now' : 'Closed' }}
                                    </span>
                                @elseif(empty($place['opening_hours']))
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Unknown
                                    </span>
                                @endif
                            </div>

                            <!-- Buttons section - uvijek na dnu kartice -->
                            <div class="mt-auto">
                                <!-- Get Directions Button -->
                                <button @click.stop="showDirections('{{ $place['place_id'] }}')"
                                        class="relative w-full overflow-hidden group">
                                    <!-- Button Container -->
                                    <div class="relative px-6 py-3 bg-gradient-to-r from-blue-500 via-blue-600 to-cyan-500 rounded-lg
                                                transform transition-all duration-300 ease-out
                                                group-hover:scale-[1.02] group-hover:shadow-xl
                                                group-active:scale-[0.98]">
                                        <!-- Hover Effect -->
                                        <div class="absolute inset-0 bg-gradient-to-r from-cyan-400 to-blue-400 opacity-0
                                                    group-hover:opacity-50 transition-opacity duration-300 rounded-lg"></div>

                                        <!-- Shimmer Effect -->
                                        <div class="absolute inset-0 transform translate-x-[-100%] group-hover:translate-x-[100%]
                                                    transition-transform duration-1000
                                                    bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>

                                        <!-- Content -->
                                        <div class="relative flex items-center justify-center space-x-3">
                                            <!-- Icon -->
                                            <span class="flex items-center transform transition-transform duration-300 group-hover:scale-110">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                                </svg>
                                            </span>
                                            <!-- Text -->
                                            <span class="text-white font-medium tracking-wide">
                                                Get Directions
                                            </span>
                                        </div>

                                        <!-- Decorative Elements -->
                                        <div class="absolute -right-1 -top-1 w-2 h-2 bg-cyan-300 rounded-full opacity-70 animate-pulse"></div>
                                        <div class="absolute -left-1 -bottom-1 w-2 h-2 bg-blue-300 rounded-full opacity-70 animate-pulse delay-300"></div>
                                    </div>
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
            <div x-show="travelTime" x-cloak
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

    <x-AIchat />

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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 006 0z"/>
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
                // Add a specific permission prompt
                navigator.permissions.query({ name: 'geolocation' }).then(function(result) {
                    if (result.state === 'granted') {
                        requestLocation();
                    } else if (result.state === 'prompt') {
                        requestLocation();
                    } else if (result.state === 'denied') {
                        alert("Please enable location access in your browser settings to use this feature.");
                    }
                });
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }

        function requestLocation() {
            navigator.geolocation.getCurrentPosition(showPosition, showError, {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            });
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



