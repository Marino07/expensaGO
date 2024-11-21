<div>
    <x-barapp />
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 py-12 px-4 sm:px-6 lg:px-8">

        <div class="max-w-3xl mx-auto">
            <div class="bg-white shadow-2xl rounded-lg overflow-hidden">
                <div class="bg-gradient-to-r from-green-500 to-teal-600 px-6 py-8 md:p-10 text-white">
                    <h2 class="text-3xl font-extrabold tracking-tight">Add New Expense</h2>
                    <p class="mt-2 text-lg">Keep track of your spending during your trip.</p>
                </div>

                <div class="px-6 py-8 md:p-10">
                    <form wire:submit.prevent="addExpense" class="space-y-6">
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
                                    class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    placeholder="Enter expense title" required>
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
                                        placeholder="Select date" readonly>
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
                                        placeholder="0.00" required>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                            <select id="category" wire:model="category"
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="">Select a category</option>
                                <option value="food">Food & Drinks</option>
                                <option value="transportation">Transportation</option>
                                <option value="accommodation">Accommodation</option>
                                <option value="activities">Activities & Entertainment</option>
                                <option value="shopping">Shopping</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <div class="mt-1">
                                <textarea id="description" wire:model="description" rows="3"
                                    class="shadow-sm block w-full focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md"
                                    placeholder="Add any additional details about the expense"></textarea>
                            </div>
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
    <x-footer />
</div>
