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
        switch (this.currentStep) {
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
}" x-show="show" x-init="$watch('currentStep', value => updateProgress())"
    class="fixed inset-0 overflow-hidden z-50 flex items-center justify-center subtle-bg-animation bg-slate-600/60"
    x-cloak>

    <div class="relative bg-gradient-to-r from-sky-400/90 to-violet-400/90 w-full max-w-3xl mx-auto">
        <div class="glassmorphism rounded-3xl shadow-2xl overflow-hidden bg-white/95">
            <div class="p-8 sm:p-12">
                <h2 class="text-4xl flex justify-center font-extrabold text-slate-800 mb-6"><x-logo-title /> </h2>

                <div class="mb-8">
                    <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 transition-all duration-500 ease-in-out"
                        x-bind:style="'background: linear-gradient(to right, #00c6ff ' + progress + '%, #f3f4f6 ' + progress + '%);'">
                    </div>
                    <p class="text-sm text-gray-500 mt-2" x-text="`Step ${currentStep} of ${totalSteps}`"></p>
                </div>

                <form wire:submit.prevent="savePreferences" class="space-y-8">
                    <div x-show="currentStep === 1" class="space-y-6 transition-opacity duration-500"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform scale-90"
                        x-transition:enter-end="opacity-100 transform scale-100">
                        <h3 class="text-2xl font-semibold text-slate-800">What excites you the most?</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <label
                                class="relative flex flex-col items-center justify-center p-4 rounded-xl border-2 border-slate-200 cursor-pointer transition-all duration-300 [&:has(input:checked)]:border-sky-500 [&:has(input:checked)]:border-4 [&:has(input:checked)]:bg-sky-50 [&:has(input:checked)]:shadow-lg">
                                <input type="checkbox" wire:model="preferences.attractions" class="sr-only peer">
                                <svg class="w-16 h-16 text-gray-400 peer-checked:text-blue-500" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                                <span class="mt-2 text-lg font-medium text-slate-700">Attractions</span>
                            </label>
                            <label
                                class="relative flex flex-col items-center justify-center p-4 rounded-xl border-2 border-slate-200 cursor-pointer transition-all duration-300 [&:has(input:checked)]:border-sky-500 [&:has(input:checked)]:border-4 [&:has(input:checked)]:bg-sky-50 [&:has(input:checked)]:shadow-lg">
                                <input type="checkbox" wire:model="preferences.events" class="sr-only peer">
                                <svg class="w-16 h-16 text-gray-400 peer-checked:text-blue-500" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <span class="mt-2 text-lg font-medium text-slate-700">Events</span>
                            </label>
                        </div>
                        <p x-show="!isStepValid()" class="text-amber-600 text-sm mt-2 italic">Select at least one option
                            to enhance your travel experience with exciting attractions and events.</p>
                    </div>

                    <div x-show="currentStep === 2" class="space-y-6 transition-opacity duration-500"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform scale-90"
                        x-transition:enter-end="opacity-100 transform scale-100">
                        <h3 class="text-2xl font-semibold text-gray-800">What kind of food do you prefer?</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <label
                                class="relative flex flex-col items-center justify-center p-4 rounded-xl border-2 border-gray-200 cursor-pointer transition-all duration-300 [&:has(input:checked)]:border-sky-500 [&:has(input:checked)]:border-4 [&:has(input:checked)]:bg-sky-50 [&:has(input:checked)]:shadow-lg">
                                <input type="checkbox" wire:model="preferences.restaurants" class="sr-only peer">
                                <svg height="800px" width="800px" version="1.1" id="Layer_1" class="w-16 h-16"
                                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    viewBox="0 0 512 512" xml:space="preserve">
                                    <path style="fill:#E0E0E2;" d="M503.291,48.732c-20.381,12.446-32.814,34.605-32.814,58.486v158.807h15.956h17.962V60.974V48.058
                               L503.291,48.732z" />
                                    <path style="fill:#C6C5CB;" d="M504.396,60.974V48.058l-1.104,0.674c-20.381,12.446-32.814,34.605-32.814,58.486v158.807h15.956
                               V107.218C486.432,89.873,492.996,73.441,504.396,60.974z" />
                                    <g>
                                        <path style="fill:#E0E0E2;"
                                            d="M486.432,107.218v158.807h17.962V60.974C492.996,73.441,486.432,89.873,486.432,107.218z" />
                                        <path style="fill:#E0E0E2;" d="M69.761,143.727H9.393H7.604v5.507c0,17.658,14.314,31.972,31.972,31.972l0,0
                                   c17.658,0,31.972-14.314,31.972-31.972v-5.507H69.761z" />
                                    </g>
                                    <path style="fill:#C6C5CB;" d="M39.577,165.155L39.577,165.155c-13.961,0-25.826-8.953-30.184-21.427H7.604v5.507
                               c0,17.658,14.314,31.972,31.972,31.972l0,0c17.658,0,31.972-14.314,31.972-31.972v-5.507h-1.789
                               C65.402,156.202,53.538,165.155,39.577,165.155z" />
                                    <circle style="fill:#D2E7F8;" cx="256.751" cy="282.159" r="187.745" />
                                    <circle style="fill:#B3D8F5;" cx="256.751" cy="282.159" r="118.424" />
                                    <path d="M256.747,86.809C149.033,86.809,61.4,174.442,61.4,282.156c0,107.715,87.633,195.348,195.347,195.348
                               c107.715,0,195.347-87.633,195.347-195.348C452.094,174.441,364.462,86.809,256.747,86.809z M256.747,462.295
                               c-99.328,0-180.138-80.81-180.138-180.139s80.81-180.138,180.138-180.138c99.329,0,180.138,80.809,180.138,180.138
                               S356.076,462.295,256.747,462.295z" />
                                    <path d="M145.742,282.156c0-61.208,49.797-111.004,111.005-111.004c17.825,0,34.844,4.093,50.584,12.167l6.941-13.534
                               c-17.908-9.185-37.262-13.842-57.524-13.842c-69.594,0-126.214,56.619-126.214,126.213c0,11.232,1.478,22.375,4.392,33.119
                               l14.678-3.983C147.04,301.847,145.742,292.044,145.742,282.156z" />
                                    <path d="M156.761,330.436l-13.691,6.624c7.979,16.492,19.725,31.348,33.97,42.961l9.611-11.787
                               C174.116,358.013,163.78,344.943,156.761,330.436z" />
                                    <path
                                        d="M333.721,182.124l-9.282,12.046c27.526,21.212,43.314,53.281,43.314,87.985c0,61.208-49.797,111.005-111.005,111.005
                               c-21.791,0-42.88-6.309-60.991-18.241l-8.368,12.699c20.602,13.575,44.585,20.751,69.359,20.751
                               c69.595,0,126.214-56.619,126.214-126.214C382.96,242.698,365.013,206.238,333.721,182.124z" />
                                    <path
                                        d="M79.097,151.331h0.057v-2.098v-13.111V48.058H63.945v88.065H47.181V48.058H31.972v88.065H15.209V48.058H0v88.065v13.111
                               v2.098h0.057c0.96,18.283,14.386,33.312,31.916,36.738v281.83h15.209V188.07C64.71,184.643,78.136,169.615,79.097,151.331z
                                M39.577,173.602c-12.73,0-23.211-9.813-24.278-22.27h48.557C62.787,163.789,52.306,173.602,39.577,173.602z" />
                                    <path d="M462.872,107.218V273.63h33.919V469.9H512v-196.27v-92.423V34.496l-12.672,7.747
                               C476.841,55.975,462.872,80.87,462.872,107.218z M496.791,181.206v77.215h-18.711V107.217c0-16.624,6.948-32.525,18.711-43.896
                               V181.206z" />
                                </svg> <span class="mt-2 text-lg font-medium text-gray-900">Restaurants</span>
                            </label>
                            <label
                                class="relative flex flex-col items-center justify-center p-4 rounded-xl border-2 border-gray-200 cursor-pointer transition-all duration-300 [&:has(input:checked)]:border-sky-500 [&:has(input:checked)]:border-4 [&:has(input:checked)]:bg-sky-50 [&:has(input:checked)]:shadow-lg">
                                <input type="checkbox" wire:model="preferences.localCuisine" class="sr-only peer">
                                <svg version="1.1" id="_x34_" xmlns="http://www.w3.org/2000/svg" class="w-16 h-16"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512"
                                    xml:space="preserve">
                                    <g>
                                        <g>
                                            <path style="fill:#7F7F7F;"
                                                d="M75.14,199.931c0,9.175-7.425,16.603-16.6,16.603H16.6c-9.175,0-16.6-7.428-16.6-16.603l0,0
                                       c0-9.176,7.425-16.598,16.6-16.598H58.54C67.715,183.333,75.14,190.755,75.14,199.931L75.14,199.931z" />
                                            <path style="fill:#7F7F7F;"
                                                d="M512,199.931c0,9.175-7.435,16.603-16.604,16.603h-41.939c-9.168,0-16.604-7.428-16.604-16.603l0,0
                                       c0-9.176,7.435-16.598,16.604-16.598h41.939C504.565,183.333,512,190.755,512,199.931L512,199.931z" />
                                        </g>
                                        <path style="fill:#E3E2E2;" d="M456.41,136.148v186.345c0,38.722-31.307,69.901-69.894,69.901h-261
                                   c-38.582,0-69.895-31.178-69.895-69.901V136.148c0-38.581,31.313-69.895,69.895-69.895h261
                                   C425.104,66.254,456.41,97.567,456.41,136.148z" />
                                        <rect x="55.621" y="147.75" style="fill:#CECECF;" width="400.789"
                                            height="37.323" />
                                        <path style="fill:#75A6C2;"
                                            d="M475.017,152.476c0,10.285-8.346,18.633-18.645,18.633H55.634c-10.312,0-18.648-8.347-18.648-18.633
                                   l0,0c0-10.312,8.337-18.652,18.648-18.652h400.738C466.671,133.824,475.017,142.164,475.017,152.476L475.017,152.476z" />
                                        <g>
                                            <path style="fill:#7F7F7F;"
                                                d="M312.795,64.501c0,4.835-3.917,8.738-8.745,8.738h-96.103c-4.815,0-8.745-3.903-8.745-8.738l0,0
                                       c0-4.834,3.93-8.732,8.745-8.732h96.103C308.878,55.769,312.795,59.667,312.795,64.501L312.795,64.501z" />
                                            <rect x="238.804" y="25.182" style="fill:#727272;" width="34.401"
                                                height="34.954" />
                                            <path style="fill:#7F7F7F;" d="M304.371,22.645c0,12.495-10.157,22.639-22.639,22.639h-51.429
                                       c-12.507,0-22.678-10.145-22.678-22.639l0,0C207.626,10.144,217.796,0,230.303,0h51.429C294.214,0,304.371,10.144,304.371,22.645
                                       L304.371,22.645z" />
                                        </g>
                                        <path style="opacity:0.1;fill:#565657;" d="M495.396,183.333H456.41v-12.237c10.299-0.012,18.607-8.347,18.607-18.619
                                   c0-10.312-8.346-18.652-18.645-18.652h-0.193c-1.258-37.483-31.859-67.571-69.663-67.571h-74.081c0.128-0.59,0.36-1.124,0.36-1.753
                                   c0-4.834-3.917-8.732-8.745-8.732h-30.844V45.284h8.527c12.481,0,22.639-10.145,22.639-22.639C304.371,10.144,294.214,0,281.732,0
                                   h-27.609v392.394h132.393c38.587,0,69.894-31.178,69.894-69.901v-105.96h38.986c9.168,0,16.604-7.428,16.604-16.603
                                   C512,190.755,504.565,183.333,495.396,183.333z" />
                                    </g>
                                </svg> <span class="mt-2 text-lg font-medium text-gray-900">Local Cuisine</span>
                            </label>
                        </div>
                        <p x-show="!isStepValid()" class="text-amber-600 text-sm mt-2 italic">Choose at least one
                            dining preference to discover amazing culinary experiences.</p>
                    </div>

                    <div x-show="currentStep === 3" class="space-y-6 transition-opacity duration-500"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform scale-90"
                        x-transition:enter-end="opacity-100 transform scale-100">
                        <h3 class="text-2xl font-semibold text-gray-800">How do you like to relax?</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <label
                                class="relative flex flex-col items-center justify-center p-4 rounded-xl border-2 border-gray-200 cursor-pointer transition-all duration-300 [&:has(input:checked)]:border-sky-500 [&:has(input:checked)]:border-4 [&:has(input:checked)]:bg-sky-50 [&:has(input:checked)]:shadow-lg">
                                <input type="checkbox" wire:model="preferences.shopping" class="sr-only peer">
                                <svg width="800px" height="800px" viewBox="0 0 1024 1024" class="w-16 h-16"
                                    version="1.1" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M687.7 833.8h-76.8c-16.6 0-30-13.4-30-30s13.4-30 30-30h76.8c16.6 0 30 13.4 30 30s-13.4 30-30 30zM480.7 833.8H136.8c-16.6 0-30-13.4-30-30s13.4-30 30-30h343.9c16.6 0 30 13.4 30 30s-13.4 30-30 30z"
                                        fill="#33CC99" />
                                    <path
                                        d="M880.8 931H207.9c-25.3 0-45.9-20.7-45.9-45.9 0-25.3 20.7-45.9 45.9-45.9h672.9c25.3 0 45.9 20.7 45.9 45.9S906 931 880.8 931z"
                                        fill="#FFB89A" />
                                    <path
                                        d="M703 122.7c20.9 0 40.6 8.2 55.5 23.2 14.9 14.9 23.2 34.7 23.2 55.5v2.8l0.3 2.8 57.7 611.8c-0.6 20-8.8 38.7-23.1 53.1-14.9 14.9-34.7 23.2-55.5 23.2H236c-20.9 0-40.6-8.2-55.5-23.2-14.4-14.4-22.6-33.2-23.1-53.2l54.7-612 0.2-2.7v-2.7c0-20.9 8.2-40.6 23.2-55.5 14.9-14.9 34.7-23.2 55.5-23.2h412m0-59.9H291c-76.3 0-138.7 62.4-138.7 138.7l-55 615c0 76.3 62.4 138.7 138.7 138.7h525c76.3 0 138.7-62.4 138.7-138.7l-58-615c0-76.3-62.4-138.7-138.7-138.7z"
                                        fill="#45484C" />
                                    <path
                                        d="M712.6 228.8c0-24.9-20.1-45-45-45s-45 20.1-45 45c0 13.5 6 25.6 15.4 33.9-0.3 1.6-0.4 3.3-0.4 5v95.9c0 23.5-9.2 45.7-26 62.5-16.8 16.8-39 26-62.5 26h-88.5c-23.5 0-45.7-9.2-62.5-26-16.8-16.8-26-39-26-62.5v-95.9c0-1.7-0.1-3.4-0.4-5 9.4-8.2 15.4-20.4 15.4-33.9 0-24.9-20.1-45-45-45s-45 20.1-45 45c0 13.5 6 25.6 15.4 33.9-0.3 1.6-0.4 3.3-0.4 5v95.9c0 81.9 66.6 148.6 148.6 148.6h88.5c81.9 0 148.6-66.6 148.6-148.6v-95.9c0-1.7-0.1-3.4-0.4-5 9.3-8.3 15.2-20.4 15.2-33.9z"
                                        fill="#45484C" />
                                </svg> <span class="mt-2 text-lg font-medium text-gray-900">Shopping</span>
                            </label>
                            <label
                                class="relative flex flex-col items-center justify-center p-4 rounded-xl border-2 border-gray-200 cursor-pointer transition-all duration-300 [&:has(input:checked)]:border-sky-500 [&:has(input:checked)]:border-4 [&:has(input:checked)]:bg-sky-50 [&:has(input:checked)]:shadow-lg">
                                <input type="checkbox" wire:model="preferences.nature" class="sr-only peer">
                                <svg width="800px" height="800px" viewBox="0 0 64 64" class="w-16 h-16"
                                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    aria-hidden="true" role="img" class="iconify iconify--emojione"
                                    preserveAspectRatio="xMidYMid meet">

                                    <path fill="#d6eef0" d="M0 0h64v64H0z">

                                    </path>

                                    <path d="M28 38c-.5-1.3-1.3-2.5-2.1-3.3L7 17.1c-1.6-1.5-4.2-1.5-5.8 0L0 18.2V38h28z"
                                        fill="#d0d0d0">

                                    </path>

                                    <g fill="#83bf4f">

                                        <path d="M0 32c.1 0 0 0 0 0">

                                        </path>

                                        <path
                                            d="M50.2 34.5c-3.2-1.4-6.8-2.2-10.4-2.2c-3.6 0-6.8.8-9.4 2.1c-5.6 2.5-13 2.4-19.8-.1C7.4 32.9 3.6 32 0 32v32h64V36.3c-4.4.5-9.2-.1-13.8-1.8">

                                        </path>

                                    </g>

                                    <g fill="#f9f3d9">

                                        <path d="M0 52h16v12H0z">

                                        </path>

                                        <path d="M48 52h16v12H48z">

                                        </path>

                                    </g>

                                    <g fill="#d0d0d0">

                                        <path d="M0 48h18v4H0z">

                                        </path>

                                        <path d="M46 48h18v4H46z">

                                        </path>

                                        <path d="M2 55h4v2H2z">

                                        </path>

                                        <path d="M10 57h4v2h-4z">

                                        </path>

                                        <path d="M54 60h4v2h-4z">

                                        </path>

                                        <path d="M58 54h4v2h-4z">

                                        </path>

                                    </g>

                                    <path
                                        d="M29.7 45.8c-10-3.5-8.4-8.3-7.7-9.6h-2.2c-1.8 1.5-6 5.3 4.5 11.6c11.8 7.1.4 16.2.4 16.2h17s4.8-12.4-12-18.2"
                                        fill="#f9f3d9">

                                    </path>

                                    <g fill="#3e4347">

                                        <path d="M14 28h2v20h-2z">

                                        </path>

                                        <path d="M48 28h2v20h-2z">

                                        </path>

                                    </g>

                                    <g fill="#699635">

                                        <circle cx="4" cy="41" r="2">

                                        </circle>

                                        <circle cx="4" cy="39" r="1">

                                        </circle>

                                        <circle cx="10" cy="41" r="2">

                                        </circle>

                                        <circle cx="7" cy="40" r="3">

                                        </circle>

                                    </g>

                                    <path fill="#89664c" d="M56 38h2v6h-2z">

                                    </path>

                                    <path
                                        d="M58.6 29.2c-.9-1.7-2.3-1.7-3.2 0L52.3 35c-.9 1.7 0 3 2 3h5.4c2 0 2.9-1.4 2-3l-3.1-5.8"
                                        fill="#699635">

                                    </path>

                                    <path
                                        d="M58.3 26c-.7-1.3-1.9-1.3-2.6 0l-2.4 4.6c-.7 1.3 0 2.4 1.6 2.4h4.3c1.6 0 2.3-1.1 1.6-2.4L58.3 26"
                                        fill="#75a843">

                                    </path>

                                    <path
                                        d="M58 22.7c-.5-1-1.4-1-1.9 0l-1.8 3.5c-.5 1 0 1.8 1.2 1.8h3.2c1.2 0 1.7-.8 1.2-1.8L58 22.7"
                                        fill="#83bf4f">

                                    </path>

                                    <circle cx="52" cy="12" r="7" fill="#ffe62e">

                                    </circle>

                                    <g fill="#ffffff">

                                        <path d="M22 7c0 1.1-.9 2-2 2H10c-1.1 0-2-.9-2-2s.9-2 2-2h10c1.1 0 2 .9 2 2">

                                        </path>

                                        <path d="M26 9c0 1.1-.9 2-2 2h-4c-1.1 0-2-.9-2-2s.9-2 2-2h4c1.1 0 2 .9 2 2">

                                        </path>

                                        <path d="M40 12c0 1.1-.9 2-2 2h-4c-1.1 0-2-.9-2-2s.9-2 2-2h4c1.1 0 2 .9 2 2">

                                        </path>

                                    </g>

                                    <path d="M49 22H15c-2.2 0-4 1.8-4 4s1.8 4 4 4h34c2.2 0 4-1.8 4-4s-1.8-4-4-4"
                                        fill="#89664c">

                                    </path>

                                    <g fill="#f9f3d9">

                                        <circle cx="15" cy="26" r="1">

                                        </circle>

                                        <circle cx="49" cy="26" r="1">

                                        </circle>

                                    </g>

                                </svg> <span class="mt-2 text-lg font-medium text-gray-900">Nature</span>
                            </label>
                        </div>
                        <p x-show="!isStepValid()" class="text-amber-600 text-sm mt-2 italic">Pick at least one
                            leisure activity to make your journey more enjoyable.</p>
                    </div>

                    <div x-show="currentStep === 4" class="space-y-6 transition-opacity duration-500"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform scale-90"
                        x-transition:enter-end="opacity-100 transform scale-100">
                        <h3 class="text-2xl font-semibold text-gray-800">You're ready for adventure!</h3>
                        <p class="text-lg text-gray-600">Thank you for sharing your preferences. We're ready to provide
                            you with an unforgettable experience!</p>
                    </div>

                    @if (session('error'))
                        <div class="text-red-500 text-sm mt-2">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="flex justify-end">
                        <button x-show="currentStep > 1" @click="currentStep--" type="button"
                            class="px-6 py-3 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors duration-300">
                            Back
                        </button>
                        <button x-show="currentStep < totalSteps" @click="isStepValid() && currentStep++"
                            type="button" x-bind:class="{ 'opacity-50 cursor-not-allowed': !isStepValid() }"
                            class="px-6 py-3 bg-sky-500 text-white rounded-full hover:bg-sky-600 transition-colors duration-300">
                            Next
                        </button>
                        <button x-show="currentStep === totalSteps" type="submit" x-bind:disabled="!isStepValid()"
                            x-bind:class="{ 'opacity-50 cursor-not-allowed': !isStepValid() }"
                            class="px-6 py-3 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition-colors duration-300">
                            Start Journey
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
