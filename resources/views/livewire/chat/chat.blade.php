<div>
    <div class="mb-4">
        @foreach($responses as $response)
            <div class="bg-gray-100 p-2 rounded mb-2">{{ $response }}</div>
        @endforeach
    </div>

    <form wire:submit.prevent="sendMessage">
        <input type="text" wire:model="message" class="border p-2 rounded w-full" placeholder="Type your message...">
        <button type="submit" class="mt-2 bg-blue-500 text-white p-2 rounded">Send</button>
    </form>
</div>
