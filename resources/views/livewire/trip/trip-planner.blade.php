<div
x-data="{
    isLoading: false,
    loadingStartTime: null,
    init() {
        Livewire.on('generation-started', () => {
            this.isLoading = true;
            this.loadingStartTime = Date.now();
        });
        Livewire.on('generation-completed', () => {
            this.isLoading = false;
        });
    }
}"
>
    <x-barapp />
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
            <h2 class="text-4xl md:text-5xl lg:text-4xl font-bold mb-8 text-center">
                <div class="inline-flex items-center justify-center gap-2 group text-cyan-400">
                  <span class="bg-gradient-to-r from-blue-500 to-cyan-400 text-transparent bg-clip-text transition-all duration-300 ease-in-out transform group-hover:scale-105">
                    Enjoy your trip
                  </span>
                  <div class="transition-transform duration-300 ease-in-out transform group-hover:rotate-12">
                    <x-application-logo class="h-10 w-auto md:h-12 lg:h-14 text-blue-500" />
                  </div>
                </div>
              </h2>

                <div x-data="{ activeDay: {}, isLoading: false }" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($plannerDays as $index => $day)
                        <div class="relative h-[450px] perspective-1000"
                             x-data="{ flipped: false }">

                            <!-- Inner container with blur -->
                            <div class="w-full h-full transition-all duration-500 preserve-3d"
                                 :class="{
                                     'blur-md pointer-events-none': {{ $index >= $cardsToShow }},
                                     'rotate-y-180': flipped
                                 }"
                                 x-on:click="flipped = !flipped"
                                 :style="flipped ? 'transform: rotateY(180deg)' : ''">

                                <!-- Front Content -->
                                <div class="absolute w-full h-full backface-hidden bg-white rounded-2xl shadow-xl overflow-hidden">
                                    <div class="bg-gradient-to-r from-blue-500 to-teal-400 px-6 py-4">
                                        <h3 class="text-2xl font-bold text-white">Day {{ $day->day_number }}</h3>
                                    </div>
                                    <div class="p-6 flex flex-col h-[calc(100%-76px)]">
                                        <div class="flex-grow">
                                            <h4 class="text-xl font-semibold text-blue-600 mb-3">Main Attraction</h4>
                                            <div class="flex flex-col bg-blue-50 p-4 rounded-lg transform transition-transform duration-300 hover:scale-102">
                                                <!-- Main attraction image and basic info -->
                                                <div class="flex items-center mb-4">
                                                    <img src="{{ $day->main_attraction['photo_url'] ?? 'default-image-url' }}"
                                                         alt="Main Attraction"
                                                         class="w-20 h-20 rounded-full mr-4 object-cover shadow-md">
                                                    <div class="flex-grow">
                                                        <p class="text-lg font-medium text-blue-800">{{ $day->main_attraction['name'] }}</p>
                                                        <div class="flex items-center gap-2 mt-2">
                                                            <span class="inline-block bg-teal-100 text-teal-800 text-sm px-3 py-1 rounded-full">
                                                                {{ $day->main_attraction['preference_name'] }}
                                                            </span>
                                                            <span class="inline-block {{ $day->main_attraction['price_info'] === 'Free' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-800' }} text-sm px-3 py-1 rounded-full">
                                                                {{ $day->main_attraction['price_info'] }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Additional attraction details -->
                                                <div class="border-t border-blue-100 pt-4">
                                                    <div class="flex justify-between items-center text-sm text-blue-700">
                                                        <span class="flex items-center">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            Recommended visit: 2-3 hours
                                                        </span>
                                                        @if(isset($day->main_attraction['geometry']['location']))
                                                            <a href="https://www.google.com/maps/search/?api=1&query={{ $day->main_attraction['geometry']['location']['lat'] }},{{ $day->main_attraction['geometry']['location']['lng'] }}"
                                                               target="_blank"
                                                               class="flex items-center text-blue-600 hover:text-blue-800 transition-colors">
                                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                </svg>
                                                                View on Maps
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                         <!-- Flip card hint -->

                                        <div class="text-center mt-4 text-gray-500 flex items-center justify-center group cursor-pointer">
                                            <svg class="w-5 h-5 mr-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                            </svg>
                                            <span class="text-[15px]">Click to see {{ count($day->places_to_visit) }} more attractions</span>
                                        </div>


                                        {{-- Average costs --}}

                                        <div class="text-center mt-4 text-blue-500 flex items-center justify-center group cursor-pointer">

                                            <span class="text-[15px]"> 💸 Expense average {{round($averageDayCost)}}$ per day </span>
                                        </div>



                                    </div>
                                </div>

                                <!-- Back of card -->
                                <div class="absolute w-full h-full backface-hidden rotate-y-180 bg-white rounded-2xl shadow-xl overflow-hidden"
                                     :style="'transform: rotateY(180deg)'">
                                    <div class="bg-gradient-to-r from-teal-400 to-blue-500 px-6 py-4">
                                        <h3 class="text-2xl font-bold text-white">Click anywhere to back </h3>
                                    </div>
                                    <div class="p-6 overflow-auto max-h-[350px]">
                                        <ul class="space-y-4">
                                            @foreach($day->places_to_visit as $place)
                                                <li class="bg-gray-50 p-4 rounded-lg transform transition-all duration-300 hover:scale-105 hover:shadow-md">
                                                    <div class="flex items-start gap-4">
                                                        <!-- Attraction Image -->
                                                        <div class="flex-shrink-0">
                                                            <img src="{{ $place['photo_url'] ?? 'https://images.unsplash.com/photo-1488646953014-85cb44e25828?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=200&h=200&q=80' }}"
                                                                 alt="{{ $place['name'] }}"
                                                                 class="w-16 h-16 rounded-lg object-cover shadow-md transform transition-transform hover:scale-110">
                                                        </div>

                                                        <!-- Attraction Details -->
                                                        <div class="flex-grow">
                                                            <div class="flex justify-between items-start">
                                                                <div>
                                                                    <span class="text-gray-800 font-medium block mb-1">{{ $place['name'] ?? 'Unnamed place' }}</span>
                                                                    <div class="flex items-center gap-2">
                                                                        <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                                                                            {{ $place['preference_name'] }}
                                                                        </span>
                                                                        <span class="inline-block {{ $place['price_info'] === 'Free' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-800' }} text-xs px-2 py-1 rounded-full">
                                                                            {{ $place['price_info'] }}
                                                                        </span>
                                                                    </div>
                                                                </div>

                                                                @if(isset($place['geometry']['location']))
                                                                    <a href="https://www.google.com/maps/search/?api=1&query={{ $place['geometry']['location']['lat'] }},{{ $place['geometry']['location']['lng'] }}"
                                                                       target="_blank"
                                                                       class="text-blue-500 hover:text-blue-600 transition-colors duration-300 p-1 hover:bg-blue-50 rounded-full">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                        </svg>
                                                                    </a>
                                                                @endif
                                                            </div>

                                                            <!-- Optional: Add visit duration if available -->
                                                            <div class="mt-2 text-xs text-gray-500 flex items-center">
                                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>
                                                                Suggested: 1-2 hours
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Lock overlay for locked cards -->
                            <template x-if="{{ $index >= $cardsToShow }}">
                                <div class="absolute inset-0 z-10 flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-600 mb-3" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 1a5 5 0 0 0-5 5v2H6a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-9a2 2 0 0 0-2-2h-1V6a5 5 0 0 0-5-5zm3 7H9V6a3 3 0 1 1 6 0z"/>
                                    </svg>
                                    <p class="text-gray-700 font-medium">Day Locked</p>
                                </div>
                            </template>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
    {{-- AI Chat --}}
    <x-AIchat />

    <style>
        [x-cloak] { display: none !important; }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .animate-spin {
            animation: spin 2s linear infinite;
        }
        .perspective-1000 {
            perspective: 1000px;
        }

        .preserve-3d {
            transform-style: preserve-3d;
        }

        .backface-hidden {
            backface-visibility: hidden;
        }

        .rotate-y-180 {
            transform: rotateY(180deg);
        }

        /* Custom scrollbar for the attractions list */
        .overflow-auto::-webkit-scrollbar {
            width: 8px;
        }

        .overflow-auto::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .overflow-auto::-webkit-scrollbar-thumb {
            background: #cbd5e0;
            border-radius: 4px;
        }

        .overflow-auto::-webkit-scrollbar-thumb:hover {
            background: #a0aec0;
        }
    </style>
    <x-footer />
</div>
