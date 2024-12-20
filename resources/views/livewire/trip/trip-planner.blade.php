<div>
    <div class="min-h-screen bg-gradient-to-br from-blue-100 to-cyan-100 p-4 sm:p-6 lg:p-8">
        @if($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <p class="font-bold">Oops! Something went wrong:</p>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="max-w-7xl mx-auto">
            @if(!$planner)
                <div class="text-center py-12">
                    <h1 class="text-4xl font-bold text-blue-600 mb-4">Welcome to Your Travel Planner</h1>
                    <p class="text-xl text-cyan-700 mb-8">Ready to start your adventure? Generate your personalized travel plan now!</p>
                    <button wire:click="generatePlan" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                        <span class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                            </svg>
                            Generate Plan
                        </span>
                    </button>
                </div>
            @else
                <h2 class="text-3xl font-bold text-blue-600 mb-6 text-center">Your Travel Itinerary</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" x-data="{ activeDay: null }">
                    @foreach($plannerDays as $day)
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden transition duration-300 ease-in-out transform hover:scale-105"
                             x-on:click="activeDay = (activeDay === {{ $day->day_number }}) ? null : {{ $day->day_number }}">
                            <div class="bg-gradient-to-r from-blue-500 to-cyan-500 px-4 py-2">
                                <h3 class="text-xl font-semibold text-white">Day {{ $day->day_number }}</h3>
                            </div>
                            <div class="p-4">
                                <div class="mb-4">
                                    <h4 class="text-lg font-semibold text-blue-600 mb-2">Main Attraction</h4>
                                    <div class="flex items-center">
                                        <img src="/placeholder.svg?height=50&width=50" alt="Main Attraction" class="w-12 h-12 rounded-full mr-3 object-cover">
                                        <p class="text-cyan-700">{{ $day->main_attraction['name'] ?? 'No attraction found' }}</p>
                                    </div>
                                </div>
                                <div x-show="activeDay === {{ $day->day_number }}" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                                    <div class="mb-4">
                                        <h4 class="text-lg font-semibold text-blue-600 mb-2">Other Places to Visit</h4>
                                        <ul class="space-y-2">
                                            @foreach($day->places_to_visit as $place)
                                                <li class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-cyan-500 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                                    </svg>
                                                    <span class="text-cyan-700">{{ $place['name'] ?? 'Unnamed place' }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-blue-600 font-semibold">
                                            Estimated costs: ${{ $day->estimated_costs['min'] }} - ${{ $day->estimated_costs['max'] }}
                                        </p>
                                    </div>
                                </div>
                                <button x-show="activeDay !== {{ $day->day_number }}" class="mt-2 text-blue-500 hover:text-blue-600 text-sm font-medium focus:outline-none">
                                    Show details
                                </button>
                                <button x-show="activeDay === {{ $day->day_number }}" class="mt-2 text-blue-500 hover:text-blue-600 text-sm font-medium focus:outline-none">
                                    Hide details
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>


</div>
