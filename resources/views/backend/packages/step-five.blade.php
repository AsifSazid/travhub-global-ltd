<input type="hidden" name="itenary" id="itenary-input">

<div class="max-w-5xl mx-auto">
    <header class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Itinerary Builder</h1>
        <div class="flex items-center gap-3">
            <button id="add-day-btn" type="button"
                class="px-4 py-2 bg-blue-600 text-white rounded shadow hover:bg-blue-700">
                + Add Day
            </button>
            <button id="export-json-btn" type="button"
                class="px-4 py-2 bg-emerald-600 text-white rounded hover:bg-emerald-700">
                Export JSON
            </button>
        </div>
    </header>

    <!-- Days Container -->
    <div id="days-container" class="space-y-6"></div>

    <!-- JSON Preview -->
    <div class="mt-8">
        <h2 class="text-lg font-medium text-gray-700 mb-2">JSON Preview</h2>
        <pre id="json-preview" class="p-4 bg-white border rounded text-sm text-gray-700 overflow-x-auto max-h-64"></pre>
    </div>
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

            <!-- Choose from Presets (multiple select) -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <h4 class="font-medium text-gray-700">Choose from presets</h4>
                    <button id="refresh-presets" type="button"
                        class="text-sm text-gray-500 hover:text-gray-700">Refresh</button>
                </div>

                <div id="presets-list" class="space-y-2 max-h-60 overflow-auto p-2 border rounded bg-gray-50">
                    <!-- JS will populate preset options with checkboxes -->
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
        document.addEventListener('DOMContentLoaded', () => {

            let itenary = []; // stores all day objects

            // itenary hidden input
            const itineraryInput = document.getElementById('itenary-input');

            function updateItineraryField() {
                const itineraryInput = document.getElementById('itenary-input'); // ✅ reselect
                if (itineraryInput) {
                    itineraryInput.value = JSON.stringify(itenary);
                }
            }

            function addToItinerary(dayData) {
                itenary.push(dayData);
                updateItineraryField();
            }

            function removeItineraryItem(index) {
                itenary.splice(index, 1);
                updateItineraryField();
            }

            function updateItineraryItem(index, newData) {
                itenary[index] = newData;
                updateItineraryField();
            }

            console.log("Hidden input always holds:", itineraryInput.value);

            // Some sample preset activities to choose from (you can change)
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

            // Some sample city options for Overnight Stay
            const cityOptions = ["Rome", "Florence", "Venice", "Milan", "Naples", "Bologna", "Verona", "Turin"];

            // Some meal options
            const mealOptions = ["Breakfast", "Lunch", "Dinner", "Snacks"];

            /* ---------------------------
            Helpers: Create default day
            ----------------------------*/
            function createEmptyDay(index) {
                return {
                    id: Date.now() + Math.floor(Math.random() * 1000),
                    dayNumber: index,
                    title: `Day ${index}: Untitled`,
                    date: "",
                    overnightStay: cityOptions[0],
                    meals: [],
                    activities: []
                };
            }

            /* ---------------------------
            DOM References
            ----------------------------*/
            const daysContainer = document.getElementById('days-container');
            const jsonPreview = document.getElementById('json-preview');
            const addDayBtn = document.getElementById('add-day-btn');
            const exportBtn = document.getElementById('export-json-btn');

            /* ---------------------------
            Render Functions
            ----------------------------*/
            function renderAll() {
                daysContainer.innerHTML = '';
                itenary.forEach((day, idx) => {
                    daysContainer.appendChild(createDayCard(day, idx + 1));
                });
                updateJSONPreview();
            }

            function updateJSONPreview() {
                jsonPreview.textContent = JSON.stringify(itenary, null, 2);
            }

            /* ---------------------------
            Day Card HTML builder
            ----------------------------*/
            function createDayCard(day, displayIndex) {
                const wrapper = document.createElement('div');
                wrapper.className = 'bg-white border rounded p-6 shadow-sm';

                // Header
                wrapper.innerHTML = `
                    <div class="flex items-start justify-between gap-3">
                    <div class="flex-1">
                    <input data-day-id="${day.id}" class="day-title block w-full text-lg font-semibold text-gray-800 border-b pb-2" value="${escapeHtml(day.title)}" />
                    <div class="mt-2 text-sm text-gray-500">Day ${displayIndex}</div>
                    </div>
                    <div class="flex items-start gap-2">
                    <button data-day-id="${day.id}" class="edit-day-btn type-button px-3 py-2 bg-yellow-400 text-white rounded hover:bg-yellow-500">Edit</button>
                    <button data-day-id="${day.id}" class="delete-day-btn type-button px-3 py-2 bg-red-600 text-white rounded hover:bg-red-700">Delete</button>
                    </div>
                    </div>

                    <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                    <label class="block text-sm font-medium text-gray-700">Date</label>
                    <input data-day-id="${day.id}" class="day-date mt-1 block w-full border rounded px-3 py-2" type="date" value="${day.date || ''}" />
                    </div>

                    <div>
                    <label class="block text-sm font-medium text-gray-700">Overnight Stay</label>
                    <select data-day-id="${day.id}" class="overnight-select mt-1 block w-full border rounded px-3 py-2">
                        ${cityOptions.map(c => `<option ${c===day.overnightStay ? 'selected' : ''}>${escapeHtml(c)}</option>`).join('')}
                    </select>
                    </div>

                    <div>
                    <label class="block text-sm font-medium text-gray-700">Meals Included</label>
                    <div class="mt-1 space-y-1">
                        ${mealOptions.map(m => `
                                                                                                                    <label class="inline-flex items-center space-x-2">
                                                                                                                    <input type="checkbox" data-day-id="${day.id}" class="meal-checkbox" value="${escapeHtml(m)}" ${day.meals.includes(m) ? 'checked' : ''} />
                                                                                                                    <span class="text-sm text-gray-700">${escapeHtml(m)}</span>
                                                                                                                    </label>`).join('')}
                    </div>
                    </div>
                    </div>

                    <h3 class="mt-6 mb-3 font-semibold text-gray-800">Activities</h3>
                    <div class="space-y-3" id="activities-area-${day.id}">
                    ${day.activities.map(a => renderActivityHtml(day.id, a)).join('')}
                    </div>

                    <div class="mt-4 flex gap-2">
                    <button type="button" data-day-id="${day.id}" class="add-activity-btn px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">+ Add Activity</button>
                    <button type="button" data-day-id="${day.id}" class="export-day-btn px-4 py-2 bg-gray-100 text-gray-800 rounded">Show JSON</button>
                    </div>
                    `;

                // Attach event listeners for dynamically created elements

                // Title change (inline input)
                wrapper.querySelector('.day-title').addEventListener('input', (e) => {
                    const id = e.target.dataset.dayId;
                    const d = itenary.find(x => x.id == id);
                    if (d) {
                        d.title = e.target.value;
                        updateJSONPreview();
                    }
                });

                // Date input
                wrapper.querySelector('.day-date').addEventListener('change', (e) => {
                    const id = e.target.dataset.dayId;
                    const d = itenary.find(x => x.id == id);
                    if (d) {
                        d.date = e.target.value;
                        updateJSONPreview();
                    }
                });

                // Overnight select
                wrapper.querySelector('.overnight-select').addEventListener('change', (e) => {
                    const id = e.target.dataset.dayId;
                    const d = itenary.find(x => x.id == id);
                    if (d) {
                        d.overnightStay = e.target.value;
                        updateJSONPreview();
                    }
                });

                // Meal checkboxes
                wrapper.querySelectorAll('.meal-checkbox').forEach(cb => {
                    cb.addEventListener('change', (e) => {
                        const id = e.target.dataset.dayId;
                        const d = itenary.find(x => x.id == id);
                        if (d) {
                            const val = e.target.value;
                            if (e.target.checked) {
                                if (!d.meals.includes(val)) d.meals.push(val);
                            } else {
                                d.meals = d.meals.filter(m => m !== val);
                            }
                            updateJSONPreview();
                        }
                    });
                });

                // Add activity button
                wrapper.querySelector('.add-activity-btn').addEventListener('click', () => {
                    openActivityModal('add', day.id);
                });

                // Export day JSON
                wrapper.querySelector('.export-day-btn').addEventListener('click', () => {
                    alert(JSON.stringify(day, null, 2));
                });

                // Delete day
                wrapper.querySelector('.delete-day-btn').addEventListener('click', () => {
                    if (!confirm('Delete this day?')) return;
                    itenary = itenary.filter(d => d.id !== day.id);
                    // reassign day numbers
                    itenary.forEach((d, i) => d.dayNumber = i + 1);
                    updateItineraryField();
                    renderAll();
                });

                return wrapper;
            }

            /* ---------------------------
            Activity HTML builder
            ----------------------------*/
            function renderActivityHtml(dayId, activity) {
                // use data- attributes for actions
                const idAttr = activity._uid || ('a' + (Date.now() + Math.floor(Math.random() * 1000)));
                activity._uid = idAttr; // persist uid
                return `
                    <div class="p-4 border rounded bg-gray-50 flex items-start justify-between" data-activity-id="${idAttr}">
                    <div>
                    <div class="font-medium text-gray-800">${escapeHtml(activity.title)}</div>
                    <div class="text-sm text-gray-600 mt-1">${escapeHtml(activity.description || '')}</div>
                    ${activity.time ? `<div class="text-xs text-gray-500 mt-1">Time: ${escapeHtml(activity.time)}</div>` : ''}
                    </div>
                    <div class="flex flex-col gap-2">
                    <button data-day-id="${dayId}" data-activity-uid="${idAttr}" class="edit-activity-btn px-3 py-1 bg-yellow-100 text-yellow-800 rounded text-sm">Edit</button>
                    <button data-day-id="${dayId}" data-activity-uid="${idAttr}" class="remove-activity-btn px-3 py-1 bg-red-100 text-red-800 rounded text-sm">Remove</button>
                    </div>
                    </div>
                    `;
            }

            /* ---------------------------
            Modal Control + Activity Add/Edit
            ----------------------------*/
            const activityModal = document.getElementById('activity-modal');
            const modalClose = document.getElementById('modal-close');
            const modalHeading = document.getElementById('modal-heading');
            const activityTitle = document.getElementById('activity-title');
            const activityDesc = document.getElementById('activity-desc');
            const activityTime = document.getElementById('activity-time');
            const saveActivityBtn = document.getElementById('save-activity-btn');
            const presetsList = document.getElementById('presets-list');
            const addSelectedPresetsBtn = document.getElementById('add-selected-presets');
            const refreshPresetsBtn = document.getElementById('refresh-presets');

            let modalMode = 'add'; // 'add' or 'edit'
            let targetDayId = null;
            let editingActivityUid = null;

            function openActivityModal(mode, dayId, activityUid = null) {
                modalMode = mode;
                targetDayId = dayId;
                editingActivityUid = activityUid;

                // If edit, populate form from activity
                if (mode === 'edit') {
                    modalHeading.textContent = 'Edit Activity';
                    const day = itenary.find(d => d.id == dayId);
                    if (!day) return;
                    const activity = day.activities.find(a => a._uid === activityUid);
                    if (!activity) return;
                    activityTitle.value = activity.title || '';
                    activityDesc.value = activity.description || '';
                    activityTime.value = activity.time || '';
                } else {
                    modalHeading.textContent = 'Add Activity';
                    activityTitle.value = '';
                    activityDesc.value = '';
                    activityTime.value = '';
                }

                // populate presets
                populatePresets();
                activityModal.classList.remove('hidden');
                activityModal.classList.add('flex');
            }

            function closeActivityModal() {
                activityModal.classList.remove('flex');
                activityModal.classList.add('hidden');
                modalMode = 'add';
                targetDayId = null;
                editingActivityUid = null;
                // clear inputs
                activityTitle.value = '';
                activityDesc.value = '';
                activityTime.value = '';
                // uncheck presets
                presetsList.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
            }

            /* Save single (create or update) */
            saveActivityBtn.addEventListener('click', () => {
                if (!targetDayId) {
                    alert('No target day selected');
                    return;
                }
                const title = activityTitle.value.trim();
                const desc = activityDesc.value.trim();
                const time = activityTime.value || '';

                if (!title) {
                    alert('Please add activity title');
                    return;
                }

                const day = itenary.find(d => d.id == targetDayId);
                if (!day) return;

                if (modalMode === 'edit' && editingActivityUid) {
                    const act = day.activities.find(a => a._uid === editingActivityUid);
                    if (act) {
                        act.title = title;
                        act.description = desc;
                        act.time = time;
                    }
                } else {
                    const activityObj = {
                        title,
                        description: desc,
                        time
                    };
                    activityObj._uid = 'a' + (Date.now() + Math.floor(Math.random() * 1000));
                    day.activities.push(activityObj);
                }

                closeActivityModal();
                updateItineraryField();
                renderAll();
            });

            /* Add selected preset activities (multiple) */
            addSelectedPresetsBtn.addEventListener('click', () => {
                if (!targetDayId) {
                    alert('No target day');
                    return;
                }
                const day = itenary.find(d => d.id == targetDayId);
                if (!day) return;

                const checked = Array.from(presetsList.querySelectorAll('input[type="checkbox"]:checked'));
                if (checked.length === 0) {
                    alert('Select at least one preset');
                    return;
                }

                checked.forEach(cb => {
                    const idx = cb.dataset.presetIndex;
                    if (presetActivities[idx]) {
                        const act = {
                            ...presetActivities[idx]
                        };
                        act._uid = 'a' + (Date.now() + Math.floor(Math.random() * 1000)) + Math
                            .floor(Math
                                .random() * 100);
                        day.activities.push(act);
                    }
                    cb.checked = false;
                });

                closeActivityModal();
                updateItineraryField();
                renderAll();
            });

            /* Populate presets list */
            function populatePresets() {
                presetsList.innerHTML = '';
                presetActivities.forEach((p, i) => {
                    const el = document.createElement('label');
                    el.className = 'flex items-start gap-2 p-2 rounded hover:bg-gray-100';
                    el.innerHTML = `
                    <input type="checkbox" data-preset-index="${i}" class="mt-1">
                    <div>
                    <div class="font-medium text-gray-800">${escapeHtml(p.title)}</div>
                    <div class="text-sm text-gray-600">${escapeHtml(p.description)}</div>
                    </div>
                    `;
                    presetsList.appendChild(el);
                });
            }

            /* Modal close handlers */
            modalClose.addEventListener('click', closeActivityModal);
            refreshPresetsBtn.addEventListener('click', populatePresets);

            // click outside modal to close
            activityModal.addEventListener('click', (e) => {
                if (e.target === activityModal) closeActivityModal();
            });

            /* ---------------------------
            Event delegation for edit/remove activity buttons
            ----------------------------*/
            document.addEventListener('click', (e) => {
                // Edit activity
                if (e.target.closest('.edit-activity-btn')) {
                    const btn = e.target.closest('.edit-activity-btn');
                    const dayId = btn.dataset.dayId;
                    const uid = btn.dataset.activityUid;
                    openActivityModal('edit', dayId, uid);
                }

                // Remove activity
                if (e.target.closest('.remove-activity-btn')) {
                    const btn = e.target.closest('.remove-activity-btn');
                    const dayId = btn.dataset.dayId;
                    const uid = btn.dataset.activityUid;
                    const day = itenary.find(d => d.id == dayId);
                    if (!day) return;
                    if (!confirm('Remove this activity?')) return;
                    day.activities = day.activities.filter(a => a._uid !== uid);
                    updateItineraryField();
                    renderAll();
                }
            });

            /* ---------------------------
            Add / Remove Days
            ----------------------------*/
            addDayBtn.addEventListener('click', () => {
                const newDay = createEmptyDay(itenary.length + 1);
                itenary.push(newDay);
                updateItineraryField();
                renderAll();
            });

            // Export JSON button
            exportBtn.addEventListener('click', () => {
                const dataStr = JSON.stringify(itenary, null, 2);
                // Provide download
                const blob = new Blob([dataStr], {
                    type: "application/json"
                });
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'itenary.json';
                a.click();
                URL.revokeObjectURL(url);
            });

            /* ---------------------------
            Initialize with one sample day
            ----------------------------*/
            (function init() {
                // start with 1 sample day
                itenary.push({
                    id: Date.now(),
                    dayNumber: 1,
                    title: 'Day 1: Arrival in Rome - June 15, 2023 (Friday)',
                    date: '2023-06-15',
                    overnightStay: 'Rome',
                    meals: ['Dinner'],
                    activities: [{
                            title: "Arrival at Rome Fiumicino Airport",
                            description: "Meet your driver and transfer to your hotel. Check in and free time to relax.",
                            time: "",
                            _uid: 'a1'
                        },
                        {
                            title: "Welcome Dinner",
                            description: "Enjoy a traditional Italian dinner at a local restaurant near your hotel.",
                            time: "",
                            _uid: 'a2'
                        }
                    ]
                });
                updateItineraryField();
                renderAll();
            })();

            /* ---------------------------
            Small util: escapeHtml
            ----------------------------*/
            function escapeHtml(unsafe) {
                if (!unsafe && unsafe !== 0) return '';
                return String(unsafe)
                    .replaceAll('&', '&amp;')
                    .replaceAll('<', '&lt;')
                    .replaceAll('>', '&gt;')
                    .replaceAll('"', '&quot;')
                    .replaceAll("'", '&#039;');
            }

            document.addEventListener('click', function(e) {
                if (e.target.closest('[type="submit"]')) {
                    const itineraryInput = document.getElementById('itenary-input');
                    if (itineraryInput) {
                        itineraryInput.value = JSON.stringify(itenary);
                        console.log("✔ Itinerary set before submit:", itineraryInput.value);
                    } else {
                        console.error("❌ itenary-input field not found!");
                    }
                }
            });

        });
    </script>
@endpush
