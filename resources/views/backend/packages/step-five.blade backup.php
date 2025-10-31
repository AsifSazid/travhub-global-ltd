<div id="itinerary-section" class="space-y-6">
    <!-- Dynamic Day Cards -->
    <div id="days-container" class="space-y-6"></div>

    <!-- Hidden input to submit itineraries as JSON -->
    <input type="hidden" name="itineraries" id="itineraries-input">

    <!-- Buttons -->
    <div class="flex justify-between mt-6">
        <button type="button" id="add-day-btn" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
            + Add Day
        </button>
    </div>

    <!-- Live JSON preview -->
    <pre id="json-preview" class="mt-6 bg-gray-100 p-4 rounded text-xs overflow-x-auto border border-gray-300"></pre>
</div>

<!-- Activity Modal -->
<div id="activity-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl p-6 transform transition-transform scale-95">
        <div class="flex items-center justify-between mb-4">
            <h3 id="modal-heading" class="text-lg font-semibold text-gray-800">Add Activity</h3>
            <button id="modal-close" type="button" class="text-gray-500 hover:text-gray-700">✕</button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Create New Activity -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Activity Title</label>
                <input id="activity-title" type="text" class="mt-1 block w-full border rounded px-3 py-2"
                    placeholder="e.g., City tour" />

                <label class="block text-sm font-medium text-gray-700 mt-3">Description</label>
                <textarea id="activity-desc" rows="4" class="mt-1 block w-full border rounded px-3 py-2"
                    placeholder="Describe the activity..."></textarea>

                <label class="block text-sm font-medium text-gray-700 mt-3">Time (optional)</label>
                <input id="activity-time" type="time" class="mt-1 block w-full border rounded px-3 py-2" />

                <div class="mt-4">
                    <button id="save-activity-btn" type="button"
                        class="px-4 py-2 bg-sky-600 text-white rounded hover:bg-sky-700">Save & Add</button>
                </div>
            </div>

            <!-- Choose from Presets -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <h4 class="font-medium text-gray-700">Choose from presets</h4>
                    <button id="refresh-presets" type="button"
                        class="text-sm text-gray-500 hover:text-gray-700">Refresh</button>
                </div>

                <div id="presets-list" class="space-y-2 max-h-60 overflow-auto p-2 border rounded bg-gray-50">
                    <!-- JS will populate preset options -->
                </div>

                <div class="mt-4 flex justify-end gap-2">
                    <button id="add-selected-presets" type="button"
                        class="px-3 py-2 bg-green-600 text-white rounded hover:bg-green-700">Add Selected</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            /* --------------------------
               Initial data + options
            -------------------------- */
            let itinerary = [];
            const presetActivities = [{
                    title: "Airport Arrival",
                    description: "Meet your driver and transfer to hotel."
                },
                {
                    title: "Welcome Dinner",
                    description: "Enjoy a traditional dinner near the hotel."
                },
                {
                    title: "City Tour",
                    description: "Guided city tour visiting main attractions."
                },
                {
                    title: "Museum Visit",
                    description: "Visit a famous museum."
                },
                {
                    title: "Free Time",
                    description: "Relax or explore on your own."
                }
            ];
            const cityOptions = ["Rome", "Florence", "Venice", "Milan", "Naples"];
            const mealOptions = ["Breakfast", "Lunch", "Dinner"];

            /* --------------------------
               DOM elements
            -------------------------- */
            const daysContainer = document.getElementById('days-container');
            const jsonPreview = document.getElementById('json-preview');
            const addDayBtn = document.getElementById('add-day-btn');
            const itinerariesInput = document.getElementById('itineraries-input');

            const activityModal = document.getElementById('activity-modal');
            const modalHeading = document.getElementById('modal-heading');
            const modalClose = document.getElementById('modal-close');
            const activityTitle = document.getElementById('activity-title');
            const activityDesc = document.getElementById('activity-desc');
            const activityTime = document.getElementById('activity-time');
            const saveActivityBtn = document.getElementById('save-activity-btn');
            const presetsList = document.getElementById('presets-list');
            const refreshPresetsBtn = document.getElementById('refresh-presets');
            const addSelectedPresetsBtn = document.getElementById('add-selected-presets');

            /* --------------------------
               Modal handling
            -------------------------- */
            let modalMode = 'add';
            let targetDayId = null;
            let editingActivityUid = null;

            function openActivityModal(mode, dayId, uid = null) {
                modalMode = mode;
                targetDayId = dayId;
                editingActivityUid = uid;

                if (mode === 'edit') {
                    modalHeading.textContent = "Edit Activity";
                    const day = itinerary.find(d => d.id == dayId);
                    const act = day.activities.find(a => a._uid === uid);
                    activityTitle.value = act.title;
                    activityDesc.value = act.description;
                    activityTime.value = act.time;
                } else {
                    modalHeading.textContent = "Add Activity";
                    activityTitle.value = '';
                    activityDesc.value = '';
                    activityTime.value = '';
                }

                populatePresets();
                activityModal.classList.remove('hidden');
                activityModal.classList.add('flex');
            }

            function closeActivityModal() {
                activityModal.classList.add('hidden');
                activityModal.classList.remove('flex');
                targetDayId = null;
                editingActivityUid = null;
            }

            modalClose.addEventListener('click', closeActivityModal);
            activityModal.addEventListener('click', e => {
                if (e.target === activityModal) closeActivityModal();
            });

            /* --------------------------
               Preset population
            -------------------------- */
            function populatePresets() {
                presetsList.innerHTML = '';
                presetActivities.forEach((p, i) => {
                    const label = document.createElement('label');
                    label.className = 'flex items-start gap-2 p-2 hover:bg-gray-100 rounded';
                    label.innerHTML = `
                <input type="checkbox" data-index="${i}">
                <div>
                    <div class="font-medium">${p.title}</div>
                    <div class="text-sm text-gray-600">${p.description}</div>
                </div>
            `;
                    presetsList.appendChild(label);
                });
            }

            refreshPresetsBtn.addEventListener('click', populatePresets);

            /* --------------------------
               Core rendering
            -------------------------- */
            function renderAll() {
                daysContainer.innerHTML = '';
                itinerary.forEach((day, i) => {
                    const div = document.createElement('div');
                    div.className = 'bg-white border rounded p-6 shadow-sm';
                    div.innerHTML = `
                <div class="flex justify-between items-start">
                    <h3 data-day-id="${day.id}" class="day-title font-semibold text-gray-800 text-lg">${day.title}</h3>
                    <button data-id="${day.id}" class="remove-day text-red-600">✕</button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-3">
                    <div>
                        <label class="text-sm text-gray-600">Date</label>
                        <input type="date" data-id="${day.id}" value="${day.date || ''}" class="day-date w-full border rounded px-2 py-1">
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Overnight Stay</label>
                        <select data-id="${day.id}" class="overnight w-full border rounded px-2 py-1">
                            ${cityOptions.map(c => `<option ${c === day.overnightStay ? 'selected':''}>${c}</option>`).join('')}
                        </select>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Meals</label>
                        <div>
                            ${mealOptions.map(m => `
                                    <label class="inline-flex items-center mr-3">
                                        <input type="checkbox" data-id="${day.id}" value="${m}" class="meal" ${day.meals.includes(m)?'checked':''}> <span>${m}</span>
                                    </label>
                                `).join('')}
                        </div>
                    </div>
                </div>

                <h4 class="mt-4 font-medium">Activities</h4>
                <div id="activities-${day.id}" class="space-y-2 mt-2">
                    ${day.activities.map(a => `
                            <div class="p-3 border rounded flex justify-between items-start bg-gray-50">
                                <div>
                                    <div class="font-medium">${a.title}</div>
                                    <div class="text-sm text-gray-600">${a.description}</div>
                                </div>
                                <div class="flex gap-1">
                                    <button data-day="${day.id}" data-uid="${a._uid}" class="edit-act text-yellow-600 text-sm">Edit</button>
                                    <button data-day="${day.id}" data-uid="${a._uid}" class="del-act text-red-600 text-sm">Del</button>
                                </div>
                            </div>
                        `).join('')}
                </div>

                <button data-id="${day.id}" type="button" class="add-activity mt-3 px-3 py-1 bg-blue-600 text-white rounded">+ Add Activity</button>
            `;

                    div.querySelector('.day-title').addEventListener('input', (e) => {
                        const id = e.target.dataset.dayId;
                        const d = itinerary.find(x => x.id == id);
                        if (d) {
                            d.title = e.target.value;
                            updateJSONPreview();
                        }
                    });
                    daysContainer.appendChild(div);
                });
                updateJSON();
            }

            /* --------------------------
               JSON + hidden input update
            -------------------------- */
            function updateJSON() {
                jsonPreview.textContent = JSON.stringify(itinerary, null, 2);
                itinerariesInput.value = JSON.stringify(itinerary);
            }

            /* --------------------------
               Add/Remove Day
            -------------------------- */
            addDayBtn.addEventListener('click', () => {
                const newDay = {
                    id: Date.now(),
                    title: `Day ${itinerary.length + 1}`,
                    date: '',
                    overnightStay: cityOptions[0],
                    meals: [],
                    activities: []
                };
                itinerary.push(newDay);
                renderAll();
            });

            daysContainer.addEventListener('click', e => {
                if (e.target.classList.contains('remove-day')) {
                    const id = e.target.dataset.id;
                    itinerary = itinerary.filter(d => d.id != id);
                    renderAll();
                }
                if (e.target.classList.contains('add-activity')) {
                    openActivityModal('add', e.target.dataset.id);
                }
                if (e.target.classList.contains('edit-act')) {
                    openActivityModal('edit', e.target.dataset.day, e.target.dataset.uid);
                }
                if (e.target.classList.contains('del-act')) {
                    const d = itinerary.find(x => x.id == e.target.dataset.day);
                    d.activities = d.activities.filter(a => a._uid !== e.target.dataset.uid);
                    renderAll();
                }
            });

            /* --------------------------
               Input listeners
            -------------------------- */
            daysContainer.addEventListener('change', e => {
                const id = e.target.dataset.id;
                const day = itinerary.find(d => d.id == id);
                if (!day) return;

                if (e.target.classList.contains('day-date')) day.date = e.target.value;
                if (e.target.classList.contains('overnight')) day.overnightStay = e.target.value;
                if (e.target.classList.contains('meal')) {
                    if (e.target.checked) day.meals.push(e.target.value);
                    else day.meals = day.meals.filter(m => m !== e.target.value);
                }
                updateJSON();
            });

            /* --------------------------
               Save Activity
            -------------------------- */
            saveActivityBtn.addEventListener('click', () => {
                const title = activityTitle.value.trim();
                const desc = activityDesc.value.trim();
                const time = activityTime.value;

                if (!title || !targetDayId) return;

                const day = itinerary.find(d => d.id == targetDayId);
                if (!day) return;

                if (modalMode === 'edit') {
                    const act = day.activities.find(a => a._uid === editingActivityUid);
                    if (act) {
                        act.title = title;
                        act.description = desc;
                        act.time = time;
                    }
                } else {
                    day.activities.push({
                        _uid: 'a' + Date.now(),
                        title,
                        description: desc,
                        time
                    });
                }

                closeActivityModal();
                renderAll();
            });

            /* --------------------------
               Add selected presets
            -------------------------- */
            addSelectedPresetsBtn.addEventListener('click', () => {
                if (!targetDayId) return;
                const day = itinerary.find(d => d.id == targetDayId);
                const checked = presetsList.querySelectorAll('input[type="checkbox"]:checked');
                checked.forEach(cb => {
                    const p = presetActivities[cb.dataset.index];
                    day.activities.push({
                        _uid: 'a' + Date.now(),
                        ...p
                    });
                });
                closeActivityModal();
                renderAll();
            });

            /* --------------------------
               Initialize one default day
            -------------------------- */
            itinerary.push({
                id: Date.now(),
                title: 'Day 1: Arrival',
                date: '',
                overnightStay: cityOptions[0],
                meals: [],
                activities: []
            });
            renderAll();
        });
    </script>
@endpush
