<div x-data="{
    show: @entangle('showQuestionnaire'),
    preferences: @entangle('preferences'),
    currentStep: 1,
    totalSteps: 4,
    progress: 25,
    updateProgress() {
        this.progress = (this.currentStep / this.totalSteps) * 100;
    },
    hasAnySelection() {
        return Object.values(this.preferences).some(value => value === true);
    },
    isStepValid() {
        switch(this.currentStep) {
            case 1:
                return this.preferences.attractions || this.preferences.events;
            case 2:
                return this.preferences.restaurants || this.preferences.localCuisine;
            case 3:
                return this.preferences.shopping || this.preferences.nature;
            default:
                return true;
        }
    }
}"
x-show="show"
x-init="$watch('currentStep', value => updateProgress())"
class="fixed inset-0 overflow-hidden z-50 flex items-center justify-center subtle-bg-animation bg-slate-600/60"
x-cloak>

    <div class="relative bg-gradient-to-r from-sky-400/90 to-violet-400/90 w-full max-w-3xl mx-auto">
        <div class="glassmorphism rounded-3xl shadow-2xl overflow-hidden bg-white/95">
            <div class="p-8 sm:p-12">
                <h2 class="text-4xl flex justify-center font-extrabold text-slate-800 mb-6"><x-logo-title /> </h2>

                <div class="mb-8">
                    <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 transition-all duration-500 ease-in-out" x-bind:style="'background: linear-gradient(to right, #00c6ff ' + progress + '%, #f3f4f6 ' + progress + '%);'">
                    </div>
                    <p class="text-sm text-gray-500 mt-2" x-text="`Step ${currentStep} of ${totalSteps}`"></p>
                </div>

                <form wire:submit.prevent="savePreferences" class="space-y-8">
                    <div x-show="currentStep === 1" class="space-y-6 transition-opacity duration-500" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-90" x-transition:enter-end="opacity-100 transform scale-100">
                        <h3 class="text-2xl font-semibold text-slate-800">What excites you the most?</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="relative flex flex-col items-center justify-center p-4 rounded-xl border-2 border-slate-200 cursor-pointer transition-all duration-300 hover:border-sky-500 hover:bg-sky-50 hover:shadow-lg">
                                <input type="checkbox" wire:model="preferences.attractions" class="sr-only peer">
                                <svg class="w-16 h-16 text-gray-400 peer-checked:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span class="mt-2 text-lg font-medium text-slate-700">Attractions</span>
                            </label>
                            <label class="relative flex flex-col items-center justify-center p-4 rounded-xl border-2 border-slate-200 cursor-pointer transition-all duration-300 hover:border-sky-500 hover:bg-sky-50 hover:shadow-lg">
                                <input type="checkbox" wire:model="preferences.events" class="sr-only peer">
                                <svg class="w-16 h-16 text-gray-400 peer-checked:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span class="mt-2 text-lg font-medium text-slate-700">Events</span>
                            </label>
                        </div>
                        <p x-show="!isStepValid()" class="text-amber-600 text-sm mt-2 italic">Select at least one option to enhance your travel experience with exciting attractions and events.</p>
                    </div>

                    <div x-show="currentStep === 2" class="space-y-6 transition-opacity duration-500" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-90" x-transition:enter-end="opacity-100 transform scale-100">
                        <h3 class="text-2xl font-semibold text-gray-800">What kind of food do you prefer?</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="relative flex flex-col items-center justify-center p-4 rounded-xl border-2 border-gray-200 cursor-pointer transition-all duration-300 hover:border-blue-500 hover:shadow-lg pulsing">
                                <input type="checkbox" wire:model="preferences.restaurants" class="sr-only peer">
                                <svg class="w-16 h-16 text-gray-400 peer-checked:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.701 2.701 0 00-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7h18zm-3-9v-2a2 2 0 00-2-2H8a2 2 0 00-2 2v2h12z"></path></svg>
                                <span class="mt-2 text-lg font-medium text-gray-900">Restaurants</span>
                            </label>
                            <label class="relative flex flex-col items-center justify-center p-4 rounded-xl border-2 border-gray-200 cursor-pointer transition-all duration-300 hover:border-blue-500 hover:shadow-lg pulsing">
                                <input type="checkbox" wire:model="preferences.localCuisine" class="sr-only peer">
                                <svg class="w-16 h-16 text-gray-400 peer-checked:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span class="mt-2 text-lg font-medium text-gray-900">Local Cuisine</span>
                            </label>
                        </div>
                        <p x-show="!isStepValid()" class="text-amber-600 text-sm mt-2 italic">Choose at least one dining preference to discover amazing culinary experiences.</p>
                    </div>

                    <div x-show="currentStep === 3" class="space-y-6 transition-opacity duration-500" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-90" x-transition:enter-end="opacity-100 transform scale-100">
                        <h3 class="text-2xl font-semibold text-gray-800">How do you like to relax?</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="relative flex flex-col items-center justify-center p-4 rounded-xl border-2 border-gray-200 cursor-pointer transition-all duration-300 hover:border-blue-500 hover:shadow-lg pulsing">
                                <input type="checkbox" wire:model="preferences.shopping" class="sr-only peer">
                                <svg class="w-16 h-16 text-gray-400 peer-checked:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                <span class="mt-2 text-lg font-medium text-gray-900">Shopping</span>
                            </label>
                            <label class="relative flex flex-col items-center justify-center p-4 rounded-xl border-2 border-gray-200 cursor-pointer transition-all duration-300 hover:border-blue-500 hover:shadow-lg pulsing">
                                <input type="checkbox" wire:model="preferences.nature" class="sr-only peer">
                                <svg class="w-16 h-16 text-gray-400 peer-checked:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                                <span class="mt-2 text-lg font-medium text-gray-900">Nature</span>
                            </label>
                        </div>
                        <p x-show="!isStepValid()" class="text-amber-600 text-sm mt-2 italic">Pick at least one leisure activity to make your journey more enjoyable.</p>
                    </div>

                    <div x-show="currentStep === 4" class="space-y-6 transition-opacity duration-500" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-90" x-transition:enter-end="opacity-100 transform scale-100">
                        <h3 class="text-2xl font-semibold text-gray-800">You're ready for adventure!</h3>
                        <p class="text-lg text-gray-600">Thank you for sharing your preferences. We're ready to provide you with an unforgettable experience!</p>
                    </div>

                    @if (session('error'))
                        <div class="text-red-500 text-sm mt-2">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="flex justify-end">
                        <button
                            x-show="currentStep > 1"
                            @click="currentStep--"
                            type="button"
                            class="px-6 py-3 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors duration-300"
                        >
                            Back
                        </button>
                        <button
                            x-show="currentStep < totalSteps"
                            @click="isStepValid() && currentStep++"
                            type="button"
                            x-bind:class="{'opacity-50 cursor-not-allowed': !isStepValid()}"
                            class="px-6 py-3 bg-sky-500 text-white rounded-full hover:bg-sky-600 transition-colors duration-300"
                        >
                            Next
                        </button>
                        <button
                            x-show="currentStep === totalSteps"
                            type="submit"
                            x-bind:disabled="!isStepValid()"
                            x-bind:class="{'opacity-50 cursor-not-allowed': !isStepValid()}"
                            class="px-6 py-3 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition-colors duration-300"
                        >
                            Start Journey
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

