<div class="flex flex-col h-full bg-gray-50">
    <div class="flex-1 overflow-y-auto p-4 space-y-4">
        @foreach($responses as $response)
            <div class="flex items-start {{ $loop->index % 2 == 0 ? 'justify-end' : 'justify-start' }}">
                <div class="{{ $loop->index % 2 == 0 ? 'bg-blue-100 text-blue-900' : 'bg-white text-gray-900' }} rounded-lg p-3 max-w-[80%] shadow-sm">
                    <p class="text-sm">{{ $response }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <form wire:submit.prevent="sendMessage" class="p-4 bg-white border-t">
        <div class="flex space-x-2">
            <input type="text"
                wire:model.defer="message"
                class="flex-1 border border-gray-300 rounded-full px-4 py-2 text-sm focus:outline-none focus:border-blue-500"
                placeholder="Ask me anything about your trip..."
            >
            <button type="submit"
                class="bg-gradient-to-r from-blue-500 to-cyan-400 text-white rounded-full px-6 py-2 text-sm hover:opacity-90 transition-opacity duration-200">
                Send
            </button>
        </div>
    </form>
</div>
