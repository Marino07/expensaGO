<div>
    <x-barapp />
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white shadow-2xl rounded-lg overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-2 md:p-4 text-white">
                    <x-logo-title />
                </div>

                <div class="px-6 py-8 md:p-10">
                    <form wire:submit.prevent="submit" class="space-y-6">
                        <div>
                            <label for="tripName" class="block text-sm font-medium text-gray-700">Trip Name</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM4.332 8.027a6.012 6.012 0 011.912-2.706C6.512 5.73 6.974 6 7.5 6A1.5 1.5 0 019 7.5V8a2 2 0 004 0 2 2 0 011.523-1.943A5.977 5.977 0 0116 10c0 .34-.028.675-.083 1H15a2 2 0 00-2 2v2.197A5.973 5.973 0 0110 16v-2a2 2 0 00-2-2 2 2 0 01-2-2 2 2 0 00-1.668-1.973z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="text" id="tripName" wire:model="location"
                                    class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    placeholder="Enter your precise location for vacation" required>
                                    @error('location') <span class="error text-red-400">{{ $message }}</span> @enderror

                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div x-data="{ picker: null }" x-init="picker = flatpickr($refs.startDate, { dateFormat: 'Y-m-d' })">
                                <label for="startDate" class="block text-sm font-medium text-gray-700">Start
                                    Date</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd"
                                                d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input type="text" id="startDate" x-ref="startDate" wire:model="start_date"
                                        class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                        placeholder="Select start date" readonly>
                                        @error('start_date') <span class="error text-red-400">{{ $message }}</span> @enderror

                                </div>
                            </div>

                            <div x-data="{ picker: null }" x-init="picker = flatpickr($refs.endDate, { dateFormat: 'Y-m-d' })">
                                <label for="endDate" class="block text-sm font-medium text-gray-700">End Date</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd"
                                                d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input type="text" id="endDate" x-ref="endDate" wire:model="end_date"
                                        class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                        placeholder="Select end date" readonly>
                                        @error('end_date') <span class="error text-red-400">{{ $message }}</span> @enderror

                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="budget" class="block text-sm font-medium text-gray-700">Budget</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" id="budget" wire:model="budget"
                                    class="pl-7 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    placeholder="0.00" required>
                                    @error('budget') <span class="error text-red-400">{{ $message }}</span> @enderror

                            </div>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Trip
                                Description</label>
                            <div class="mt-1">
                                <textarea id="description" wire:model="description" rows="3"
                                    class="shadow-sm block w-full focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md"
                                    placeholder="Describe your upcoming adventure"></textarea>
                                    @error('description') <span class="error text-red-400">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input id="remember_me" type="checkbox"
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="remember_me" class="ml-2 block text-sm text-gray-900">
                                    Save as draft
                                </label>
                            </div>

                            <div class="text-sm">
                                <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">
                                    Need help planning?
                                </a>
                            </div>
                        </div>

                        <div>
                            <button type="submit"
                                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                                Start Your Trip
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @if (session()->has('message'))
                <div class="mt-8 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md shadow-md"
                    role="alert">
                    <p class="font-bold">Success!</p>
                    <p>{{ session('message') }}</p>
                </div>
            @endif
        </div>

        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    </div>
</div>
