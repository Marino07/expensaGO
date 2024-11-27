
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white shadow-xl rounded-lg overflow-hidden">
            <div class="bg-gradient-to-r from-purple-500 to-indigo-600 px-6 py-8 md:p-10 text-white">
                <h2 class="text-3xl font-extrabold tracking-tight">{{$search}} Restaurants</h2>
                <p class="mt-2 text-lg">Discover great places to eat in {{$search}}</p>
            </div>

            <div class="px-6 py-8 md:p-10">
                <!-- Loading State -->
                <div wire:loading class="w-full text-center py-4">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
                    <p class="mt-2 text-gray-600">Loading restaurants...</p>
                </div>

                <!-- Results Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($restaurants as $restaurant)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow duration-300">
                            <div class="p-6">
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $restaurant['name'] }}</h3>
                                <div class="flex items-center mb-2">
                                    <span class="text-yellow-400">
                                        @for($i = 0; $i < floor($restaurant['rating'] ?? 0); $i++)
                                            ★
                                        @endfor
                                    </span>
                                    <span class="ml-2 text-sm text-gray-600">
                                        {{ $restaurant['rating'] ?? 'No rating' }}
                                        ({{ $restaurant['user_ratings_total'] ?? 0 }} reviews)
                                    </span>
                                </div>
                                <p class="text-gray-600 text-sm mb-2">{{ $restaurant['vicinity'] ?? 'No address available' }}</p>
                                @if(isset($restaurant['opening_hours']['open_now']))
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $restaurant['opening_hours']['open_now'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $restaurant['opening_hours']['open_now'] ? 'Open Now' : 'Closed' }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
