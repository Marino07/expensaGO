<div class="flex flex-col h-full bg-gray-50">
    <div class="flex-1 overflow-y-auto p-4 space-y-4" id="chat-messages">
        @foreach($messages as $message)
            <div class="flex items-start {{ $message['type'] === 'user' ? 'justify-end' : 'justify-start' }}">
                <div class="{{ $message['type'] === 'user' ? 'bg-blue-100 text-blue-900' : 'bg-white text-gray-900' }} rounded-lg p-3 max-w-[80%] shadow-sm">
                    <p class="text-sm whitespace-pre-wrap">{{ $message['content'] }}</p>
                </div>
            </div>
        @endforeach

        @if($isTyping)
            <div class="flex items-start justify-start">
                <div class="bg-gray-100 rounded-lg p-3">
                    <div class="flex space-x-2">
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <form wire:submit.prevent="sendMessage" class="p-4 bg-white border-t">
        <div class="flex space-x-2">
            <input type="text"
                wire:model="message"
                class="flex-1 border border-gray-300 rounded-full px-4 py-2 text-sm focus:outline-none focus:border-blue-500"
                placeholder="Ask me anything about your trip..."
                @if($isTyping) disabled @endif
            >
            <button type="submit"
                class="bg-gradient-to-r from-blue-500 to-cyan-400 text-white rounded-full px-6 py-2 text-sm hover:opacity-90 transition-opacity duration-200"
                @if($isTyping) disabled @endif>
                Send
            </button>
        </div>
    </form>

    @script
    <script>
        // Auto scroll to bottom when new messages arrive
        $wire.on('messageAdded', () => {
            const container = document.getElementById('chat-messages');
            container.scrollTop = container.scrollHeight;
        });
    </script>
    @endscript
</div>
