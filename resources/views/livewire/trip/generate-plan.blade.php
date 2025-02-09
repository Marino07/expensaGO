<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <h2 class="text-3xl font-bold text-center text-indigo-600 mb-8">Main attractions</h2>

        @if ($errorMessage)
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p class="font-bold">Error</p>
                <p>{{ $errorMessage }}</p>
            </div>
        @else
            <div class="space-y-8">
                @foreach ($plan as $index => $dayPlan)
                    <div x-data="{ init: false }" x-init="init = true" x-show="init" x-cloak
                        class="bg-white shadow-lg rounded-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4">
                            <h3 class="text-xl font-semibold text-white">Day {{ $index + 1 }}: {{ $dayPlan['day'] }}
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="md:flex">
                                @if ($dayPlan['image_url'])
                                    <div class="md:flex-shrink-0">
                                        <img src="{{ $dayPlan['image_url'] }}" alt="{{ $dayPlan['attraction'] }}"
                                            class="h-48 w-full object-cover md:w-48">
                                    </div>
                                @endif
                                <div class="mt-4 md:mt-0 md:ml-6">
                                    <div class="uppercase tracking-wide text-sm text-indigo-600 font-bold">Visit</div>
                                    <p class="mt-2 text-gray-600">{{ $dayPlan['attraction'] }}</p>

                                    <div class="mt-4">
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path
                                                    d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" />
                                            </svg>
                                            <span class="text-gray-600">Lunch at: {{ $dayPlan['restaurant'] }}</span>
                                        </div>
                                        <div class="flex items-center mt-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <span class="text-gray-600">Walking time:
                                                {{ $dayPlan['walking_time'] }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-6 flex flex-wrap gap-2">
                                <span
                                    class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    Attraction cost: ${{ $dayPlan['attraction_cost'] }}
                                </span>
                                <span
                                    class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    Restaurant cost: ${{ $dayPlan['restaurant_cost'] }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
