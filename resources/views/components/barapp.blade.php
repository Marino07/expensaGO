<header class="px-4 lg:px-6 h-14 flex items-center border-b bg-blue-100">
    <a href="{{ route('index') }}" class="flex items-center justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6 text-primary"><path d="M17.8 19.2L16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z"></path></svg>
        <span class="ml-2 text-2xl font-bold text-primary">ExpensaGO</span>
    </a>
    <nav class="ml-auto flex gap-4 sm:gap-6">
        <a class="text-sm font-medium hover:underline underline-offset-4" href="#features">Features</a>
        <a class="text-sm font-medium hover:underline underline-offset-4" href="#testimonials">Testimonials</a>
        <a class="text-sm font-medium hover:underline underline-offset-4" href="#cta">Get Started</a>
        @guest
        <a class="text-sm font-medium hover:underline underline-offset-4" href="{{route('register')}}">Register</a>
        <a class="text-sm font-medium hover:underline underline-offset-4" href="{{route('login')}}">Login</a>
        @endguest
        @auth
        <a @click="$wire.call('logout')" class="text-sm font-medium hover:underline underline-offset-4" href="#">Logout</a>
        @endauth
    </nav>
</header>
