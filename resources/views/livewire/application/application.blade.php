<div x-data="{ openFirst: {{ $openFirst }} }" class="flex flex-col min-h-screen bg-gradient-to-br from-indigo-50 via-white to-pink-50">
    @if (session()->has('message'))
        <div x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-90"
            x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-90"
            class=" bg-gradient-to-l from-blue-50 to-cyan-300 border-l-4 border-blue-500 p-4 rounded-md shadow-lg"
            role="alert">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-medium text-green-800">
                        Trip created successfully! 🎉
                    </h3>
                    <div class="mt-2 text-sm text-green-700">
                        <p>{{ session('message') }}</p>
                    </div>
                    <div class="mt-4">
                        <div class="-mx-2 -my-1.5 flex">
                            <button @click="show = false" type="button"
                                class="bg-cyan-300 px-2 py-1.5 rounded-md text-sm font-medium text-green-800 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-green-50 focus:ring-green-600">
                                Dismiss
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($lastFiveExpenses && count($lastFiveExpenses) > 0)
        <!-- Zamijenite postojeći Transaction Slider dio s ovim: -->
        <div class="w-full">
            <div x-data="transactionSlider()"
                class="bg-gradient-to-r from-blue-400 to-cyan-500 rounded-lg shadow-lg overflow-hidden">
                <div class="relative h-16">
                    <div class="absolute inset-0">
                        <div class="slider-container overflow-hidden w-full h-full">
                            <div class="animate-slide-left flex items-center space-x-6 px-4 absolute right-0 top-1/2 transform -translate-y-1/2"
                                x-ref="slider">
                                <template x-if="transactions.length > 0">
                                    <template
                                        x-for="transaction in transactions.filter(t => t !== undefined && t !== null)"
                                        :key="transaction.id">
                                        <div
                                            class="inline-flex items-center space-x-2 bg-white bg-opacity-20 rounded-full px-4 py-2 transition-all duration-300 hover:bg-opacity-30 shadow-md hover:shadow-lg whitespace-nowrap">
                                            <span x-html="getIcon(transaction.category)"></span>
                                            <span class="text-sm font-medium text-white"
                                                x-text="transaction.type"></span>
                                            <span class="text-sm font-bold text-white"
                                                x-text="'$' + transaction.amount.toFixed(2)"></span>
                                        </div>
                                    </template>
                                </template>
                                <template x-if="transactions.length === 0">
                                    <div
                                        class="inline-flex items-center space-x-2 bg-white bg-opacity-20 rounded-full px-4 py-2">
                                        <svg class="w-4 h-4 text-white" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10" />
                                            <path d="M12 16v-4" />
                                            <path d="M12 8h.01" />
                                        </svg>
                                        <span class="text-sm font-medium text-white">No recent transactions</span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <x-barapp />

    <main class="flex-grow container mx-auto my-3 px-4 sm:px-6 lg:px-8 py-8 bg-blue-50 rounded-lg shadow-inner">
        <div x-show="openFirst">
            <livewire:components.first-visit-questionnaire />
        </div>
        @if ($trip)
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                <!-- Total Expenses Card -->
                <div class="bg-gradient-to-br from-blue-400 to-indigo-600 rounded-lg shadow-lg overflow-hidden">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-white rounded-md p-3">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                    class="text-indigo-600 h-6 w-6" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M0 0h1v15h15v1H0zm10 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-1 0V4.9l-3.613 4.417a.5.5 0 0 1-.74.037L7.06 6.767l-3.656 5.027a.5.5 0 0 1-.808-.588l4-5.5a.5.5 0 0 1 .758-.06l2.609 2.61L13.445 4H10.5a.5.5 0 0 1-.5-.5" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-100 truncate">
                                        See all analytics with ease
                                    </dt>
                                    <dd class="text-3xl font-semibold text-white">
                                        For Free
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="bg-indigo-600 px-4 py-4 sm:px-6">
                        <div class="text-sm">
                            <a href="{{ route('analytics') }}"
                                class="font-medium text-white hover:text-indigo-100 transition ease-in-out duration-150">
                                Detail analytics of expenses &rarr;
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Active Trips Card -->
                <div class="bg-gradient-to-br from-blue-300 to-indigo-500 rounded-lg shadow-lg overflow-hidden">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-white rounded-md p-3">
                                <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <div class="flex justify-between text-sm font-medium text-gray-100">
                                        <dt>Active Trip</dt>
                                        <dt>Expenses: <span
                                                class="{{ $AllExpenses > $trip->budget ? 'text-red-600' : 'text-green-200' }}">{{ $AllExpenses }}</span>
                                        </dt>
                                    </div>
                                    <dd class="mt-1 text-3xl font-semibold text-white">
                                        {{ $trip->location }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="bg-green-600 px-4 py-4 sm:px-6">
                        <div class="text-sm">
                            <a href="{{ route('manage-trips') }}"
                                class="font-medium text-white hover:text-green-100 transition ease-in-out duration-150">
                                Manage trips &rarr;
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Pending Reports Card -->
                <div class="bg-gradient-to-br from-red-200 to-pink-400 rounded-lg shadow-lg overflow-hidden">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-white rounded-md p-3">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-500">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-100 truncate">
                                        Saved Places & Events
                                    </dt>
                                    <dd class="text-3xl font-semibold text-white">
                                        {{ $countSavedItems }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="bg-red-500 px-4 py-4 sm:px-6">
                        <div class="text-sm">
                            <a  href="{{ route('saved-items') }}"
                                class="font-medium text-white hover:text-red-100 transition ease-in-out duration-150">
                                View Saved Items &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div
                class="bg-gradient-to-r from-blue-100 to-indigo-100 rounded-xl p-6 mb-6 shadow-lg border border-blue-100">
                <div class="max-w-2xl mx-auto text-center">
                    <h1 class="text-4xl font-bold text-gray-900 mb-6">Welcome to Expensago! ✈️</h1>
                    <div class="space-y-6">
                        <h2 class="text-2xl font-semibold text-gray-800">
                            Start Your Journey
                        </h2>
                        <p class="text-lg text-gray-600">
                            Effortlessly track expenses, plan trips, and manage your finances. Create your first trip,
                            connect your bank account, and get personalized insights for seamless financial management.
                        </p>
                    </div>
                </div>
            </div>
        @endif


            {{-- Commented out original charts
            <div class="mt-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">Expense Analytics</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Bar Chart -->
                    <div class="bg-white shadow-lg rounded-lg overflow-hidden border border-blue-100 p-4">
                        <canvas id="expenseChart" class="w-full" style="height: 300px;"></canvas>
                    </div>
                    <!-- Doughnut Chart -->
                    <div class="bg-white shadow-lg rounded-lg overflow-hidden border border-blue-100 p-4">
                        <canvas id="expenseChart2" class="w-full" style="height: 300px;"></canvas>
                    </div>
                </div>
            </div>
            --}}

        @if($suggestPlaces && $suggestEvents)
        <div x-data="imageSlider" x-init="initialize()" @load.window="stathrortObserver"
        @beforeunload.window="cleanup" class="mt-8 p-2 bg-blue-50">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-[2rem] max-w-9xl ">
            <!-- Places Card -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden group">
                <div class="relative h-[18rem]" x-ref="placesCard">
                    <!-- Place Marker -->
                    <div class="absolute top-4 left-4 z-10 bg-white/30 backdrop-blur-sm px-3 py-1 rounded-full flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="text-white text-sm font-medium">Places</span>
                    </div>

                    <template x-for="(image, index) in placesImages" :key="index">
                        <img :src="image.image" :alt="image.title"
                            class="absolute inset-0 w-full h-full object-cover transition-opacity duration-1000"
                            :class="{
                                'opacity-100': currentPlacesIndex === index,
                                'opacity-0': currentPlacesIndex !== index
                            }" />
                    </template>
                    <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center transition-opacity duration-300 opacity-100 group-hover:opacity-0">
                        <h3 class="text-white text-3xl font-bold px-4 truncate max-w-full" x-text="placesImages[currentPlacesIndex].title"></h3>
                    </div>
                    <div class="absolute inset-0 bg-black bg-opacity-60 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <h3 class="text-white text-3xl font-bold mb-4 px-4 truncate max-w-full" x-text="placesImages[currentPlacesIndex].title"></h3>
                        <p class="text-white text-center px-6 mb-4">Discover iconic landmarks and hidden gems in London.</p>
                        <a :href="'https://www.google.com/maps/search/?api=1&query=' + encodeURIComponent(placesImages[currentPlacesIndex].title + ' ' + placesImages[currentPlacesIndex].location)"
                           target="_blank"
                           class="px-6 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-full transform transition-all duration-300 hover:scale-105 flex items-center space-x-2">
                           <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M6.672 1.911a1 1 0 10-1.932.518l.259.966a1 1 0 001.932-.518l-.26-.966zM2.429 4.74a1 1 0 10-.517 1.932l.966.259a1 1 0 00.517-1.932l-.966-.26zm8.814-.569a1 1 0 00-1.415-1.414l-.707.707a1 1 0 101.415 1.415l.707-.708zm-7.071 7.072l.707-.707A1 1 0 003.465 9.12l-.708.707a1 1 0 001.415 1.415zm3.2-5.171a1 1 0 00-1.3 1.3l4 10a1 1 0 001.823.075l1.38-2.759 3.018 3.02a1 1 0 001.414-1.415l-3.019-3.02 2.76-1.379a1 1 0 00-.076-1.822l-10-4z" clip-rule="evenodd" />
                        </svg>
                            <span>View place</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Events Card -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden group">
                <div class="relative h-[18rem]" x-ref="eventsCard">
                    <!-- Event Marker -->
                    <div class="absolute top-4 left-4 z-10 bg-white/30 backdrop-blur-sm px-3 py-1 rounded-full flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="text-white text-sm font-medium">Events</span>
                    </div>

                    <template x-for="(image, index) in eventsImages" :key="index">
                        <img :src="image.image" :alt="image.title"
                            class="absolute inset-0 w-full h-full object-cover transition-opacity duration-1000"
                            :class="{
                                'opacity-100': currentEventsIndex === index,
                                'opacity-0': currentEventsIndex !== index
                            }" />
                    </template>
                    <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center transition-opacity duration-300 opacity-100 group-hover:opacity-0">
                        <h3 class="text-white text-3xl font-bold px-4 truncate max-w-full" x-text="eventsImages[currentEventsIndex].title"></h3>
                    </div>
                    <div class="absolute inset-0 bg-black bg-opacity-60 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <h3 class="text-white text-3xl font-bold mb-4 px-4 truncate max-w-full" x-text="eventsImages[currentEventsIndex].title"></h3>
                        <p class="text-white text-center px-6 mb-4">Experience exciting events and festivals in the city.</p>
                        <a :href="eventsImages[currentEventsIndex].url"
                           target="_blank"
                           class="px-6 py-2 bg-green-500 hover:bg-green-600 text-white rounded-full transform transition-all duration-300 hover:scale-105 flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M6.672 1.911a1 1 0 10-1.932.518l.259.966a1 1 0 001.932-.518l-.26-.966zM2.429 4.74a1 1 0 10-.517 1.932l.966.259a1 1 0 00.517-1.932l-.966-.26zm8.814-.569a1 1 0 00-1.415-1.414l-.707.707a1 1 0 101.415 1.415l.707-.708zm-7.071 7.072l.707-.707A1 1 0 003.465 9.12l-.708.707a1 1 0 001.415 1.415zm3.2-5.171a1 1 0 00-1.3 1.3l4 10a1 1 0 001.823.075l1.38-2.759 3.018 3.02a1 1 0 001.414-1.415l-3.019-3.02 2.76-1.379a1 1 0 00-.076-1.822l-10-4z" clip-rule="evenodd" />
                            </svg>
                            <span>Get Tickets</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
       {{-- New Travel Gallery Slider --}}
       <div class="mt-8 p-4 bg-gray-50">
        <h2 class="text-3xl font-bold text-gray-800 mb-6">Worth to see</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- City Break Card -->
            <div
                class="bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl hover:scale-105">
                <div class="relative">
                    <img src="{{asset('images/defaults/paris2.jpg')}}" alt="City Break"
                        class="w-full h-48 object-cover" />
                    <div
                        class="absolute top-0 left-0 bg-blue-500 text-white px-3 py-1 rounded-br-lg text-sm font-semibold">
                        Popular
                    </div>
                </div>
                <div class="p-5">
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">City Break in Paris</h3>
                    <p class="text-gray-600 mb-4">Experience the charm of the City of Light</p>
                    <div class="flex justify-between items-center">
                        <span class="text-blue-500 font-bold">€750 avg/person</span>
                        <a  href="{{route('start-trip')}}"
                            class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-full transition duration-300 flex items-center">
                            Start Trip
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Beach Getaway Card -->
            <div
                class="bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl hover:scale-105">
                <div class="relative">
                    <img src="{{asset('/images/defaults/balib.jpg')}}"
                        alt="Beach Getaway" class="w-full h-48 object-cover" />
                    <div
                        class="absolute top-0 left-0 bg-green-500 text-white px-3 py-1 rounded-br-lg text-sm font-semibold">
                        Best Value
                    </div>
                </div>
                <div class="p-5">
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Bali Beach Retreat</h3>
                    <p class="text-gray-600 mb-4">Relax on pristine beaches and explore rich culture</p>
                    <div class="flex justify-between items-center">
                        <span class="text-green-500 font-bold">€900 avg/person</span>
                        <a  href="{{ route('start-trip') }}"
                            class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-full transition duration-300 flex items-center">
                            Start Trip
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @endif
        <!-- Quick Actions -->
        <div class="mt-8">
            <h2 class="text-2xl font-semibold text-gray-900 mb-4">Quick Actions</h2>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div
                    class="relative group bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-blue-500 rounded-lg shadow-lg hover:bg-blue-50 transition-all duration-200">
                    <div>
                        <span class="rounded-lg inline-flex p-3 bg-blue-100 text-blue-700 ring-4 ring-white">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </span>
                    </div>
                    <div class="mt-8">
                        <h3 class="text-lg font-medium">
                            <a wire:navigate href="{{ route('new-expense') }}" class="focus:outline-none">
                                <span class="absolute inset-0" aria-hidden="true"></span>
                                Add New Expense
                            </a>
                        </h3>
                        <p class="mt-2 text-sm text-gray-500">
                            Quickly log a new expense for your current trip.
                        </p>
                    </div>
                    <span class="pointer-events-none absolute top-6 right-6 text-gray-300 group-hover:text-indigo-400"
                        aria-hidden="true">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                d="M20 4h1a1 1 0 00-1-1v1zm-1 12a1 1 0 102 0h-2zM8 3a1 1 0 000 2V3zM3.293 19.293a1 1 0 101.414 1.414l-1.414-1.414zM19 4v12h2V4h-2zm1-1H8v2h12V3zm-.707.293l-16 16 1.414 1.414 16-16-1.414-1.414z" />
                        </svg>
                    </span>
                </div>

                <div
                    class="relative group bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-blue-500 rounded-lg shadow-lg hover:bg-blue-50 transition-all duration-200">
                    <div>
                        <span class="rounded-lg inline-flex p-3 bg-blue-100 text-blue-700 ring-4 ring-white">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </span>
                    </div>
                    <div class="mt-8">
                        <h3 class="text-lg font-medium">
                            <a  href="{{ route('start-trip') }}" class="focus:outline-none">
                                <span class="absolute inset-0" aria-hidden="true"></span>
                                Start New Trip
                            </a>
                        </h3>
                        <p class="mt-2 text-sm text-gray-500">
                            Begin tracking expenses for a new journey.
                        </p>
                    </div>
                    <span class="pointer-events-none absolute top-6 right-6 text-gray-300 group-hover:text-green-400"
                        aria-hidden="true">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                d="M20 4h1a1 1 0 00-1-1v1zm-1 12a1 1 0 102 0h-2zM8 3a1 1 0 000 2V3zM3.293 19.293a1 1 0 101.414 1.414l-1.414-1.414zM19 4v12h2V4h-2zm1-1H8v2h12V3zm-.707.293l-16 16 1.414 1.414 16-16-1.414-1.414z" />
                        </svg>
                    </span>
                </div>
                <div id="plaidon"
                    class="relative group bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-blue-500 rounded-lg shadow-lg hover:bg-blue-50 transition-all duration-200">
                    <div>
                        <span class="rounded-lg inline-flex p-3 bg-blue-100 text-blue-700 ring-4 ring-white">
                            <svg class="w-6 h-6 {{ auth()->user()->plaid_access_token ? 'text-green-400' : 'text-orange-700' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>

                        </span>
                    </div>
                    <div class="mt-8">
                        <h3 class="text-lg font-medium pb-2">
                            Bank account
                        </h3>
                    </div>
                    @auth
                        @if (!auth()->user()->plaid_access_token)
                            <button @click="initPlaid" class="text-orange-700  font-medium">
                                Connect Now →
                            </button>
                        @else
                            <span class="text-sm font-medium text-green-700 flex items-center">
                                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-base">Connected</span>
                            </span>
                        @endif
                    @endauth
                    <span class="pointer-events-none absolute top-6 right-6 text-gray-300 group-hover:text-yellow-400"
                        aria-hidden="true">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                d="M20 4h1a1 1 0 00-1-1v1zm-1 12a1 1 0 102 0h-2zM8 3a1 1 0 000 2V3zM3.293 19.293a1 1 0 101.414 1.414l-1.414-1.414zM19 4v12h2V4h-2zm1-1H8v2h12V3zm-.707.293l-16 16 1.414 1.414 16-16-1.414-1.414z" />
                        </svg>
                    </span>
                </div>
                @if ($trip)
                    <div
                        class="relative group bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-blue-500 rounded-lg shadow-lg hover:bg-blue-50 transition-all duration-200">
                        <div>
                            <span class="rounded-lg inline-flex p-3 bg-blue-100 text-blue-700 ring-4 ring-white">

                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M14.25 6.087c0-.355.186-.676.401-.959.221-.29.349-.634.349-1.003 0-1.036-1.007-1.875-2.25-1.875s-2.25.84-2.25 1.875c0 .369.128.713.349 1.003.215.283.401.604.401.959v0a.64.64 0 0 1-.657.643 48.39 48.39 0 0 1-4.163-.3c.186 1.613.293 3.25.315 4.907a.656.656 0 0 1-.658.663v0c-.355 0-.676-.186-.959-.401a1.647 1.647 0 0 0-1.003-.349c-1.036 0-1.875 1.007-1.875 2.25s.84 2.25 1.875 2.25c.369 0 .713-.128 1.003-.349.283-.215.604-.401.959-.401v0c.31 0 .555.26.532.57a48.039 48.039 0 0 1-.642 5.056c1.518.19 3.058.309 4.616.354a.64.64 0 0 0 .657-.643v0c0-.355-.186-.676-.401-.959a1.647 1.647 0 0 1-.349-1.003c0-1.035 1.008-1.875 2.25-1.875 1.243 0 2.25.84 2.25 1.875 0 .369-.128.713-.349 1.003-.215.283-.4.604-.4.959v0c0 .333.277.599.61.58a48.1 48.1 0 0 0 5.427-.63 48.05 48.05 0 0 0 .582-4.717.532.532 0 0 0-.533-.57v0c-.355 0-.676.186-.959.401-.29.221-.634.349-1.003.349-1.035 0-1.875-1.007-1.875-2.25s.84-2.25 1.875-2.25c.37 0 .713.128 1.003.349.283.215.604.401.96.401v0a.656.656 0 0 0 .658-.663 48.422 48.422 0 0 0-.37-5.36c-1.886.342-3.81.574-5.766.689a.578.578 0 0 1-.61-.58v0Z" />
                                </svg>

                            </span>
                        </div>
                        <div class="mt-8">
                            <h3 class="text-lg font-medium">
                                <a href="{{ route('trip-planner', ['trip' => $trip->id]) }}"
                                    class="focus:outline-none">
                                    <span class="absolute inset-0" aria-hidden="true"></span>
                                    @if (!$trip->planner)
                                        Generate Trip Plan
                                    @else
                                        Trip Planner
                                    @endif

                                </a>
                            </h3>
                            <p class="mt-2 text-sm text-gray-500">
                                @if (!$trip->planner)
                                    Create an AI-powered plan for your trip.
                                @else
                                    Unlocking planner days every few days
                                @endif
                            </p>
                        </div>
                        <span
                            class="pointer-events-none absolute top-6 right-6 text-gray-300 group-hover:text-purple-400"
                            aria-hidden="true">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    d="M20 4h1a1 1 0 00-1-1v1zm-1 12a1 1 0 102 0h-2zM8 3a1 1 0 000 2V3zM3.293 19.293a1 1 0 101.414 1.414l-1.414-1.414zM19 4v12h2V4h-2zm1-1H8v2h12V3zm-.707.293l-16 16 1.414 1.414 16-16-1.414-1.414z" />
                            </svg>
                        </span>
                    </div>
                    <div
                        class="relative group bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-blue-500 rounded-lg shadow-lg hover:bg-blue-50 transition-all duration-200">
                        <div>
                            <span class="rounded-lg inline-flex p-3 bg-blue-100 text-blue-700 ring-4 ring-white">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                                </svg>

                            </span>
                        </div>
                        <div class="mt-8">
                            <h3 class="text-lg font-medium">
                                <a href="{{ route('events') }}" class="focus:outline-none">
                                    <span class="absolute inset-0" aria-hidden="true"></span>
                                    Events
                                </a>
                            </h3>
                            <p class="mt-2 text-sm text-gray-500">
                                Events near you
                            </p>
                        </div>
                        <span
                            class="pointer-events-none absolute top-6 right-6 text-gray-300 group-hover:text-purple-400"
                            aria-hidden="true">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    d="M20 4h1a1 1 0 00-1-1v1zm-1 12a1 1 0 102 0h-2zM8 3a1 1 0 000 2V3zM3.293 19.293a1 1 0 101.414 1.414l-1.414-1.414zM19 4v12h2V4h-2zm1-1H8v2h12V3zm-.707.293l-16 16 1.414 1.414 16-16-1.414-1.414z" />
                            </svg>
                        </span>
                    </div>
                    <div
                        class="relative group bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-blue-500 rounded-lg shadow-lg hover:bg-blue-50 transition-all duration-200">
                        <div>
                            <span class="rounded-lg inline-flex p-3 bg-blue-100 text-blue-700 ring-4 ring-white">

                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m20.893 13.393-1.135-1.135a2.252 2.252 0 0 1-.421-.585l-1.08-2.16a.414.414 0 0 0-.663-.107.827.827 0 0 1-.812.21l-1.273-.363a.89.89 0 0 0-.738 1.595l.587.39c.59.395.674 1.23.172 1.732l-.2.2c-.212.212-.33.498-.33.796v.41c0 .409-.11.809-.32 1.158l-1.315 2.191a2.11 2.11 0 0 1-1.81 1.025 1.055 1.055 0 0 1-1.055-1.055v-1.172c0-.92-.56-1.747-1.414-2.089l-.655-.261a2.25 2.25 0 0 1-1.383-2.46l.007-.042a2.25 2.25 0 0 1 .29-.787l.09-.15a2.25 2.25 0 0 1 2.37-1.048l1.178.236a1.125 1.125 0 0 0 1.302-.795l.208-.73a1.125 1.125 0 0 0-.578-1.315l-.665-.332-.091.091a2.25 2.25 0 0 1-1.591.659h-.18c-.249 0-.487.1-.662.274a.931.931 0 0 1-1.458-1.137l1.411-2.353a2.25 2.25 0 0 0 .286-.76m11.928 9.869A9 9 0 0 0 8.965 3.525m11.928 9.868A9 9 0 1 1 8.965 3.525" />
                                </svg>

                            </span>
                        </div>
                        <div class="mt-8">
                            <h3 class="text-lg font-medium">
                                <a href="{{ route('places') }}" class="focus:outline-none">
                                    <span class="absolute inset-0" aria-hidden="true"></span>
                                    Local Tips
                                </a>
                            </h3>
                            <p class="mt-2 text-sm text-gray-500">
                                Recommended places and more
                            </p>
                        </div>
                        <span
                            class="pointer-events-none absolute top-6 right-6 text-gray-300 group-hover:text-purple-400"
                            aria-hidden="true">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    d="M20 4h1a1 1 0 00-1-1v1zm-1 12a1 1 0 102 0h-2zM8 3a1 1 0 000 2V3zM3.293 19.293a1 1 0 101.414 1.414l-1.414-1.414zM19 4v12h2V4h-2zm1-1H8v2h12V3zm-.707.293l-16 16 1.414 1.414 16-16-1.414-1.414z" />
                            </svg>
                        </span>
                    </div>

                @endif
            </div>
        </div>
    </main>
    <x-footer />
    <!-- AI Chat Button and Modal -->
    <x-AIchat />
