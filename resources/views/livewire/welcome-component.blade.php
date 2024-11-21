
<div class="flex flex-col min-h-screen">
    <x-barapp />
    <main class="flex-1">
        <section class="w-full py-12 md:py-24 lg:py-32 xl:py-48 bg-blue-500">
            <div class="container px-4 md:px-6 mx-auto">
                <div class="flex flex-col items-center space-y-4 text-center">
                    <div class="space-y-2">
                        <h1 class="text-3xl font-bold tracking-tighter sm:text-4xl md:text-5xl lg:text-6xl/none text-white">
                            Track Your Trip Expenses with Ease
                        </h1>
                        <p class="mx-auto max-w-[700px] text-gray-200 md:text-xl">
                            ExpensaGO helps you manage your travel expenses effortlessly. Stay on budget and enjoy your trips worry-free.
                        </p>
                    </div>
                    <div class="space-x-4">
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none ring-offset-background bg-white text-primary hover:bg-gray-100 h-11 px-8">
                            Get Started
                        </a>
                        <a href="#features" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none ring-offset-background border border-input hover:bg-accent hover:text-accent-foreground h-11 px-8 text-white border-white hover:bg-white/10">
                            Learn More
                        </a>
                    </div>
                </div>
            </div>
        </section>
        <section id="features" class="w-full py-12 md:py-24 lg:py-32 bg-gray-100">
            <div class="container px-4 md:px-6 mx-auto">
                <h2 class="text-3xl font-bold tracking-tighter sm:text-5xl text-center mb-12">Key Features</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="rounded-lg border bg-white shadow-sm p-6">
                        <div class="flex flex-col items-center space-y-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-12 h-12 text-primary"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                            <h3 class="text-2xl font-bold text-center">Expense Tracking</h3>
                            <p class="text-center text-gray-500">
                                Easily log and categorize your expenses on-the-go.
                            </p>
                        </div>
                    </div>
                    <div class="rounded-lg border bg-white shadow-sm p-6">
                        <div class="flex flex-col items-center space-y-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-12 h-12 text-primary"><circle cx="12" cy="12" r="10"></circle><polyline points="8 12 12 16 16 12"></polyline><line x1="12" y1="8" x2="12" y2="16"></line></svg>
                            <h3 class="text-2xl font-bold text-center">Budget Analysis</h3>
                            <p class="text-center text-gray-500">
                                Get insights into your spending habits with detailed reports.
                            </p>
                        </div>
                    </div>
                    <div class="rounded-lg border bg-white shadow-sm p-6">
                        <div class="flex flex-col items-center space-y-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-12 h-12 text-primary"><path d="M17.8 19.2L16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z"></path></svg>
                            <h3 class="text-2xl font-bold text-center">Trip Organization</h3>
                            <p class="text-center text-gray-500">
                                Organize expenses by trip for better management.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section id="testimonials" class="w-full py-12 md:py-24 lg:py-32" x-data="{
testimonials: [
    { id: 1, name: 'John Doe', avatar: 'pic.jpeg', content: 'ExpensaGO is amazing! It has helped me keep track of my expenses effortlessly.' },
    { id: 2, name: 'Jane Smith', avatar: 'pic.jpeg', content: 'I love using ExpensaGO for my trips. It makes budgeting so much easier.' },
    { id: 3, name: 'Sam Wilson', avatar: 'pic.jpeg', content: 'A must-have app for travelers. Highly recommend ExpensaGO!' }
]
}" x-init="console.log(testimonials)">
            <div class="container px-4 md:px-6 mx-auto">
                <h2 class="text-3xl font-bold tracking-tighter sm:text-5xl text-center mb-12">What Our Users Say</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <template x-for="testimonial in testimonials" :key="testimonial.id">
                        <div class="rounded-lg border bg-white shadow-sm p-6">
                            <div class="flex flex-col space-y-4">
                                <div class="flex items-center space-x-4">
                                    <img :src="testimonial.avatar" alt="User avatar" class="rounded-full w-10 h-10">
                                    <div>
                                        <p class="text-lg font-semibold" x-text="testimonial.name"></p>
                                        <div class="flex">
                                            <template x-for="i in 5" :key="i">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-yellow-400"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-gray-500" x-text="testimonial.content"></p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </section>
        <section id="cta" class="w-full py-12 md:py-24 lg:py-32 bg-blue-300">
            <div class="container px-4 md:px-6 mx-auto">
                <div class="flex flex-col items-center space-y-4 text-center">
                    <div class="space-y-2">
                        <h2 class="text-3xl font-bold tracking-tighter sm:text-4xl md:text-5xl text-white">
                            Ready to Simplify Your Trip Expenses?
                        </h2>
                        <p class="mx-auto max-w-[600px] text-gray-200 md:text-xl">
                            Join thousands of happy travelers who have taken control of their expenses with ExpensaGO.
                        </p>
                    </div>
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none ring-offset-background bg-white text-primary hover:bg-gray-100 h-11 px-8">
                        Start Your Free Trial
                    </a>
                </div>
            </div>
        </section>
    </main>
    <footer class="flex flex-col gap-2 sm:flex-row py-6 w-full shrink-0 items-center px-4 md:px-6 border-t">
        <p class="text-xs text-gray-500">© {{ date('Y') }} ExpensaGO. All rights reserved.</p>
        <nav class="sm:ml-auto flex gap-4 sm:gap-6">
            <a class="text-xs hover:underline underline-offset-4" href="#">Terms of Service</a>
            <a class="text-xs hover:underline underline-offset-4" href="#">Privacy</a>
        </nav>
    </footer>
</div>
