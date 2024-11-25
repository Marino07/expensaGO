<div x-data="tripManager()">
    <x-barapp />

    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white shadow-2xl rounded-lg overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-8 md:p-6 text-white">
                    <h2 class="text-3xl font-extrabold tracking-tight">Manage Your Trips</h2>
                    <p class="mt-2 text-lg">Overview and control of all your adventures.</p>
                </div>

                <div class="px-6 py-8 md:p-10">
                    <div class="flex justify-between items-center mb-6">
                        <div class="relative">
                            <input type="text" placeholder="Search trips..."
                                class="pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                        <a href="{{route('start-trip')}}"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            New Trip
                        </a>
                    </div>

                    <div class="bg-white shadow overflow-hidden sm:rounded-md">
                        <ul class="divide-y divide-gray-200">
                           @foreach ($trips as $trip)
                           <li x-data="{ showExpenses: false, expenses: {{ json_encode($trip->expenses) }} }">
                            <div class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                                 class="text-blue-400 w-5 h-5" viewBox="0 0 16 16"
                                                 :class="{ 'text-green-500': '{{ $trip->status }}' === 'active' }">
                                                <path d="M6.428 1.151C6.708.591 7.213 0 8 0s1.292.592 1.572 1.151C9.861 1.73 10 2.431 10 3v3.691l5.17 2.585a1.5 1.5 0 0 1 .83 1.342V12a.5.5 0 0 1-.582.493l-5.507-.918-.375 2.253 1.318 1.318A.5.5 0 0 1 10.5 16h-5a.5.5 0 0 1-.354-.854l1.319-1.318-.376-2.253-5.507.918A.5.5 0 0 1 0 12v-1.382a1.5 1.5 0 0 1 .83-1.342L6 6.691V3c0-.568.14-1.271.428-1.849m.894.448C7.111 2.02 7 2.569 7 3v4a.5.5 0 0 1-.276.447l-5.448 2.724a.5.5 0 0 0-.276.447v.792l5.418-.903a.5.5 0 0 1 .575.41l.5 3a.5.5 0 0 1-.14.437L6.708 15h2.586l-.647-.646a.5.5 0 0 1-.14-.436l.5-3a.5.5 0 0 1 .576-.411L15 11.41v-.792a.5.5 0 0 0-.276-.447L9.276 7.447A.5.5 0 0 1 9 7V3c0-.432-.11-.979-.322-1.401C8.458 1.159 8.213 1 8 1s-.458.158-.678.599"/>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <h3 class="text-lg font-medium text-indigo-600">{{$trip->location}}</h3>
                                            <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($trip->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($trip->end_date)->format('d M Y') }}</p>
                                        </div>
                                    </div>
                                    @if ($trip->status === 'active')
                                    <div class="flex space-x-2">
                                        <button wire:click="finishTrip({{$trip->id}})" @click="showExpenses = true"
                                                class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            Finish Trip
                                        </button>
                                    </div>

                                    @endif

                                </div>
                                <div class="mt-2 sm:flex sm:justify-between">
                                    <div class="sm:flex">
                                        <p class="flex items-center text-sm text-gray-500">
                                            <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400"
                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Budget: {{$trip->budget}}
                                        </p>
                                        <p class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0 sm:ml-6">
                                            <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400"
                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                                <path fill-rule="evenodd"
                                                    d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            {{$trip->expenses->count()}} Expenses
                                        </p>
                                    </div>
                                </div>

                                <!-- Dodajemo prikaz troškova -->
                                <div x-show="showExpenses"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 transform translate-x-full"
                                x-transition:enter-end="opacity-100 transform translate-x-0"
                                class="mt-6 bg-white p-6 rounded-lg shadow-md">
                                <h4 class=" flex justify-center text-xl font-semibold mb-4 text-blue-400">We hope you enjoy 😊</h4>
                               <h4 class="text-xl font-semibold mb-4 text-indigo-700">Trip Expenses</h4>
                               <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-6">
                                   <template x-for="expense in expenses" :key="expense.id">
                                       <div class="bg-gray-50 p-4 rounded-lg shadow-sm transition duration-300 ease-in-out transform hover:scale-105">
                                           <div class="flex justify-between items-center mb-2">
                                               <p x-text="expense.title" class="font-medium text-gray-800"></p>
                                               <span x-text="'$' + expense.amount.toFixed(2)" class="text-indigo-600 font-semibold"></span>
                                           </div>
                                           <div class="mt-2">
                                               <p class="text-sm text-gray-600 mb-1" x-text="expense.title"></p>
                                               <div class="h-2 bg-indigo-200 rounded-full overflow-hidden">
                                                   <div class="h-2 bg-indigo-600 rounded-full"
                                                        :style="`width: ${Math.min((expense.amount / trip.budget) * 100, 100)}%`"
                                                        :aria-valuenow="Math.min((expense.amount / trip.budget) * 100, 100)"
                                                        aria-valuemin="0"
                                                        aria-valuemax="100"
                                                        role="progressbar"
                                                        :aria-label="`${expense.title} expense progress`"></div>
                                               </div>
                                           </div>
                                       </div>
                                   </template>
                               </div>
                               <div class="bg-gray-100 p-4 rounded-lg">
                                   <div class="flex justify-between items-center mb-2">
                                       <p class="text-lg font-semibold text-gray-800">Total Expenses:</p>
                                       <p class="text-xl font-bold text-indigo-700" x-text="'$' + expenses.reduce((sum, exp) => sum + exp.amount, 0).toFixed(2)"></p>
                                   </div>
                                   <div class="h-4 bg-gray-300 rounded-full overflow-hidden">
                                       <div class="h-4 rounded-full transition-all duration-500 ease-in-out"
                                            :class="expenses.reduce((sum, exp) => sum + exp.amount, 0) <= {{ $trip->budget }} ? 'bg-green-500' : 'bg-red-500'"
                                            :style="`width: ${Math.min((expenses.reduce((sum, exp) => sum + exp.amount, 0) / {{ $trip->budget }}) * 100, 100)}%`"
                                            :aria-valuenow="Math.min((expenses.reduce((sum, exp) => sum + exp.amount, 0) / {{ $trip->budget }}) * 100, 100)"
                                            aria-valuemin="0"
                                            aria-valuemax="100"
                                            role="progressbar"
                                            aria-label="Total expenses progress"></div>
                                   </div>
                                   <div class="mt-2 flex justify-between items-center">
                                       <p class="font-medium"
                                          x-text="expenses.reduce((sum, exp) => sum + exp.amount, 0) <= {{ $trip->budget }} ? 'Within budget! Well Done 👏' : 'Over budget! 😕'"
                                          :class="expenses.reduce((sum, exp) => sum + exp.amount, 0) <= {{ $trip->budget }} ? 'text-green-600' : 'text-red-600'">
                                       </p>
                                       <p class="font-medium text-gray-600" x-text="`${Math.min(Math.round((expenses.reduce((sum, exp) => sum + exp.amount, 0) / {{ $trip->budget }}) * 100), 100)}% of budget`"></p>
                                   </div>
                               </div>
                           </div>
                        </li>
                        @endforeach
                        </ul>
                    </div>

                    <div class="mt-6">
                        <nav class="flex items-center justify-between border-t border-gray-200 px-4 sm:px-0">
                            <div class="-mt-px flex w-0 flex-1">
                                <a href="#"
                                    class="inline-flex items-center border-t-2 border-transparent pt-4 pr-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700">
                                    <svg class="mr-3 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd"
                                            d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Previous
                                </a>
                            </div>
                            <div class="hidden md:-mt-px md:flex">
                                <a href="#"
                                    class="inline-flex items-center border-t-2 border-indigo-500 px-4 pt-4 text-sm font-medium text-indigo-600"
                                    aria-current="page">
                                    1
                                </a>
                                <a href="#"
                                    class="inline-flex items-center border-t-2 border-transparent px-4 pt-4 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700">
                                    2
                                </a>
                                <a href="#"
                                    class="inline-flex items-center border-t-2 border-transparent px-4 pt-4 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700">
                                    3
                                </a>
                            </div>
                            <div class="-mt-px flex w-0 flex-1 justify-end">
                                <a href="#"
                                    class="inline-flex items-center border-t-2 border-transparent pt-4 pl-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700">
                                    Next
                                    <svg class="ml-3 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd"
                                            d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </a>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-footer />

    <!-- Custom Finish Trip Modal -->
    <div x-cloak x-data="{ showModal: false, tripId: null }" x-on:open-finish-modal.window="showModal = true; tripId = $event.detail">
        <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-cloak x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Finish Trip
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Are you sure you want to finish this trip? This action cannot be undone.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" @click="$wire.finishTrip(tripId); showModal = false" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Finish Trip
                        </button>
                        <button type="button" @click="showModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function tripManager() {
        return {
            // Ovdje možete dodati dodatne funkcije ako su potrebne
        }
    }
</script>