</div>

@push('scripts')
    <script>
        function transactionSlider() {
            return {
                transactions: @json($lastFiveExpenses) || [],
                getIcon(category) {
                    if (!category) category = 'Other';

                    const icons = {
                        'Food': '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" class="w-4 h-4 text-yellow-300" x="0px" y="0px" viewBox="0 0 122.88 121.87" style="enable-background:new 0 0 122.88 121.87" xml:space="preserve" fill="yellow"><g><path d="M97.34,0.74c0.86-0.93,2.3-0.99,3.23-0.13c0.93,0.86,0.99,2.3,0.13,3.23L81.98,24.1l-0.03,0.04 c-2.29,2.77-3.86,5.33-4.56,7.67c-0.62,2.07-0.53,3.95,0.39,5.59c0.49,0.88,0.33,1.96-0.32,2.67l0,0l-8.89,9.62 c-0.87-0.95-1.56-1.72-2.02-2.22c-0.21-0.28-0.45-0.55-0.7-0.81l-0.02,0.02c-0.12-0.13-0.25-0.25-0.38-0.37l7.6-8.23 c-0.89-2.38-0.88-4.91-0.06-7.6c0.88-2.92,2.75-6.03,5.44-9.27c0.06-0.08,0.11-0.16,0.18-0.23L97.32,0.72L97.34,0.74L97.34,0.74z M57.13,55.01c-0.84-0.94-0.76-2.39,0.18-3.23c0.94-0.84,2.39-0.76,3.23,0.18c9.41,10.54,38.5,41.73,46.56,53.39 c10.63,15.05-5.83,19.79-11.29,14.31c-13.64-13.19-42.6-46.82-55.33-61.08c-4.58,1.94-9.03,2.24-13.5,0.96 c-4.81-1.37-9.52-4.58-14.3-9.51l-0.06-0.06c-3.64-3.84-6.49-7.63-8.55-11.38c-2.11-3.86-3.4-7.68-3.86-11.47 c-0.49-4.08-0.11-7.88,0.99-11.25c1.29-3.96,3.58-7.31,6.58-9.8c3.02-2.5,6.73-4.12,10.87-4.62c3.44-0.41,7.19-0.06,11.07,1.21 c5.37,1.75,11.63,6.1,16.82,11.68c3.83,4.11,7.11,8.92,9.06,13.87c2.03,5.16,2.65,10.5,1.02,15.5c-0.96,2.96-2.7,5.74-5.4,8.25 c-0.93,0.86-2.37,0.8-3.23-0.12c-0.86-0.93-0.8-2.37,0.12-3.23c2.09-1.95,3.43-4.08,4.16-6.33c1.26-3.87,0.73-8.16-0.93-12.38 c-1.74-4.42-4.69-8.74-8.15-12.45c-4.68-5.02-10.23-8.91-14.91-10.44c-3.21-1.04-6.28-1.34-9.09-1c-3.26,0.4-6.18,1.65-8.51,3.6 c-2.34,1.95-4.13,4.58-5.16,7.71c-0.89,2.73-1.2,5.87-0.79,9.26c0.39,3.2,1.5,6.47,3.32,9.81c1.91,3.43,4.53,6.9,7.9,10.45 l0.02,0.03c4.22,4.35,8.27,7.15,12.28,8.29c3.79,1.08,7.65,0.66,11.68-1.35c0.92-0.53,2.11-0.35,2.84,0.47 c12.42,13.91,42.63,48.92,56.01,61.89c5.81,2.37,9.03-0.55,6.25-5.7C100.7,102.43,63.5,62.17,57.13,55.01L57.13,55.01L57.13,55.01z M45.07,75.12l-29.16,31.55c-0.06,0.06-0.11,0.12-0.18,0.18c-4.26,4.6,3.28,11.3,7.96,6.82l28.32-30.65l3.04,3.45l-28.1,30.41l0,0 c-0.06,0.07-0.12,0.13-0.2,0.2c-1.68,1.41-3.37,2.33-5.08,2.71c-1.76,0.4-3.49,0.22-5.15-0.56c-0.28-0.11-0.54-0.25-0.77-0.46 l-4.03-3.73l0,0c-0.06-0.06-0.12-0.11-0.18-0.18c-1.56-1.8-2.3-3.72-2.1-5.75c0.19-1.92,1.21-3.79,3.14-5.59l29.44-31.86 L45.07,75.12L45.07,75.12z M75.63,57.46l1.73-1.87c0.86-0.93,2.31-0.99,3.23-0.13s0.99,2.3,0.13,3.23l-2,2.16L75.63,57.46 L75.63,57.46z M104.45,7.43c0.86-0.93,2.3-0.99,3.23-0.13c0.93,0.86,0.99,2.3,0.13,3.23L91.4,28.3c-0.86,0.93-2.3,0.99-3.23,0.13 c-0.93-0.86-0.99-2.3-0.13-3.23L104.45,7.43L104.45,7.43L104.45,7.43z M111.55,14c0.86-0.93,2.3-0.99,3.23-0.13 c0.93,0.86,0.99,2.3,0.13,3.23L98.51,34.86c-0.86,0.93-2.3,0.99-3.23,0.13c-0.93-0.86-0.99-2.3-0.13-3.23L111.55,14L111.55,14 L111.55,14z M118.91,20.83c0.86-0.93,2.3-0.99,3.23-0.13c0.93,0.86,0.99,2.31,0.13,3.23L103.55,44.2c-0.07,0.07-0.14,0.13-0.21,0.2 c-4.26,4.1-8.33,6.47-12.22,7.14c-4.22,0.73-8.09-0.47-11.64-3.57c-0.95-0.83-1.04-2.28-0.22-3.22c0.83-0.95,2.28-1.04,3.22-0.22 c2.45,2.14,5.07,2.98,7.84,2.49c2.98-0.51,6.26-2.48,9.84-5.93l0.02-0.02l18.71-20.25L118.91,20.83L118.91,20.83z"/></g></svg>',
                        'Travel': '<svg version="1.1" id="Layer_1" fill="red" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-red-500" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 122.9 120.5" style="enable-background:new 0 0 122.9 120.5" xml:space="preserve"><style type="text/css">.st0{fill-rule:evenodd;clip-rule:evenodd;}</style><g><path class="st0" d="M110.8,103.6h-7.6V114c0,3.6-2.9,6.5-6.5,6.5h-9c-3.6,0-6.5-2.9-6.5-6.5v-10.3H41.5V114c0,3.6-2.9,6.5-6.5,6.5 h-9c-3.6,0-6.5-2.9-6.5-6.5v-10.3H12v-82c0-7.6,4.4-13.1,13.3-16.5c17.6-6.9,54.6-6.9,72.3,0c8.9,3.4,13.3,8.9,13.3,16.5V103.6 L110.8,103.6L110.8,103.6z M118.6,40.4h-3.8V62h3.8c2.4,0,4.3-1.9,4.3-4.3V44.7C122.9,42.3,121,40.4,118.6,40.4L118.6,40.4z M4.3,40.4h3.8V62H4.3C1.9,62,0,60.1,0,57.7V44.7C0,42.3,1.9,40.4,4.3,40.4L4.3,40.4z M46.4,8.6h30.1c0.9,0,1.6,0.7,1.6,1.6v5.2 c0,0.9-0.7,1.6-1.6,1.6H46.4c-0.9,0-1.6-0.7-1.6-1.6v-5.2C44.8,9.3,45.5,8.6,46.4,8.6L46.4,8.6z M22.9,23.2h76.7 c1,0,1.9,0.9,1.9,1.9v42.8c0,1-0.9,1.9-1.9,1.9H22.9c-1,0-1.9-0.9-1.9-1.9V25.1C21,24.1,21.8,23.2,22.9,23.2L22.9,23.2L22.9,23.2 L22.9,23.2z M98.6,84.9c0-1.9-0.7-3.6-2-4.9c-1.3-1.3-3-2-4.9-2c-1.9,0-3.5,0.7-4.9,2c-1.4,1.3-2,3-2,4.9c0,1.9,0.7,3.5,2,4.8 c1.4,1.3,3,2,4.9,2c1.9,0,3.6-0.7,4.9-2C98,88.4,98.6,86.8,98.6,84.9L98.6,84.9L98.6,84.9L98.6,84.9z M38.1,84.9 c0-1.9-0.7-3.6-2-4.9c-1.3-1.3-3-2-4.9-2c-1.9,0-3.6,0.7-4.9,2c-1.3,1.3-2,3-2,4.9c0,1.9,0.6,3.5,2,4.8c1.3,1.3,3,2,4.9,2 c2,0,3.6-0.7,4.9-2C37.4,88.4,38.1,86.8,38.1,84.9L38.1,84.9L38.1,84.9L38.1,84.9z"/></g></svg>',
                        'Accommodation': '<svg class="w-4 h-4 text-green-300" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>',
                        'Food and Drink': '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" class="w-4 h-4 text-yellow-300" x="0px" y="0px" viewBox="0 0 122.88 121.87" style="enable-background:new 0 0 122.88 121.87" xml:space="preserve" fill="yellow"><g><path d="M97.34,0.74c0.86-0.93,2.3-0.99,3.23-0.13c0.93,0.86,0.99,2.3,0.13,3.23L81.98,24.1l-0.03,0.04 c-2.29,2.77-3.86,5.33-4.56,7.67c-0.62,2.07-0.53,3.95,0.39,5.59c0.49,0.88,0.33,1.96-0.32,2.67l0,0l-8.89,9.62 c-0.87-0.95-1.56-1.72-2.02-2.22c-0.21-0.28-0.45-0.55-0.7-0.81l-0.02,0.02c-0.12-0.13-0.25-0.25-0.38-0.37l7.6-8.23 c-0.89-2.38-0.88-4.91-0.06-7.6c0.88-2.92,2.75-6.03,5.44-9.27c0.06-0.08,0.11-0.16,0.18-0.23L97.32,0.72L97.34,0.74L97.34,0.74z M57.13,55.01c-0.84-0.94-0.76-2.39,0.18-3.23c0.94-0.84,2.39-0.76,3.23,0.18c9.41,10.54,38.5,41.73,46.56,53.39 c10.63,15.05-5.83,19.79-11.29,14.31c-13.64-13.19-42.6-46.82-55.33-61.08c-4.58,1.94-9.03,2.24-13.5,0.96 c-4.81-1.37-9.52-4.58-14.3-9.51l-0.06-0.06c-3.64-3.84-6.49-7.63-8.55-11.38c-2.11-3.86-3.4-7.68-3.86-11.47 c-0.49-4.08-0.11-7.88,0.99-11.25c1.29-3.96,3.58-7.31,6.58-9.8c3.02-2.5,6.73-4.12,10.87-4.62c3.44-0.41,7.19-0.06,11.07,1.21 c5.37,1.75,11.63,6.1,16.82,11.68c3.83,4.11,7.11,8.92,9.06,13.87c2.03,5.16,2.65,10.5,1.02,15.5c-0.96,2.96-2.7,5.74-5.4,8.25 c-0.93,0.86-2.37,0.8-3.23-0.12c-0.86-0.93-0.8-2.37,0.12-3.23c2.09-1.95,3.43-4.08,4.16-6.33c1.26-3.87,0.73-8.16-0.93-12.38 c-1.74-4.42-4.69-8.74-8.15-12.45c-4.68-5.02-10.23-8.91-14.91-10.44c-3.21-1.04-6.28-1.34-9.09-1c-3.26,0.4-6.18,1.65-8.51,3.6 c-2.34,1.95-4.13,4.58-5.16,7.71c-0.89,2.73-1.2,5.87-0.79,9.26c0.39,3.2,1.5,6.47,3.32,9.81c1.91,3.43,4.53,6.9,7.9,10.45 l0.02,0.03c4.22,4.35,8.27,7.15,12.28,8.29c3.79,1.08,7.65,0.66,11.68-1.35c0.92-0.53,2.11-0.35,2.84,0.47 c12.42,13.91,42.63,48.92,56.01,61.89c5.81,2.37,9.03-0.55,6.25-5.7C100.7,102.43,63.5,62.17,57.13,55.01L57.13,55.01L57.13,55.01z M45.07,75.12l-29.16,31.55c-0.06,0.06-0.11,0.12-0.18,0.18c-4.26,4.6,3.28,11.3,7.96,6.82l28.32-30.65l3.04,3.45l-28.1,30.41l0,0 c-0.06,0.07-0.12,0.13-0.2,0.2c-1.68,1.41-3.37,2.33-5.08,2.71c-1.76,0.4-3.49,0.22-5.15-0.56c-0.28-0.11-0.54-0.25-0.77-0.46 l-4.03-3.73l0,0c-0.06-0.06-0.12-0.11-0.18-0.18c-1.56-1.8-2.3-3.72-2.1-5.75c0.19-1.92,1.21-3.79,3.14-5.59l29.44-31.86 L45.07,75.12L45.07,75.12z M75.63,57.46l1.73-1.87c0.86-0.93,2.31-0.99,3.23-0.13s0.99,2.3,0.13,3.23l-2,2.16L75.63,57.46 L75.63,57.46z M104.45,7.43c0.86-0.93,2.3-0.99,3.23-0.13c0.93,0.86,0.99,2.3,0.13,3.23L91.4,28.3c-0.86,0.93-2.3,0.99-3.23,0.13 c-0.93-0.86-0.99-2.3-0.13-3.23L104.45,7.43L104.45,7.43L104.45,7.43z M111.55,14c0.86-0.93,2.3-0.99,3.23-0.13 c0.93,0.86,0.99,2.3,0.13,3.23L98.51,34.86c-0.86,0.93-2.3,0.99-3.23,0.13c-0.93-0.86-0.99-2.3-0.13-3.23L111.55,14L111.55,14 L111.55,14z M118.91,20.83c0.86-0.93,2.3-0.99,3.23-0.13c0.93,0.86,0.99,2.31,0.13,3.23L103.55,44.2c-0.07,0.07-0.14,0.13-0.21,0.2 c-4.26,4.1-8.33,6.47-12.22,7.14c-4.22,0.73-8.09-0.47-11.64-3.57c-0.95-0.83-1.04-2.28-0.22-3.22c0.83-0.95,2.28-1.04,3.22-0.22 c2.45,2.14,5.07,2.98,7.84,2.49c2.98-0.51,6.26-2.48,9.84-5.93l0.02-0.02l18.71-20.25L118.91,20.83L118.91,20.83z"/></g></svg>',
                        'Shops': '<svg class="w-4 h-4 text-pink-300" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>',
                        'Shopping': '<svg class="w-4 h-4 text-pink-300" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>',
                        'Recreation': '<svg version="1.1" id="Layer_1" fill="orange" class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 118.92 122.88" style="enable-background:new 0 0 118.92 122.88" xml:space="preserve"><style type="text/css">.st0{fill-rule:evenodd;clip-rule:evenodd;}</style><g><path class="st0" d="M77.71,17.96C56.07,0.47,25.53,2.31,9.54,22.08C-6.46,41.87-1.86,72.1,19.79,89.62 c6.08,4.92,12.88,8.3,19.85,10.19l55.77-55.77C92.48,34.28,86.51,25.08,77.71,17.96L77.71,17.96z M104.22,0 C96.1,0,89.51,6.58,89.51,14.71c0,8.12,6.59,14.71,14.71,14.71c8.12,0,14.71-6.59,14.71-14.71C118.92,6.58,112.34,0,104.22,0 L104.22,0z M52,101.6c2.01,0.04,4.01-0.03,5.99-0.23c8.69-0.88,14.73-4.66,23.54-0.57c7.48,3.47,11.09,10.79,17.82,18.5 c3.26,3.74,3.79,4.36,8.7,2.72c6.47-2.17,9.37-6.62,9.99-12.51c0.54-5.11-1.34-5.41-5.32-8.31c-6.15-4.5-11.19-7.92-14.45-11.88 c-7.97-9.67-1.23-18.92-0.82-29.5c0.05-1.22,0.04-2.45-0.01-3.67L52,101.6L52,101.6L52,101.6z"/></g></svg>',
                        'Entertainment': '<svg class="w-4 h-4 text-purple-300" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="2.18" ry="2.18"/><line x1="7" y1="2" x2="7" y2="22"/><line x1="17" y1="2" x2="17" y2="22"/><line x1="2" y1="12" x2="22" y2="12"/><line x1="2" y1="7" x2="7" y2="7"/><line x1="2" y1="17" x2="7" y2="17"/><line x1="17" y1="17" x2="22" y2="17"/><line x1="17" y1="7" x2="22" y2="7"/></svg>',
                        'Transport': '<svg version="1.1" id="Layer_1" fill="red" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-red-500" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 122.9 120.5" style="enable-background:new 0 0 122.9 120.5" xml:space="preserve"><style type="text/css">.st0{fill-rule:evenodd;clip-rule:evenodd;}</style><g><path class="st0" d="M110.8,103.6h-7.6V114c0,3.6-2.9,6.5-6.5,6.5h-9c-3.6,0-6.5-2.9-6.5-6.5v-10.3H41.5V114c0,3.6-2.9,6.5-6.5,6.5 h-9c-3.6,0-6.5-2.9-6.5-6.5v-10.3H12v-82c0-7.6,4.4-13.1,13.3-16.5c17.6-6.9,54.6-6.9,72.3,0c8.9,3.4,13.3,8.9,13.3,16.5V103.6 L110.8,103.6L110.8,103.6z M118.6,40.4h-3.8V62h3.8c2.4,0,4.3-1.9,4.3-4.3V44.7C122.9,42.3,121,40.4,118.6,40.4L118.6,40.4z M4.3,40.4h3.8V62H4.3C1.9,62,0,60.1,0,57.7V44.7C0,42.3,1.9,40.4,4.3,40.4L4.3,40.4z M46.4,8.6h30.1c0.9,0,1.6,0.7,1.6,1.6v5.2 c0,0.9-0.7,1.6-1.6,1.6H46.4c-0.9,0-1.6-0.7-1.6-1.6v-5.2C44.8,9.3,45.5,8.6,46.4,8.6L46.4,8.6z M22.9,23.2h76.7 c1,0,1.9,0.9,1.9,1.9v42.8c0,1-0.9,1.9-1.9,1.9H22.9c-1,0-1.9-0.9-1.9-1.9V25.1C21,24.1,21.8,23.2,22.9,23.2L22.9,23.2L22.9,23.2 L22.9,23.2z M98.6,84.9c0-1.9-0.7-3.6-2-4.9c-1.3-1.3-3-2-4.9-2c-1.9,0-3.5,0.7-4.9,2c-1.4,1.3-2,3-2,4.9c0,1.9,0.7,3.5,2,4.8 c1.4,1.3,3,2,4.9,2c1.9,0,3.6-0.7,4.9-2C98,88.4,98.6,86.8,98.6,84.9L98.6,84.9L98.6,84.9L98.6,84.9z M38.1,84.9 c0-1.9-0.7-3.6-2-4.9c-1.3-1.3-3-2-4.9-2c-1.9,0-3.6,0.7-4.9,2c-1.3,1.3-2,3-2,4.9c0,1.9,0.6,3.5,2,4.8c1.3,1.3,3,2,4.9,2 c2,0,3.6-0.7,4.9-2C37.4,88.4,38.1,86.8,38.1,84.9L38.1,84.9L38.1,84.9L38.1,84.9z"/></g></svg>',
                        'Food and Drink': '<svg version="1.1" id="Layer_1" fill="yellow" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" class="w-4 h-4" x="0px" y="0px" viewBox="0 0 122.88 121.87" style="enable-background:new 0 0 122.88 121.87" xml:space="preserve"><g><path d="M97.34,0.74c0.86-0.93,2.3-0.99,3.23-0.13c0.93,0.86,0.99,2.3,0.13,3.23L81.98,24.1l-0.03,0.04 c-2.29,2.77-3.86,5.33-4.56,7.67c-0.62,2.07-0.53,3.95,0.39,5.59c0.49,0.88,0.33,1.96-0.32,2.67l0,0l-8.89,9.62 c-0.87-0.95-1.56-1.72-2.02-2.22c-0.21-0.28-0.45-0.55-0.7-0.81l-0.02,0.02c-0.12-0.13-0.25-0.25-0.38-0.37l7.6-8.23 c-0.89-2.38-0.88-4.91-0.06-7.6c0.88-2.92,2.75-6.03,5.44-9.27c0.06-0.08,0.11-0.16,0.18-0.23L97.32,0.72L97.34,0.74L97.34,0.74z M57.13,55.01c-0.84-0.94-0.76-2.39,0.18-3.23c0.94-0.84,2.39-0.76,3.23,0.18c9.41,10.54,38.5,41.73,46.56,53.39 c10.63,15.05-5.83,19.79-11.29,14.31c-13.64-13.19-42.6-46.82-55.33-61.08c-4.58,1.94-9.03,2.24-13.5,0.96 c-4.81-1.37-9.52-4.58-14.3-9.51l-0.06-0.06c-3.64-3.84-6.49-7.63-8.55-11.38c-2.11-3.86-3.4-7.68-3.86-11.47 c-0.49-4.08-0.11-7.88,0.99-11.25c1.29-3.96,3.58-7.31,6.58-9.8c3.02-2.5,6.73-4.12,10.87-4.62c3.44-0.41,7.19-0.06,11.07,1.21 c5.37,1.75,11.63,6.1,16.82,11.68c3.83,4.11,7.11,8.92,9.06,13.87c2.03,5.16,2.65,10.5,1.02,15.5c-0.96,2.96-2.7,5.74-5.4,8.25 c-0.93,0.86-2.37,0.8-3.23-0.12c-0.86-0.93-0.8-2.37,0.12-3.23c2.09-1.95,3.43-4.08,4.16-6.33c1.26-3.87,0.73-8.16-0.93-12.38 c-1.74-4.42-4.69-8.74-8.15-12.45c-4.68-5.02-10.23-8.91-14.91-10.44c-3.21-1.04-6.28-1.34-9.09-1c-3.26,0.4-6.18,1.65-8.51,3.6 c-2.34,1.95-4.13,4.58-5.16,7.71c-0.89,2.73-1.2,5.87-0.79,9.26c0.39,3.2,1.5,6.47,3.32,9.81c1.91,3.43,4.53,6.9,7.9,10.45 l0.02,0.03c4.22,4.35,8.27,7.15,12.28,8.29c3.79,1.08,7.65,0.66,11.68-1.35c0.92-0.53,2.11-0.35,2.84,0.47 c12.42,13.91,42.63,48.92,56.01,61.89c5.81,2.37,9.03-0.55,6.25-5.7C100.7,102.43,63.5,62.17,57.13,55.01L57.13,55.01L57.13,55.01z M45.07,75.12l-29.16,31.55c-0.06,0.06-0.11,0.12-0.18,0.18c-4.26,4.6,3.28,11.3,7.96,6.82l28.32-30.65l3.04,3.45l-28.1,30.41l0,0 c-0.06,0.07-0.12,0.13-0.2,0.2c-1.68,1.41-3.37,2.33-5.08,2.71c-1.76,0.4-3.49,0.22-5.15-0.56c-0.28-0.11-0.54-0.25-0.77-0.46 l-4.03-3.73l0,0c-0.06-0.06-0.12-0.11-0.18-0.18c-1.56-1.8-2.3-3.72-2.1-5.75c0.19-1.92,1.21-3.79,3.14-5.59l29.44-31.86 L45.07,75.12L45.07,75.12z M75.63,57.46l1.73-1.87c0.86-0.93,2.31-0.99,3.23-0.13s0.99,2.3,0.13,3.23l-2,2.16L75.63,57.46 L75.63,57.46z M104.45,7.43c0.86-0.93,2.3-0.99,3.23-0.13c0.93,0.86,0.99,2.3,0.13,3.23L91.4,28.3c-0.86,0.93-2.3,0.99-3.23,0.13 c-0.93-0.86-0.99-2.3-0.13-3.23L104.45,7.43L104.45,7.43L104.45,7.43z M111.55,14c0.86-0.93,2.3-0.99,3.23-0.13 c0.93,0.86,0.99,2.3,0.13,3.23L98.51,34.86c-0.86,0.93-2.3,0.99-3.23,0.13c-0.93-0.86-0.99-2.3-0.13-3.23L111.55,14L111.55,14 L111.55,14z M118.91,20.83c0.86-0.93,2.3-0.99,3.23-0.13c0.93,0.86,0.99,2.31,0.13,3.23L103.55,44.2c-0.07,0.07-0.14,0.13-0.21,0.2 c-4.26,4.1-8.33,6.47-12.22,7.14c-4.22,0.73-8.09-0.47-11.64-3.57c-0.95-0.83-1.04-2.28-0.22-3.22c0.83-0.95,2.28-1.04,3.22-0.22 c2.45,2.14,5.07,2.98,7.84,2.49c2.98-0.51,6.26-2.48,9.84-5.93l0.02-0.02l18.71-20.25L118.91,20.83L118.91,20.83z"/></g></svg>',

                        // Defaultna ikona za sve ostale kategorije
                        'Other': '<svg class="w-4 h-4 text-slate-300" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"/><path d="M12 18V6"/></svg>'
                    };

                    return icons[category] || icons['Other'];
                },
                init() {}
            } // uses entire function data
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Bar Chart with adjusted options for smaller size
            try {
                var ctx = document.getElementById('expenseChart').getContext('2d');
                var gradientFill = ctx.createLinearGradient(0, 0, 0, 300);
                gradientFill.addColorStop(0, 'rgba(75, 192, 192, 0.6)');
                gradientFill.addColorStop(1, 'rgba(75, 192, 192, 0.1)');

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Budget', 'All Expenses'],
                        datasets: [{
                            label: 'Amount in $',
                            data: [{{ $Budget }}, {{ $AllExpenses }}],
                            backgroundColor: [gradientFill, 'rgba(255, 99, 132, 0.6)'],
                            borderColor: ['rgba(75, 192, 192, 1)', 'rgba(255, 99, 132, 1)'],
                            borderWidth: 2,
                            borderRadius: 5,
                            borderSkipped: false,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    font: {
                                        size: 11
                                    }
                                }
                            },
                            x: {
                                ticks: {
                                    font: {
                                        size: 11
                                    }
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            title: {
                                display: true,
                                text: 'Budget vs Expenses',
                                font: {
                                    size: 16
                                },
                                padding: {
                                    top: 5,
                                    bottom: 15
                                }
                            }
                        }
                    }
                });
            } catch (error) {
                console.error('Error initializing bar chart:', error);
            }

            // Doughnut Chart with adjusted options
            try {
                var ctx2 = document.getElementById('expenseChart2').getContext('2d');
                new Chart(ctx2, {
                    type: 'doughnut',
                    data: {
                        labels: @json($categoryNames),
                        datasets: [{
                            data: @json($categoryExpenses),
                            backgroundColor: [
                                'rgba(75, 192, 192, 0.8)',
                                'rgba(255, 99, 132, 0.8)',
                                'rgba(54, 162, 235, 0.8)',
                                'rgba(255, 206, 86, 0.8)',
                                'rgba(153, 102, 255, 0.8)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    font: {
                                        size: 11
                                    }
                                }
                            },
                            title: {
                                display: true,
                                text: 'Expenses by Category',
                                font: {
                                    size: 16
                                },
                                padding: {
                                    top: 5,
                                    bottom: 15
                                }
                            }
                        }
                    }
                });
            } catch (error) {
                console.error('Error initializing doughnut chart:', error);
            }
        });

        function initPlaid() {
            fetch('/plaid/create-link-token', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        console.error('Error:', data.error);
                        alert('Failed to initialize Plaid. Please try again later.');
                        return;
                    }

                    const handler = Plaid.create({
                        token: data.link_token,
                        onSuccess: async (public_token, metadata) => {
                            try {
                                const response = await fetch('/plaid/get-access-token', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector(
                                            'meta[name="csrf-token"]').content
                                    },
                                    body: JSON.stringify({
                                        public_token
                                    })
                                });

                                const data = await response.json();
                                if (data.success) {
                                    window.location.reload();
                                } else {
                                    throw new Error('Failed to exchange token');
                                }
                            } catch (error) {
                                console.error('Error:', error);
                                alert('Failed to connect bank account. Please try again.');
                            }
                        },
                        onExit: (err, metadata) => {
                            if (err != null) {
                                console.error('Plaid Error:', err);
                            }
                        },
                        onLoad: () => {
                            // Optional: Add loading state handling
                        },
                        onEvent: (eventName, metadata) => {
                            // Optional: Track events
                            console.log('Plaid Event:', eventName, metadata);
                        }
                    });

                    handler.open();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to initialize Plaid. Please try again later.');
                });
        }

        document.addEventListener('alpine:init', () => {
            Alpine.data('imageSlider', () => ({
                placesImages: @json($suggestPlaces),
                eventsImages: @json($suggestEvents),
                currentPlacesIndex: 0,
                currentEventsIndex: 0,
                sliderInterval: null,
                observer: null,
                isVisible: false,
                transitioning: false,

                initialize() {
                    this.setupObserver();
                    this.$watch('isVisible', (value) => {
                        value ? this.startSlider() : this.stopSlider();
                    });
                },

                setupObserver() {
                    if (this.observer) return;

                    this.observer = new IntersectionObserver((entries) => {
                        requestAnimationFrame(() => {
                            this.isVisible = entries[0].isIntersecting;
                        });
                    }, {
                        threshold: 0.1
                    });

                    if (this.$refs.placesCard) {
                        this.observer.observe(this.$refs.placesCard);
                    }
                },

                async changeSlide() {
                    if (this.transitioning) return;
                    this.transitioning = true;

                    await new Promise(resolve => setTimeout(resolve, 50));

                    this.currentPlacesIndex = (this.currentPlacesIndex + 1) % this.placesImages
                        .length;
                    this.currentEventsIndex = (this.currentEventsIndex + 1) % this.eventsImages
                        .length;

                    await new Promise(resolve => setTimeout(resolve, 50));
                    this.transitioning = false;
                },

                startSlider() {
                    if (this.sliderInterval) return;
                    this.sliderInterval = setInterval(() => this.changeSlide(), 5000);
                },

                stopSlider() {
                    if (this.sliderInterval) {
                        clearInterval(this.sliderInterval);
                        this.sliderInterval = null;
                    }
                },

                cleanup() {
                    this.stopSlider();
                    if (this.observer) {
                        this.observer.disconnect();
                        this.observer = null;
                    }
                }
            }));
        });
        document.addEventListener('DOMContentLoaded', function () {
            Livewire.on('preferences-saved', () => {
                location.reload();
            });
        });
    </script>
@endpush
