<x-backend.layouts.master>
    <x-slot name="header">
        <div class="flex items-center justify-between px-4 py-4 border-b lg:py-6 dark:border-primary-darker">
            <h2 class="text-2xl font-semibold">
                {{ __('Create Package (Step-by-step)') }}
            </h2>
        </div>
    </x-slot>

    <!-- Progress -->
    <div class="mb-6">
        <div class="w-full bg-gray-200 h-2 rounded-full overflow-hidden">
            <div class="h-2 bg-indigo-600 transition-all duration-300" :style="`width: ${(step/totalSteps)*100}%`">
            </div>
        </div>
        <div class="flex gap-3 mt-3 flex-wrap">
            <template x-for="i in totalSteps" :key="i">
                <div class="flex items-center gap-2">
                    <div class="step-dot"
                        :class="i < step ? 'bg-green-500' : (i === step ? 'bg-indigo-600' : 'bg-gray-300')">
                    </div>
                    <div class="text-xs text-gray-600" x-text="stepTitles[i-1]"></div>
                </div>
            </template>
        </div>
    </div>

    <!-- Steps -->
    <form @submit.prevent="submitCurrentStep" class="space-y-6">

        <!-- Step 1 -->
        <div x-show="step===1" x-cloak>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium">Package Title <span class="text-red-500">*</span></label>
                    <input x-model="form.title" type="text" class="mt-1 w-full border rounded px-3 py-2" />
                    <p class="text-red-600 text-sm mt-1" x-text="errors.title"></p>
                </div>

                <div>
                    <label class="block text-sm font-medium">Status</label>
                    <select x-model="form.status" class="mt-1 w-full border rounded px-3 py-2">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium">Description</label>
                    <textarea x-model="form.description" class="mt-1 w-full border rounded px-3 py-2"></textarea>
                    <p class="text-red-600 text-sm mt-1" x-text="errors.description"></p>
                </div>
            </div>
        </div>

        <!-- Step 2 -->
        <div x-show="step===2" x-cloak x-data="citySelector($root)" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium">Country</label>
                    <select x-model="selectedCountry" @change="loadCities" class="mt-1 w-full border rounded px-3 py-2">
                        <option value="">-- Select country --</option>
                        @foreach (\App\Models\Country::where('status', 'active')->get() as $c)
                            <option value="{{ $c->id }}">{{ $c->title }}</option>
                        @endforeach
                    </select>
                    <p class="text-red-600 text-sm mt-1" x-text="$root.errors.country_id"></p>
                </div>

                <div>
                    <label class="block text-sm font-medium">Activity Category</label>
                    <select x-model="$root.form.activity_category_id" class="mt-1 w-full border rounded px-3 py-2">
                        <option value="">-- Select activity --</option>
                        @foreach (\App\Models\Activity::where('activity_category_id', 1)->get() as $a)
                            <option value="{{ $a->id }}">{{ $a->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <template x-if="cities.length > 0">
                <div>
                    <label class="block text-sm font-medium mb-2">Select Cities</label>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="city in cities" :key="city.id">
                            <label class="flex items-center space-x-2 border px-3 py-1 rounded cursor-pointer">
                                <input type="checkbox" :value="city.id" x-model="selectedCityIds"
                                    class="accent-indigo-600">
                                <span x-text="city.title"></span>
                            </label>
                        </template>
                    </div>
                </div>
            </template>
            <input type="hidden" name="cities" :value="JSON.stringify(selectedCities)">
        </div>

        <!-- Step 3 -->
        <div x-show="step===3" x-cloak>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium">Duration (days)</label>
                    <input x-model="form.duration" type="number" min="1"
                        class="mt-1 w-full border rounded px-3 py-2" />
                </div>

                <div>
                    <label class="block text-sm font-medium">Start Date</label>
                    <input x-model="form.start_date" type="date" class="mt-1 w-full border rounded px-3 py-2" />
                </div>

                <div>
                    <label class="block text-sm font-medium">End Date</label>
                    <input x-model="form.end_date" type="date" class="mt-1 w-full border rounded px-3 py-2" />
                </div>

                <div>
                    <label class="block text-sm font-medium">No of Pax (JSON)</label>
                    <input x-model="form.no_of_pax" type="text" class="mt-1 w-full border rounded px-3 py-2"
                        placeholder='{"adult":2,"child":0}' />
                </div>
            </div>
        </div>

        <!-- Step 4 -->
        <div x-show="step===4" x-cloak>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium">Accommodation Cities (comma separated)</label>
                    <input x-model="form.accomo_cities" type="text" class="mt-1 w-full border rounded px-3 py-2" />
                </div>

                <div>
                    <label class="block text-sm font-medium">Hotels (JSON)</label>
                    <input x-model="form.hotels" type="text" class="mt-1 w-full border rounded px-3 py-2"
                        placeholder='[{"city":"Rome","hotel":"Grand"}]' />
                </div>
            </div>
        </div>

        <!-- Step 5 -->
        <div x-show="step===5" x-cloak>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium">Currency</label>
                    <select x-model="form.currency_id" class="mt-1 w-full border rounded px-3 py-2">
                        <option value="">-- Currency --</option>
                        @foreach (\App\Models\Currency::all() as $cur)
                            <option value="{{ $cur->id }}">{{ $cur->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium">Air Ticket Details (JSON)</label>
                    <textarea x-model="form.air_ticket_details" rows="3" class="mt-1 w-full border rounded px-3 py-2"></textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium">Price Options (option|price)</label>
                    <textarea x-model="form.price_options" rows="3" class="mt-1 w-full border rounded px-3 py-2"
                        placeholder="Option1|1850\nOption2|2150"></textarea>
                </div>
            </div>
        </div>

        <!-- Step 6 -->
        <div x-show="step===6" x-cloak>
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-sm font-medium">Itineraries (JSON)</label>
                    <textarea x-model="form.itenaries" rows="4" class="mt-1 w-full border rounded px-3 py-2"
                        placeholder='[{"day":1,"title":"Arrival","activities":["transfer","dinner"]}]'></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium">Inclusions (comma separated)</label>
                    <input x-model="form.inclusions" type="text" class="mt-1 w-full border rounded px-3 py-2"
                        placeholder="Airport transfers, Breakfast" />
                </div>
            </div>
        </div>

        <!-- Step 7: Confirmation -->
        <div x-show="step===7" x-cloak class="text-center">
            <h2 class="text-lg font-semibold mb-3">Review & Submit</h2>
            <p class="text-gray-600">Everything looks good! Click “Save & Finish” to finalize.</p>
        </div>

        <!-- Buttons -->
        <div class="flex items-center justify-between mt-8">
            <button type="button" @click="prevStep" :disabled="step === 1"
                class="px-4 py-2 border rounded disabled:opacity-50">Back</button>

            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition"
                x-text="step < totalSteps ? 'Save & Next' : 'Save & Finish'"></button>
        </div>

        <div x-show="genericError" class="text-red-600 text-sm mt-3" x-text="genericError"></div>
    </form>

    @push('js')
        <script>
            function packageForm() {
                return {
                    step: 1,
                    totalSteps: 7,
                    stepTitles: ['Basic', 'Destination', 'Quotation', 'Accommodation', 'Pricing', 'Itinerary', 'Confirm'],
                    submitting: false,
                    genericError: '',
                    form: {
                        uuid: '',
                        title: '',
                        description: '',
                        status: 'active',
                        country_id: '',
                        activity_category_id: '',
                        cities: '',
                        duration: '',
                        start_date: '',
                        end_date: '',
                        no_of_pax: '',
                        accomo_cities: '',
                        hotels: '',
                        currency_id: '',
                        air_ticket_details: '',
                        price_options: '',
                        itenaries: '',
                        inclusions: ''
                    },
                    errors: {},

                    init() {
                        // Read uuid & step from query params (resume support)
                        const urlParams = new URLSearchParams(window.location.search);
                        const uuid = urlParams.get('uuid');
                        const step = parseInt(urlParams.get('step')) || 1;

                        if (uuid) this.form.uuid = uuid;
                        this.step = step;
                    },

                    prevStep() {
                        if (this.step > 1) this.step--;
                        this.genericError = '';
                        this.errors = {};
                    },

                    async submitCurrentStep() {
                        this.submitting = true;
                        this.genericError = '';
                        this.errors = {};

                        const token = document.querySelector('meta[name="csrf-token"]').content;

                        // ✅ Safe URL (no Laravel route() here)
                        const url = `/packages/step/${this.step}`;

                        // Send all current form data including uuid
                        const payload = {
                            ...this.form
                        };

                        try {
                            const res = await fetch(url, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': token
                                },
                                body: JSON.stringify(payload)
                            });

                            const json = await res.json();

                            if (!res.ok) {
                                if (json.errors) this.errors = json.errors;
                                else this.genericError = json.message || 'Something went wrong.';
                                this.submitting = false;
                                return;
                            }

                            // ✅ If step 1 returns uuid, store it and redirect to step 2
                            if (json.data?.package_uuid || json.data?.uuid) {
                                const newUuid = json.data.package_uuid ?? json.data.uuid;
                                this.form.uuid = newUuid;

                                // Redirect with UUID in URL to continue later if needed
                                if (this.step === 1) {
                                    window.location.href = `/packages/create?uuid=${newUuid}&step=2`;
                                    return;
                                }
                            }

                            // ✅ Move to next step or finish
                            if (this.step < this.totalSteps) {
                                const nextStep = this.step + 1;
                                this.step = nextStep;
                                // Update URL so reload resumes same step
                                const baseUrl = `/packages/create?uuid=${this.form.uuid}&step=${nextStep}`;
                                window.history.replaceState(null, '', baseUrl);
                                window.scrollTo({
                                    top: 0,
                                    behavior: 'smooth'
                                });
                            } else {
                                // ✅ Final redirect after last step
                                window.location.href = `/packages`;
                            }

                        } catch (e) {
                            console.error(e);
                            this.genericError = 'Network error. Try again.';
                        } finally {
                            this.submitting = false;
                        }
                    }
                }
            }

            function citySelector(root) {
                return {
                    selectedCountry: '',
                    cities: [],
                    selectedCityIds: [],
                    selectedCities: [],

                    async loadCities() {
                        if (!this.selectedCountry) {
                            this.cities = [];
                            this.selectedCityIds = [];
                            this.selectedCities = [];
                            root.form.cities = '';
                            return;
                        }
                        try {
                            const res = await fetch(`/api/countries/${this.selectedCountry}/cities`);
                            this.cities = await res.json();
                            this.selectedCityIds = [];
                            this.selectedCities = [];
                            root.form.cities = '';
                        } catch (err) {
                            console.error('Error loading cities', err);
                        }
                    },

                    // ✅ Live city selection watcher
                    $watch('selectedCityIds', (ids) => {
                        this.selectedCities = this.cities
                            .filter(c => ids.includes(c.id.toString()))
                            .map(c => ({
                                id: c.id,
                                uuid: c.uuid,
                                title: c.title
                            }));

                        root.form.cities = JSON.stringify(this.selectedCities);
                    })
                };
            }
        </script>
    @endpush

</x-backend.layouts.master>