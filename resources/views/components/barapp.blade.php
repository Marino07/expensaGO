<header x-data="{ isOpen: false }" @class([
    'px-4 lg:px-6 h-14 flex items-center border-b',
    'bg-blue-300' => request()->routeIs('analytics'),
    'bg-blue-200' => request()->routeIs('saved-items'),
    'bg-blue-100' =>
        !request()->routeIs('analytics') && !request()->routeIs('saved-items'),
])>
    <a href="@auth{{ route('app') }}@else{{ route('index') }}@endauth" class="flex items-center justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6 text-primary">
            <path
                d="M17.8 19.2L16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z">
            </path>
        </svg>
        <span class="ml-2 text-2xl font-bold text-primary hidden md:inline-block">ExpensaGO</span>
    </a>

    <!-- Hamburger button -->
    <button @click="isOpen = !isOpen" class="ml-auto md:hidden">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg">
            <path x-show="!isOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16"></path>
            <path x-show="isOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>

    <!-- Navigation menu -->
    <nav :class="{ 'hidden': !isOpen }" @class([
        'absolute top-14 left-0 right-0 md:bg-transparent md:relative md:top-0 md:flex md:ml-auto md:items-center md:gap-4 sm:gap-6 z-50',
        'bg-indigo-700' => request()->routeIs('analytics'),
        'bg-blue-200' => request()->routeIs('saved-items'),
        'bg-blue-100' =>
            !request()->routeIs('analytics') && !request()->routeIs('saved-items'),
    ])>
        <div class="flex flex-col md:flex-row items-center gap-4 p-4 md:p-0">
            @auth
                @if (!auth()->check() || !auth()->user()->plaid_access_token)
                    <a href="{{ route('app') }}"
                        class="text-sm font-medium text-yellow-700 hover:text-yellow-900 hover:underline underline-offset-4 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                        Link bank account
                    </a>
                @else
                    <span class="text-sm font-medium text-green-700 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Connected
                    </span>
                @endif
                <a class="text-sm font-medium hover:underline underline-offset-4"  href="/sos"><svg
                        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1"
                        width="30" height="25" viewBox="0 0 256 256" xml:space="preserve">

                        <defs>
                        </defs>
                        <g style="stroke: none; stroke-width: 0; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: none; fill-rule: nonzero; opacity: 1;"
                            transform="translate(1.4065934065934016 1.4065934065934016) scale(2.81 2.81)">
                            <path
                                d="M 79.106 0 h -58.8 c -5.485 0 -9.932 4.447 -9.932 9.932 v 14.701 v 1.833 V 51.45 h 17.903 h 5.531 h 8.077 l 7.821 12.865 l 7.821 -12.865 h 21.579 c 5.486 0 9.932 -4.447 9.932 -9.932 V 9.932 C 89.038 4.447 84.591 0 79.106 0 z"
                                style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(235,84,104); fill-rule: nonzero; opacity: 1;"
                                transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                            <path
                                d="M 8.443 22.632 l 2.468 2.468 c 0.221 0.221 0.561 0.271 0.836 0.123 l 2.957 -1.588 c 0.219 -0.118 0.483 -0.112 0.697 0.015 l 4.315 2.56 c 0.308 0.183 0.432 0.565 0.288 0.892 c -0.369 0.839 -1.04 2.377 -1.306 3.057 c -0.056 0.143 -0.14 0.274 -0.256 0.375 c -2.876 2.493 -8.597 -0.545 -12.976 -4.924 C 1.085 21.231 -1.952 15.51 0.54 12.635 c 0.101 -0.116 0.232 -0.2 0.375 -0.256 c 0.68 -0.267 2.218 -0.938 3.057 -1.306 c 0.328 -0.144 0.71 -0.02 0.892 0.288 l 2.56 4.315 c 0.127 0.214 0.132 0.478 0.015 0.697 l -1.588 2.957 c -0.148 0.275 -0.098 0.615 0.123 0.836 L 8.443 22.632 z"
                                style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(182,196,207); fill-rule: nonzero; opacity: 1;"
                                transform=" matrix(2.8008 0 0 2.8008 1.9639999999999986 1.9639999999999702) "
                                stroke-linecap="round" />
                            <path
                                d="M 33.453 37.12 H 27.13 c -1.104 0 -2 -0.896 -2 -2 s 0.896 -2 2 -2 h 6.322 c 0.74 0 1.365 -0.625 1.365 -1.365 v -2.957 c 0 -0.74 -0.625 -1.365 -1.365 -1.365 h -3.521 c -2.958 0 -5.365 -2.407 -5.365 -5.365 v -2.957 c 0 -2.958 2.407 -5.365 5.365 -5.365 h 4.538 c 1.104 0 2 0.896 2 2 s -0.896 2 -2 2 h -4.538 c -0.74 0 -1.365 0.625 -1.365 1.365 v 2.957 c 0 0.74 0.625 1.365 1.365 1.365 h 3.521 c 2.958 0 5.365 2.407 5.365 5.365 v 2.957 C 38.818 34.713 36.411 37.12 33.453 37.12 z"
                                style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(255,255,255); fill-rule: nonzero; opacity: 1;"
                                transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                            <path
                                d="M 52.638 37.12 h -3.671 c -3.406 0 -6.178 -2.771 -6.178 -6.177 v -11.02 c 0 -3.406 2.771 -6.178 6.178 -6.178 h 3.671 c 3.406 0 6.177 2.771 6.177 6.178 v 11.02 C 58.814 34.349 56.044 37.12 52.638 37.12 z M 48.967 17.746 c -1.201 0 -2.178 0.977 -2.178 2.178 v 11.02 c 0 1.201 0.977 2.177 2.178 2.177 h 3.671 c 1.2 0 2.177 -0.977 2.177 -2.177 v -11.02 c 0 -1.201 -0.977 -2.178 -2.177 -2.178 H 48.967 z"
                                style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(255,255,255); fill-rule: nonzero; opacity: 1;"
                                transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                            <path
                                d="M 71.671 37.12 h -6.322 c -1.104 0 -2 -0.896 -2 -2 s 0.896 -2 2 -2 h 6.322 c 0.753 0 1.365 -0.612 1.365 -1.365 v -2.957 c 0 -0.753 -0.612 -1.365 -1.365 -1.365 h -3.52 c -2.958 0 -5.365 -2.407 -5.365 -5.365 v -2.957 c 0 -2.958 2.407 -5.365 5.365 -5.365 h 4.537 c 1.104 0 2 0.896 2 2 s -0.896 2 -2 2 h -4.537 c -0.753 0 -1.365 0.612 -1.365 1.365 v 2.957 c 0 0.753 0.612 1.365 1.365 1.365 h 3.52 c 2.958 0 5.365 2.407 5.365 5.365 v 2.957 C 77.036 34.713 74.629 37.12 71.671 37.12 z"
                                style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(255,255,255); fill-rule: nonzero; opacity: 1;"
                                transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                        </g>
                    </svg></a>
            @endauth

            <a class="text-sm font-medium hover:underline underline-offset-4" href="#testimonials">Testimonials</a>
            @guest
                <a class="text-sm font-medium hover:underline underline-offset-4"
                    href="{{ route('register') }}">Register</a>
                <a class="text-sm font-medium hover:underline underline-offset-4" href="{{ route('login') }}">Login</a>
            @endguest
            @auth
                <a @click="$wire.call('logout')" class="text-sm font-medium hover:underline underline-offset-4"
                    href="#">Logout</a>
            @endauth
        </div>
    </nav>
</header>
