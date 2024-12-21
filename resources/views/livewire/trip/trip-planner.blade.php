<div
    x-data="{
        isLoading: false,
        minLoadingTime: 3000, // 3 seconds minimum
        loadingStartTime: null,
        init() {
            Livewire.on('generation-started', () => {
                this.isLoading = true;
                this.loadingStartTime = Date.now();
            });
            Livewire.on('generation-completed', () => {
                const elapsedTime = Date.now() - this.loadingStartTime;
                const remainingTime = Math.max(0, this.minLoadingTime - elapsedTime);

                setTimeout(() => {
                    this.isLoading = false;
                }, remainingTime);
            });
        }
    }"
>
    <!-- Loading Overlay -->
    <div x-show="isLoading"
         x-cloak
         class="fixed inset-0 z-50 overflow-hidden bg-gray-900/80 backdrop-blur-sm flex items-center justify-center">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-sm mx-auto transform">
            <div class="text-center">
                <div class="relative inline-flex items-center justify-center mb-6">
                    <!-- Outer ring animation -->
                    <div class="absolute border-4 border-blue-600 rounded-full w-20 h-20 animate-spin border-t-transparent"></div>
                    <!-- Inner ring animation -->
                    <div class="absolute border-4 border-teal-400 rounded-full w-12 h-12 animate-spin border-t-transparent" style="animation-direction: reverse;"></div>
                    <!-- Center icon -->
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-2xl font-semibold text-gray-800 mb-2">Generating Plan</p>
                <p class="text-gray-500 text-sm">Please wait while we create your perfect itinerary</p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-teal-100 p-4 sm:p-6 lg:p-8">
        <div class="max-w-7xl mx-auto">
            @if($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow-md" role="alert">
                    <p class="font-bold">Oops! Something went wrong:</p>
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(!$planner)
                <div class="text-center py-16 px-4 sm:px-6 lg:px-8">
                    <h1 class="text-5xl font-extrabold text-blue-600 mb-6">Welcome to Your Travel Planner</h1>
                    <p class="text-2xl text-teal-700 mb-10 max-w-3xl mx-auto">Ready to embark on your next adventure? Generate your personalized travel plan and explore the world with confidence!</p>
                    <button
                        wire:click="generatePlan"
                        x-on:click="isLoading = true"
                        x-bind:disabled="isLoading"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-8 rounded-full text-xl transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 shadow-lg disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span x-show="!isLoading" class="flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Generate Your Travel Plan
                        </span>
                        <span x-show="isLoading" class="flex items-center justify-center">
                            <svg class="animate-spin h-5 w-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Processing...
                        </span>
                    </button>
                </div>
            @else
                <h2 class="text-4xl font-bold text-blue-600 mb-8 text-center">Your Personalized Travel Itinerary</h2>
                <div x-data="{ activeDay: {}, isLoading: false }" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($plannerDays as $day)
                        <div class="bg-white rounded-2xl shadow-xl overflow-hidden transition duration-300 ease-in-out transform hover:scale-102 hover:shadow-2xl"
                             x-on:click="activeDay[{{ $day->day_number }}] = !activeDay[{{ $day->day_number }}]">
                            <div class="bg-gradient-to-r from-blue-500 to-teal-400 px-6 py-4">
                                <h3 class="text-2xl font-bold text-white">Day {{ $day->day_number }}</h3>
                            </div>
                            <div class="p-6">
                                <div class="mb-6">
                                    <h4 class="text-xl font-semibold text-blue-600 mb-3">Main Attraction</h4>
                                    <div class="flex items-center bg-blue-50 p-4 rounded-lg">
                                        <img src="{{ $day->main_attraction['photo_url'] ?? 'https://images.unsplash.com/photo-1488646953014-85cb44e25828?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=200&h=200&q=80' }}" alt="Main Attraction" class="w-16 h-16 sm:w-20 sm:h-20 rounded-full mr-4 object-cover shadow-md">
                                        <div>
                                            <p class="text-lg font-medium text-blue-800">{{ $day->main_attraction['name'] ?? 'No attraction found' }}</p>
                                            <span class="inline-block bg-teal-100 text-teal-800 text-sm px-3 py-1 rounded-full mt-2">{{ $day->main_attraction['preference_name'] }}</span>
                                        </div>
                                        @if(isset($day->main_attraction['geometry']['location']))
                                            <a href="https://www.google.com/maps/search/?api=1&query={{ $day->main_attraction['geometry']['location']['lat'] }},{{ $day->main_attraction['geometry']['location']['lng'] }}"
                                               target="_blank"
                                               class="ml-auto text-blue-500 hover:text-blue-600 transition duration-150 ease-in-out"
                                               title="Open in Google Maps">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                <div x-show="activeDay[{{ $day->day_number }}]" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" class="space-y-6">
                                    <div>
                                        <h4 class="text-xl font-semibold text-blue-600 mb-3">Other Places to Visit</h4>
                                        <ul class="space-y-4">
                                            @foreach($day->places_to_visit as $place)
                                                <li class="flex items-center bg-gray-50 p-3 rounded-lg">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-teal-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                                    </svg>
                                                    <span class="text-gray-800 font-medium">{{ $place['name'] ?? 'Unnamed place' }}</span>
                                                    <span class="ml-2 inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">{{ $place['preference_name'] }}</span>
                                                    @if(isset($place['geometry']['location']))
                                                        <a href="https://www.google.com/maps/search/?api=1&query={{ $place['geometry']['location']['lat'] }},{{ $place['geometry']['location']['lng'] }}"
                                                           target="_blank"
                                                           class="ml-auto text-blue-500 hover:text-blue-600 transition duration-150 ease-in-out"
                                                           title="Open in Google Maps">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            </svg>
                                                        </a>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <div class="bg-blue-50 p-4 rounded-lg">
                                        <p class="text-lg text-blue-800 font-semibold">
                                            Estimated costs:
                                            <span class="text-teal-600">${{ $day->estimated_costs['min'] }} - ${{ $day->estimated_costs['max'] }}</span>
                                        </p>
                                    </div>
                                </div>
                                <button
                                    x-show="!activeDay[{{ $day->day_number }}]"
                                    @click="activeDay[{{ $day->day_number }}] = true"
                                    class="mt-4 w-full bg-blue-100 hover:bg-blue-200 text-blue-800 font-semibold py-2 px-4 rounded-lg transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50"
                                >
                                    Show details
                                </button>
                                <button
                                    x-show="activeDay[{{ $day->day_number }}]"
                                    @click="activeDay[{{ $day->day_number }}] = false"
                                    class="mt-4 w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50"
                                >
                                    Hide details
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .animate-spin {
            animation: spin 2s linear infinite;
        }
    </style>
</div>
