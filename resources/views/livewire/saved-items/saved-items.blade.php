<div class="flex flex-col min-h-screen bg-gradient-to-br from-blue-100 via-purple-100 to-pink-100 font-sans"
    x-data="{
        activeTab: 'events',
        showScrollTop: false,
        scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        },
        init() {
            window.addEventListener('scroll', () => {
                this.showScrollTop = window.pageYOffset > 300;
            });
        }
    }" x-init="init()" @scroll.window="showScrollTop = window.pageYOffset > 300">
    <x-barapp />

    <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0 opacity-10">
        <div class="absolute top-0 left-0 w-full h-full bg-repeat"
            style="background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI2MCIgaGVpZ2h0PSI2MCI+CjxyZWN0IHdpZHRoPSI2MCIgaGVpZ2h0PSI2MCIgZmlsbD0iI2ZmZiIvPgo8cGF0aCBkPSJNMzYgMzZjLTUuNSAwLTEwLTQuNS0xMC0xMHM0LjUtMTAgMTAtMTAgMTAgNC41IDEwIDEwLTQuNSAxMC0xMCAxMHptMC0xN2MtMy45IDAtNyAzLjEtNyA3czMuMSA3IDcgNyA3LTMuMSA3LTctMy4xLTctNy03eiIgZmlsbD0iIzAwMCIgZmlsbC1vcGFjaXR5PSIuMDUiLz4KPC9zdmc+');">
        </div>
    </div>

    <main class="container mx-auto px-4 py-4 relative z-10 flex-grow">
        <div class="flex flex-col items-center mb-6">
            <div class="flex items-center justify-center mb-2">
                <svg class="w-14 h-14 text-red-500 animate-pulse" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                    viewBox="0 0 24 24">
                    <path
                        d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                    </path>
                </svg>
            </div>
            <h1
                class="text-4xl md:text-5xl font-extrabold text-center text-gray-800 bg-clip-text text-transparent bg-gradient-to-r from-purple-500 to-pink-500 mb-3">
                Your Global Adventures</h1>
            <p class="text-lg text-gray-600 text-center max-w-2xl">Explore your favorite places and upcoming events from
                around the world.</p>
        </div>

        <!-- Improved Tab switcher -->
        <div class="flex justify-center mb-8">
            <div class="bg-white p-1 rounded-full shadow-lg">
                <button @click="activeTab = 'places'"
                    :class="{ 'bg-green-500 text-white': activeTab === 'places', 'text-gray-700 hover:bg-green-100': activeTab !== 'places' }"
                    class="px-6 py-3 rounded-full font-semibold transition duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Saved Places
                    </span>
                </button>
                <button @click="activeTab = 'events'"
                    :class="{ 'bg-red-500 text-white': activeTab === 'events', 'text-gray-700 hover:bg-red-100': activeTab !== 'events' }"
                    class="px-6 py-3 rounded-full font-semibold transition duration-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        Saved Events
                    </span>
                </button>
            </div>
        </div>

        <!-- Saved Places -->
        <div x-show="activeTab === 'places'" x-cloak x-transition:enter="transition ease-out duration-300">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($savedPlaces as $place)
                    <div
                        class="bg-white rounded-lg shadow-lg overflow-hidden transform transition duration-500 hover:scale-105 hover:shadow-xl relative">
                        <!-- Remove heart button - ažurirani kod unutar card elementa -->
                        <button onclick="confirmRemoval({{ $place['id'] }}, 'place')"
                            class="absolute top-4 right-4 z-10 bg-white bg-opacity-75 p-2 rounded-full shadow-lg hover:bg-opacity-100 transition-all duration-300">
                            <svg class="w-6 h-6 text-red-500 hover:scale-110 transition-transform" viewBox="0 0 24 24"
                                fill="currentColor">
                                <path
                                    d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                            </svg>
                        </button>

                        <div class="relative h-64">
                            <img src="{{ $place['image'] }}" alt="{{ $place['name'] }}"
                                class="w-full h-full object-cover">
                            <div
                                class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity duration-300">
                                <button
                                    class="bg-white text-gray-800 px-4 py-2 rounded-full font-semibold hover:bg-gray-100 transition duration-300">View
                                    More</button>
                            </div>
                        </div>
                        <div class="p-6">
                            <h2 class="text-2xl font-bold mb-2 text-gray-800 overflow-hidden whitespace-nowrap overflow-ellipsis"
                                title="{{ $place['name'] }}">
                                {{ $place['name'] }}
                            </h2>
                            <div class="flex items-center gap-2 text-gray-600 mb-4">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="inline-block max-w-[200px] truncate" title="{{ $place['location'] }}">
                                    {{ $place['location'] }}
                                </span>
                            </div>

                            <!-- Add rating display -->
                            @if ($place['rating'])
                                <div class="flex items-center mb-3">
                                    <div class="flex text-yellow-400">
                                        @for ($i = 0; $i < 5; $i++)
                                            @if ($i < floor($place['rating']))
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            @else
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                                </svg>
                                            @endif
                                        @endfor
                                        <span class="ml-2 text-gray-600">{{ number_format($place['rating'], 1) }}</span>
                                    </div>
                                </div>
                            @endif

                            <div class="flex flex-wrap gap-2">
                                @if ($place['category'])
                                    @foreach (array_slice($place['category'], 0, 3) as $category)
                                        <span
                                            class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium">
                                            {{ $category }}
                                        </span>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-8">
                        <p class="text-gray-500">No saved places yet.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Saved Events -->
        <div x-show="activeTab === 'events'" x-cloak x-transition:enter="transition ease-out duration-300">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($savedEvents as $event)
                    <div
                        class="bg-white rounded-lg shadow-lg overflow-hidden transform transition duration-500 hover:scale-105 hover:shadow-xl relative">
                        <!-- Remove heart button - ažurirani kod unutar card elementa -->
                        <button onclick="confirmRemoval({{ $event['id'] }}, 'event')"
                            class="absolute top-4 right-4 z-10 bg-white bg-opacity-75 p-2 rounded-full shadow-lg hover:bg-opacity-100 transition-all duration-300">
                            <svg class="w-6 h-6 text-red-500 hover:scale-110 transition-transform" viewBox="0 0 24 24"
                                fill="currentColor">
                                <path
                                    d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                            </svg>
                        </button>

                        <div class="relative h-64">
                            <img src="{{ $event['image'] }}" alt="{{ $event['name'] }}"
                                class="w-full h-full object-cover">
                            <div
                                class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity duration-300">
                                <a href="{{ $event['url'] }}" target="_blank"
                                    class="bg-white text-gray-800 px-4 py-2 rounded-full font-semibold hover:bg-gray-100 transition duration-300">
                                    {{ match ($event['price_display']) {
                                        'Free' => 'Book for Free',
                                        'Price not fixed' => 'More Info',
                                        default => 'Book a Ticket',
                                    } }}
                                </a>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-3">
                                <h2 class="text-2xl font-bold text-gray-800 truncate" title="{{ $event['name'] }}">
                                    {{ $event['name'] }}
                                </h2>
                                <div class="flex items-center gap-2 text-gray-600">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span class="truncate"
                                        title="{{ $event['location'] }}">{{ $event['location'] }}</span>
                                </div>
                                <p class="text-red-600 font-semibold flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ $event['date'] ? \Carbon\Carbon::parse($event['date'])->format('F j, Y') : 'Date TBA' }}
                                </p>

                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2 text-gray-500">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                        <span>{{ $event['category'] }}</span>
                                    </div>
                                    <span @class([
                                        'font-semibold',
                                        'text-green-600' => $event['is_free'],
                                        'text-gray-900' => !$event['is_free'],
                                    ])>
                                        {{ $event['price_display'] }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-8">
                        <p class="text-gray-500">No saved events yet.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Scroll to top button -->
    </main>

    <div class="mt-auto">
        <x-footer />
        <x-AIchat />
    </div>
</div>

<script>
    window.confirmRemoval = async function(id, type) {
        const result = await Swal.fire({
            title: 'Are you sure?',
            text: `Do you want to remove this ${type}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Yes, remove it!',
            cancelButtonText: 'Cancel'
        });

        if (result.isConfirmed) {
            @this.call('removeItem', id, type);
            Swal.fire(
                'Removed!',
                `The ${type} has been removed.`,
                'success'
            );
        }
    }
</script>
