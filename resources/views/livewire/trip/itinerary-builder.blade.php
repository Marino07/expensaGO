<div>
    <div class="p-6">
        <h2 class="text-2xl font-bold mb-4">Itinerary Builder</h2>

        <!-- Date Selector -->
        <div class="mb-6">
            <input type="date" wire:model="date" class="form-input rounded-md shadow-sm">
        </div>

        <!-- Time Slots Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Morning Activities -->
            <div class="bg-white p-4 rounded-lg shadow">
                <h3 class="font-semibold mb-2">Morning</h3>
                @foreach ($timeSlots as $slot)
                    @if ($slot['period'] === 'morning')
                        <div class="border p-2 mb-2 rounded">
                            <div class="flex justify-between">
                                <span>{{ $slot['time'] }}</span>
                                <button wire:click="addActivity({{ $slot['id'] }})"
                                    class="text-blue-500 hover:text-blue-700">
                                    Add Activity
                                </button>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <!-- Local Events Section -->
            <div class="bg-white p-4 rounded-lg shadow">
                <h3 class="font-semibold mb-2">Local Events</h3>
                @foreach ($events as $event)
                    <div class="border p-2 mb-2 rounded">
                        <h4 class="font-medium">{{ $event['name'] }}</h4>
                        <p class="text-sm text-gray-600">{{ $event['formatted_address'] }}</p>
                        <button wire:click="addToItinerary('{{ $event['place_id'] }}')"
                            class="mt-2 bg-blue-500 text-white px-3 py-1 rounded">
                            Add to Itinerary
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Activity Modal -->
    <div x-show="$wire.showActivityModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium">Add New Activity</h3>
                <form wire:submit.prevent="addActivity">
                    <div class="mt-2">
                        <input type="text" wire:model="activityName" class="w-full border rounded px-3 py-2"
                            placeholder="Activity Name">
                        @error('activityName')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mt-2">
                        <textarea wire:model="activityDescription" class="w-full border rounded px-3 py-2" placeholder="Description"></textarea>
                        @error('activityDescription')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mt-4 flex justify-end">
                        <button type="button" wire:click="$set('showActivityModal', false)"
                            class="bg-gray-500 text-white px-3 py-1 rounded">
                            Cancel
                        </button>
                        <button type="submit" class="ml-2 bg-blue-500 text-white px-3 py-1 rounded">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
