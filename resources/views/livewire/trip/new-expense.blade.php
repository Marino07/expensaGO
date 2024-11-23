<div>
    @if (session()->has('message'))
    <div x-data="{ show: true }"
         x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-90"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-90"
         class="bg-gradient-to-l from-blue-50 to-cyan-300 border-l-4 border-blue-500 p-2 rounded-md shadow-lg"
         role="alert">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-lg font-medium text-green-800">
                    Trip created successfully! 💰
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
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 py-12 px-4 sm:px-6 lg:px-8">

        <div class="max-w-3xl mx-auto">
            <div class="bg-white shadow-2xl rounded-lg overflow-hidden">
                <div class="bg-gradient-to-r from-green-500 to-teal-600 px-6 py-8 md:p-10 text-white">
                    <h2 class="text-3xl font-extrabold tracking-tight">Add New Expense</h2>
                    <p class="mt-2 text-lg">Keep track of your spending during your trip.</p>
                </div>

                <div class="px-6 py-8 md:p-10">
                    <form wire:submit.prevent="submit" class="space-y-6">
                        <div>
                            <label for="trip" class="block text-sm font-medium text-gray-700">Select Trip</label>
                            <select id="trip" wire:model="tripId"
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="">Select active trip</option>
                                @foreach($trips as $trip)
                                    <option value="{{ $trip->id }}">{{ $trip->location }}</option>
                                @endforeach
                            </select>
                            @error('tripId') <span class="error text-red-400">{{ $message }}</span> @enderror
                        </div>
                        <input type="hidden" wire:model="tripId">
                        <div>
                            <label for="expenseTitle" class="block text-sm font-medium text-gray-700">Expense
                                Title</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd"
                                            d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 0v12h8V4H6z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="text" id="expenseTitle" wire:model="expenseTitle"
                                    class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm "
                                    placeholder="Enter expense title" draggable="true">
                                    @error('expenseTitle') <span class="error mt-1 text-red-400">{{ $message }}</span> @enderror

                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div x-data="{ picker: null }" x-init="picker = flatpickr($refs.expenseDate, { dateFormat: 'Y-m-d' })">
                                <label for="expenseDate" class="block text-sm font-medium text-gray-700">Date</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd"
                                                d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input type="text" id="expenseDate" x-ref="expenseDate" wire:model="expenseDate"
                                        class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                        placeholder="Select date"  draggable="false">
                                        @error('expenseDate') <span class="error mt-3 text-red-400">{{ $message }}</span> @enderror

                                </div>
                            </div>

                            <div>
                                <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">$</span>
                                    </div>
                                    <input type="number" step="0.01" id="amount" wire:model="amount"
                                        class="pl-7 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                        placeholder="0.00" >
                                        @error('amount') <span class="error text-red-400">{{ $message }}</span> @enderror

                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="category_id">Category</label>
                            <select id="category_id" wire:model="category_id"
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="">Select a category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @error('category_id') <span class="error text-red-400">{{ $message }}</span> @enderror
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input id="isRecurring" type="checkbox" wire:model="isRecurring"
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="isRecurring" class="ml-2 block text-sm text-gray-900">
                                    Recurring expense
                                </label>
                            </div>

                            <div class="text-sm">
                                <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">
                                    Need help categorizing?
                                </a>
                            </div>
                        </div>

                        <div>
                            <button type="submit"
                                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-green-600 to-teal-600 hover:from-green-700 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out">
                                Add Expense
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    </div>
    <x-footer />
</div>
