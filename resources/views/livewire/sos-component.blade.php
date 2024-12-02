<div>
    <x-barapp />

    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 p-4">
        <div class="max-w-2xl mx-auto">
            <!-- Header Section -->
            <div class="bg-cyan-100 rounded-lg shadow-lg mb-6 p-6">
                <h2 class="text-2xl font-bold text-center text-indigo-600 mb-4">Emergency Services</h2>
                <div class="flex justify-center space-x-4">
                    <button wire:click="searchEmergencyServices('hospital')"
                        class="flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition duration-200 ease-in-out">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                                clip-rule="evenodd" />
                        </svg>
                        Hospitals
                    </button>
                    <button wire:click="searchEmergencyServices('police')"
                        class="flex items-center px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded-lg transition duration-200 ease-in-out">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path
                                d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
                        </svg>
                        Police Stations
                    </button>
                </div>
            </div>

            <!-- Results List -->
            <div class="space-y-4">
                @forelse($emergencyServices as $service)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition duration-200 ease-in-out p-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $service['name'] }}</h3>
                                <p class="mt-1 text-gray-600 flex items-center">
                                    <span class="text-sm">{{ $service['vicinity'] }}</span>
                                </p>
                            </div>
                            <div class="ml-4">
                                <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($service['vicinity']) }}"
                                    target="_blank"
                                    class="text-indigo-600 hover:text-indigo-800 transition duration-150 ease-in-out">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-lg shadow p-6 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-4"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <p class="text-gray-600">No emergency services found in your area.</p>
                        <p class="text-sm text-gray-500 mt-2">Please try searching again or expand your search radius.
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    <x-footer />

</div>
