<div
    x-data="{
        open: false,
        categories: ['all', 'Concerts', 'Festivals', 'Theater', 'Sports', 'Nightlife', 'Cultural'],
        selectedCategory: 'all',
        showTooltip: null,
        searchFocused: false
    }">
    <x-barapp />
    <!-- Hero Section -->
    <div class="relative overflow-hidden bg-gradient-to-r from-blue-600 to-blue-800 text-white">
        <div class="absolute inset-0 bg-pattern opacity-10"></div>
        <div class="max-w-7xl mx-auto px-4 py-6 relative">
            <h1 class="text-2xl md:text-3xl font-bold text-center mb-[22px]">Discover Amazing Events</h1>

            <!-- Enhanced Search Bar -->
            <div class="max-w-2xl mx-auto  relative">
                <div class="relative flex items-center"
                     :class="{ 'ring-2 ring-white/50': searchFocused }">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        @focus="searchFocused = true"
                        @blur="searchFocused = false"
                        placeholder="Search for events, venues, or artists..."
                        class="w-full bg-white/10 backdrop-blur-lg text-white placeholder-gray-300 rounded-l-xl pl-12 pr-4 py-3 focus:outline-none focus:bg-white/20 transition-all duration-300"
                    >
                    <button
                        @click="open = true"
                        class="flex items-center gap-2 bg-white/20 hover:bg-white/30 transition-colors px-6 py-3 rounded-r-xl border-l border-white/10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        <span class="hidden sm:inline">Filters</span>
                    </button>
                </div>
            </div>

            <!-- Quick Filters -->
            <div class="max-w-2xl mx-auto mt-3 pt-5">
                <div class="flex justify-center flex-wrap gap-2">
                    <button class="px-3 py-1.5 text-sm bg-white/10 hover:bg-white/20 rounded-full transition-colors">Today</button>
                    <button class="px-3 py-1.5 text-sm bg-white/10 hover:bg-white/20 rounded-full transition-colors">This Weekend</button>
                    <button class="px-3 py-1.5 text-sm bg-white/10 hover:bg-white/20 rounded-full transition-colors">Free Events</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Simplified Modal -->
    <div
        x-show="open" x-cloak
        class="fixed inset-0 overflow-hidden z-50">

        <!-- Dark overlay -->
        <div
            class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
            @click="open = false">
        </div>

        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 max-w-xs w-full bg-white shadow-xl">
            <!-- Header with gradient -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-white">Filters</h2>
                    <button
                        @click="open = false"
                        class="rounded-full p-1 bg-white/10 hover:bg-white/20 transition-colors">
                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Filter Content -->
            <div class="p-6 space-y-6">
                <!-- Categories -->
                <div class="space-y-4">
                    <div class="flex items-center space-x-2">
                        <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                        </svg>
                        <h3 class="text-sm font-semibold text-gray-900">Event Category</h3>
                    </div>
                    <div class="relative">
                        <select
                            x-model="selectedCategory"
                            @change="open = false"
                            class="appearance-none w-full bg-gray-50 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <template x-for="category in categories" :key="category">
                                <option
                                    :value="category"
                                    :selected="selectedCategory === category"
                                    x-text="category"
                                    class="py-2">
                                </option>
                            </template>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Price Range -->
                <div class="space-y-4">
                    <div class="flex items-center space-x-2">
                        <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="text-sm font-semibold text-gray-900">Price Range</h3>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button class="px-4 py-2 text-sm bg-gray-50 text-gray-700 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">Free</button>
                        <button class="px-4 py-2 text-sm bg-gray-50 text-gray-700 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">Paid</button>
                        <button class="px-4 py-2 text-sm bg-gray-50 text-gray-700 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">All</button>
                    </div>
                </div>

                <!-- Date -->
                <div class="space-y-4">
                    <div class="flex items-center space-x-2">
                        <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <h3 class="text-sm font-semibold text-gray-900">Date</h3>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <button class="px-4 py-2 text-sm bg-gray-50 text-gray-700 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">Today</button>
                        <button class="px-4 py-2 text-sm bg-gray-50 text-gray-700 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">This Week</button>
                        <button class="px-4 py-2 text-sm bg-gray-50 text-gray-700 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">This Month</button>
                        <button class="px-4 py-2 text-sm bg-gray-50 text-gray-700 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">All Time</button>
                    </div>
                </div>

                <!-- Apply Filters Button -->
                <div class="pt-4">
                    <button
                        @click="open = false"
                        class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Apply Filters
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Events -->
    <div class="max-w-7xl mx-auto px-4 py-12">
        <h2 class="text-2xl font-bold mb-8"><div class="flex gap-2 items-center"><span class="italic font-light">Featured Events</span> <span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="h-5 w-5 text-yellow-400" viewBox="0 0 16 16">
            <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
          </svg></span></div></h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($events as $event)
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
                    <div class="p-6 flex flex-col h-[200px]"> <!-- Fixed height container -->
                        <!-- Price and Heart Section -->
                        <div class="flex justify-between items-center mb-4">
                            <div class="flex items-center gap-2">
                                <span class="text-blue-600 font-medium">
                                    {{ $event->price ? '€' . $event->price : 'Unknown Price' }}
                                </span>
                                <span class="text-sm text-gray-500">•</span>
                            </div>
                            <button class="text-blue-600 hover:text-blue-700 flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </button>
                        </div>

                        <!-- Description with fixed height -->
                        <div class="flex-1 mb-4">
                            <p class="text-gray-600 text-sm line-clamp-2">
                                {{$event->description ? $event->description : 'These event are provided by Ticketmaster stay tuned for news'}}
                            </p>
                        </div>

                        <!-- Date and Location with truncation and tooltips -->
                        <div class="flex items-center justify-between text-sm text-gray-500 mt-auto">
                            <div
                                class="flex items-center gap-2 min-w-0 flex-1 relative"
                                @mouseenter="showTooltip = 'date-{{$event->id}}'"
                                @mouseleave="showTooltip = null"
                                @focus="showTooltip = 'date-{{$event->id}}'"
                                @blur="showTooltip = null">
                                <svg class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="truncate" tabindex="0">{{$event->start_date}}</span>
                                <!-- Tooltip for date -->
                                <div x-cloak
                                    x-show="showTooltip === 'date-{{$event->id}}'"
                                    class="absolute bottom-full left-0 mb-2 px-3 py-2 bg-gray-900 text-white text-xs rounded-lg whitespace-nowrap z-10"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 transform -translate-y-2"
                                    x-transition:enter-end="opacity-100 transform translate-y-0">
                                    {{$event->start_date}}
                                </div>
                            </div>
                            <div
                                class="flex items-center gap-2 min-w-0 flex-1 justify-end relative"
                                @mouseenter="showTooltip = 'location-{{$event->id}}'"
                                @mouseleave="showTooltip = null"
                                @focus="showTooltip = 'location-{{$event->id}}'"
                                @blur="showTooltip = null">
                                <svg class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                </svg>
                                <span class="truncate" tabindex="0">{{$event->location}}</span>
                                <!-- Tooltip for location -->
                                <div x-cloak
                                    x-show="showTooltip === 'location-{{$event->id}}'"
                                    class="absolute bottom-full right-0 mb-2 px-3 py-2 bg-gray-900 text-white text-xs rounded-lg whitespace-nowrap z-10"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 transform -translate-y-2"
                                    x-transition:enter-end="opacity-100 transform translate-y-0">
                                    {{$event->location}}
                                </div>
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


