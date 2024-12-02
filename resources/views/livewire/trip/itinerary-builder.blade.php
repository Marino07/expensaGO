<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white rounded-lg shadow-xl overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 p-6">
                <h2 class="text-3xl font-extrabold text-white">Itinerary Builder</h2>
                <p class="mt-2 text-blue-100">Plan your perfect day with ease</p>
            </div>

            <div class="p-6">
                <!-- Date Selector -->
                <div class="mb-6">
                    <label for="date-select" class="block text-sm font-medium text-gray-700 mb-2">Select Date</label>
                    <input id="date-select" type="date" wire:model="date" class="form-input rounded-md shadow-sm w-full sm:w-auto border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>

                <!-- Time Slots Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Morning Activities -->
                    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
                        <h3 class="font-semibold text-lg mb-4 text-indigo-700 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                            </svg>
                            Morning
                        </h3>
                        @foreach ($timeSlots as $slot)
                            @if ($slot['period'] === 'morning')
                                <div class="bg-gray-50 p-3 mb-3 rounded-md hover:shadow-md transition duration-300">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-700">{{ $slot['time'] }}</span>
                                        <button wire:click="addActivity({{ $slot['id'] }})"
                                            class="text-indigo-600 hover:text-indigo-800 font-medium flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                            </svg>
                                            Add Activity
                                        </button>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <!-- Local Events Section -->
                    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
                        <h3 class="font-semibold text-lg mb-4 text-indigo-700 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                            </svg>
                            Local Events
                        </h3>
                        @foreach ($events as $event)
                            <div class="bg-gray-50 p-4 mb-4 rounded-md hover:shadow-md transition duration-300">
                                <h4 class="font-medium text-lg text-gray-800">{{ $event['name'] }}</h4>
                                <p class="text-sm text-gray-600 mb-2">{{ $event['formatted_address'] }}</p>
                                <button wire:click="addToItinerary('{{ $event['place_id'] }}')"
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md transition duration-300 ease-in-out flex items-center text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                                    </svg>
                                    Add to Itinerary
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Modal -->
    <div x-show="$wire.showActivityModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" style="display: none;">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Add New Activity</h3>
                <form wire:submit.prevent="addActivity">
                    <div class="mb-4">
                        <label for="activityName" class="block text-sm font-medium text-gray-700 mb-2">Activity Name</label>
                        <input type="text" id="activityName" wire:model="activityName" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter activity name">
                        @error('activityName')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="activityDescription" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea id="activityDescription" wire:model="activityDescription" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter activity description"></textarea>
                        @error('activityDescription')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="button" wire:click="$set('showActivityModal', false)"
                            class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancel
                        </button>
                        <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Save Activity
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
