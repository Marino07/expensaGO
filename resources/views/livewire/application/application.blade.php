
    <div class="flex flex-col min-h-screen bg-gradient-to-br from-indigo-50 via-white to-pink-50">
        @if (session()->has('message'))
        <div x-data="{ show: true }"
             x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-90"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-90"
             class=" bg-gradient-to-l from-blue-50 to-cyan-300 border-l-4 border-blue-500 p-4 rounded-md shadow-lg"
             role="alert">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
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
                            <button @click="show = false" type="button" class="bg-cyan-300 px-2 py-1.5 rounded-md text-sm font-medium text-green-800 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-green-50 focus:ring-green-600">
                                Dismiss
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
        <x-barapp />


        <main class="flex-grow container mx-auto my-3 px-4 sm:px-6 lg:px-8 py-8 bg-blue-50 rounded-lg shadow-inner">
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                <!-- Total Expenses Card -->
                <div class="bg-gradient-to-br from-blue-400 to-indigo-600 rounded-lg shadow-lg overflow-hidden">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-white rounded-md p-3">
                                <svg class="h-6 w-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-100 truncate">
                                        Total Expenses
                                    </dt>
                                    <dd class="text-3xl font-semibold text-white">
                                        $3,659.00
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="bg-indigo-600 px-4 py-4 sm:px-6">
                        <div class="text-sm">
                            <a href="{{route('all-expenses')}}" class="font-medium text-white hover:text-indigo-100 transition ease-in-out duration-150">
                                View all expenses &rarr;
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Active Trips Card -->
                <div class="bg-gradient-to-br from-blue-300 to-indigo-500 rounded-lg shadow-lg overflow-hidden">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-white rounded-md p-3">
                                <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-100 truncate">
                                        Active Trips
                                    </dt>
                                    <dd class="text-3xl font-semibold text-white">
                                        2
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="bg-green-600 px-4 py-4 sm:px-6">
                        <div class="text-sm">
                            <a href="{{route('manage-trips')}}" class="font-medium text-white hover:text-green-100 transition ease-in-out duration-150">
                                Manage trips &rarr;
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Pending Reports Card -->
                <div class="bg-gradient-to-br from-blue-200 to-indigo-400 rounded-lg shadow-lg overflow-hidden">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-white rounded-md p-3">
                                <svg class="h-6 w-6 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-100 truncate">
                                        Pending Reports
                                    </dt>
                                    <dd class="text-3xl font-semibold text-white">
                                        3
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="bg-yellow-600 px-4 py-4 sm:px-6">
                        <div class="text-sm">
                            <a href="#" class="font-medium text-white hover:text-yellow-100 transition ease-in-out duration-150">
                                Review reports &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Expenses -->
            <div class="mt-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">Recent Expenses</h2>
                <div class="bg-white shadow-lg rounded-lg overflow-hidden border border-blue-100">
                    <table class="w-full divide-y divide-blue-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Description
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Category
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Price
                                </th>

                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($lastExpenses as $expense)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    2023-05-13
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{$expense->title}}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                       {{$expense->category->name}}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    ${{$expense->amount}}
                                </td>
                            </tr>


                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">Quick Actions</h2>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <div class="relative group bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-blue-500 rounded-lg shadow-lg hover:bg-blue-50 transition-all duration-200">
                        <div>
                            <span class="rounded-lg inline-flex p-3 bg-blue-100 text-blue-700 ring-4 ring-white">
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </span>
                        </div>
                        <div class="mt-8">
                            <h3 class="text-lg font-medium">
                                <a href="{{route('new-expense')}}" class="focus:outline-none">
                                    <span class="absolute inset-0" aria-hidden="true"></span>
                                    Add New Expense
                                </a>
                            </h3>
                            <p class="mt-2 text-sm text-gray-500">
                                Quickly log a new expense for your current trip.
                            </p>
                        </div>
                        <span class="pointer-events-none absolute top-6 right-6 text-gray-300 group-hover:text-indigo-400" aria-hidden="true">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20 4h1a1 1 0 00-1-1v1zm-1 12a1 1 0 102 0h-2zM8 3a1 1 0 000 2V3zM3.293 19.293a1 1 0 101.414 1.414l-1.414-1.414zM19 4v12h2V4h-2zm1-1H8v2h12V3zm-.707.293l-16 16 1.414 1.414 16-16-1.414-1.414z" />
                            </svg>
                        </span>
                    </div>

                    <div class="relative group bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-blue-500 rounded-lg shadow-lg hover:bg-blue-50 transition-all duration-200">
                        <div>
                            <span class="rounded-lg inline-flex p-3 bg-blue-100 text-blue-700 ring-4 ring-white">
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </span>
                        </div>
                        <div class="mt-8">
                            <h3 class="text-lg font-medium">
                                <a href="{{route('start-trip')}}" class="focus:outline-none">
                                    <span class="absolute inset-0" aria-hidden="true"></span>
                                    Start New Trip
                                </a>
                            </h3>
                            <p class="mt-2 text-sm text-gray-500">
                                Begin tracking expenses for a new journey.
                            </p>
                        </div>
                        <span class="pointer-events-none absolute top-6 right-6 text-gray-300 group-hover:text-green-400" aria-hidden="true">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20 4h1a1 1 0 00-1-1v1zm-1 12a1 1 0 102 0h-2zM8 3a1 1 0 000 2V3zM3.293 19.293a1 1 0 101.414 1.414l-1.414-1.414zM19 4v12h2V4h-2zm1-1H8v2h12V3zm-.707.293l-16 16 1.414 1.414 16-16-1.414-1.414z" />
                            </svg>
                        </span>
                    </div>

                    <div class="relative group bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-blue-500 rounded-lg shadow-lg hover:bg-blue-50 transition-all duration-200">
                        <div>
                            <span class="rounded-lg inline-flex p-3 bg-blue-100 text-blue-700 ring-4 ring-white">
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </span>
                        </div>
                        <div class="mt-8">
                            <h3 class="text-lg font-medium">
                                <a href="#" class="focus:outline-none">
                                    <span class="absolute inset-0" aria-hidden="true"></span>
                                    Generate Report
                                </a>
                            </h3>
                            <p class="mt-2 text-sm text-gray-500">
                                Create a detailed expense report for your trips.
                            </p>
                        </div>
                        <span class="pointer-events-none absolute top-6 right-6 text-gray-300 group-hover:text-yellow-400" aria-hidden="true">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20 4h1a1 1 0 00-1-1v1zm-1 12a1 1 0 102 0h-2zM8 3a1 1 0 000 2V3zM3.293 19.293a1 1 0 101.414 1.414l-1.414-1.414zM19 4v12h2V4h-2zm1-1H8v2h12V3zm-.707.293l-16 16 1.414 1.414 16-16-1.414-1.414z" />
                            </svg>
                        </span>
                    </div>
                </div>
            </div>
        </main>
    <x-footer />


    </div>

