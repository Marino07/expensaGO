<div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <h2 class="text-xl font-bold mb-4">Nearby Emergency Services</h2>
        <div class="flex space-x-2 mb-4">
            <button wire:click="searchEmergencyServices('hospital')" class="bg-blue-500 text-white px-4 py-2 rounded-md">Hospitals</button>
            <button wire:click="searchEmergencyServices('police')" class="bg-red-500 text-white px-4 py-2 rounded-md">Police Stations</button>
        </div>
        <ul class="mt-4">
            @foreach($emergencyServices as $service)
                <li class="mb-2">
                    <h3 class="font-bold">{{ $service['name'] }}</h3>
                    <p>{{ $service['vicinity'] }}</p>
                </li>
            @endforeach
        </ul>
    </div>
</div>
