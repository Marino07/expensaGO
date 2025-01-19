<div x-data="{
    selectedCategory: 'all',
    searchQuery: '',
    categories: ['all', 'Concerts', 'Festivals', 'Theater', 'Sports', 'Nightlife', 'Cultural'],

}">
    <x-barapp />
    <!-- Hero Section -->
    <div class="relative overflow-hidden bg-gradient-to-b from-blue-900 to-blue-800 text-white">
        <div class="absolute inset-0 bg-pattern opacity-10"></div>
        <div class="max-w-7xl mx-auto px-4 py-6 relative">
            <!-- Search Bar -->
            <div class="max-w-2xl mx-auto mb-4 flex gap-4 bg-white/10 backdrop-blur-lg p-2 rounded-lg">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search events..."
                    class="form-control w-full bg-white/80 backdrop-blur-sm text-gray-900 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                <button wire:click="search" class="bg-white text-blue-900 px-6 py-2 rounded-lg font-semibold hover:bg-blue-50 transition-colors">
                    Search
                </button>
            </div>
        </div>
    </div>

    <!-- Category Tabs -->
    <div class="bg-white sticky top-0 z-10 shadow-sm">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex gap-2 overflow-x-auto py-4">
                <template x-for="category in categories" :key="category">
                    <button
                        @click="selectedCategory = category"
                        :class="{'bg-blue-600 text-white': selectedCategory === category,
                                'bg-gray-100 text-gray-700 hover:bg-gray-200': selectedCategory !== category}"
                        class="px-4 py-2 rounded-full text-sm font-medium transition-colors">
                        <span x-text="category"></span>
                    </button>
                </template>
            </div>
        </div>

    </div>

    <!-- Featured Events -->
    <div class="max-w-7xl mx-auto px-4 py-12">
        <h2 class="text-2xl font-bold mb-8">Featured Events</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($events as $event )
                <div class="group relative bg-white rounded-xl shadow-lg overflow-hidden transform hover:-translate-y-1 transition-all duration-300">
                    <div class="absolute top-4 left-4 z-10">
                        <span class="bg-yellow-400 text-yellow-900 text-xs font-bold px-3 py-1 rounded-full">FEATURED</span>
                    </div>
                    <div class="relative h-48">
                        <img src="{{$event->image_url ? $event->image_url : asset('events.jpeg') }}" alt="event"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        <div class="absolute bottom-4 left-4 text-white">
                            <p class="text-sm font-medium" x-text="event.category"></p>
                            <h3 class="text-lg font-bold" >{{$event->name}}</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <div class="flex items-center gap-2">
                                <span class="text-blue-600">
                                    {{ $event->price ? '€' . $event->price : 'Free' }}
                                </span>
                                <span class="text-sm text-gray-500">•</span>
                            </div>
                            <button class="text-blue-600 hover:text-blue-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </button>
                        </div>
                        <p class="text-gray-600 text-sm line-clamp-2 mb-4" >{{$event->description ? $event->description : 'These event are provided by Ticketmaster stay tuned for news'}}</p>
                        <div class="flex items-center justify-between text-sm text-gray-500">
                            <div class="flex items-center gap-2">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>{{$event->start_date}}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                </svg>
                                <span>{{$event->location}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @if(!empty($events))
        <div class="mt-6 flex justify-center">
            {{ $events->links('pagination::tailwind') }}
        </div>
        @endif
    </div>

</div>
