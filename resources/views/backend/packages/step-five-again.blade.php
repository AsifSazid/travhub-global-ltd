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
                class="px-4 py-2 text-white rounded bg-green-500 hover:scale-110 hover:brightness-110">
                <i class="fa-solid fa-download"></i>
                Export JSON
            </button>
        </div>
    </header>

    <div id="days-container" class="space-y-6"></div>

    <div class="mt-8">
        <h2 class="text-lg font-medium text-gray-700 mb-2">JSON Preview</h2>
        <pre id="json-preview" class="p-4 bg-white border rounded text-sm text-gray-700 overflow-x-auto max-h-64"></pre>
    </div>
</div>

<!-- Activity Modal -->
<div id="activity-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl p-6 transform transition-transform scale-95"
        style="width: 90%; height: 80%; overflow-y: auto;">
        <div class="flex items-center justify-between mb-4">
            <h3 id="modal-heading" class="text-lg font-semibold text-gray-800">Add Activity</h3>
            <button id="modal-close" type="button" class="text-gray-500 hover:text-gray-700">✕</button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Activity Title</label>
                <input id="activity-title" type="text" class="mt-1 block w-full border rounded px-3 py-2"
                    placeholder="e.g., City tour" />

                <label class="block text-sm font-medium text-gray-700 mt-3">Description</label>
                <div id="activity-desc" class="quill-editor border rounded p-2" style="max-height: 40% !important;">
                </div>

                <label class="block text-sm font-medium text-gray-700 mt-3">Time (optional)</label>
                <input id="activity-time" type="time" class="mt-1 block w-full border rounded px-3 py-2" />

                <div class="mt-4">
                    <button id="save-activity-btn" type="button"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:scale-110 hover:brightness-110">+
                        Add</button>
                </div>
            </div>

            <div>
                <div class="flex items-center justify-between mb-2">
                    <h4 class="font-medium text-gray-700">Choose from presets</h4>
                    <button id="refresh-presets" type="button"
                        class="text-sm text-gray-500 hover:text-gray-700">Refresh</button>
                </div>

                <div id="presets-list" class="space-y-2 max-h-60 overflow-auto p-2 border rounded bg-gray-50"></div>

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
            const cities = @json($cities);
            const startDate = @json($packQuatDetails->start_date);
            const presetActivities = @json($activities);
            let itenary = [];

            const mealOptions = ["Breakfast", "Lunch", "Dinner", "Snacks"];
            const daysContainer = document.getElementById('days-container');
            const jsonPreview = document.getElementById('json-preview');
            const addDayBtn = document.getElementById('add-day-btn');
            const exportBtn = document.getElementById('export-json-btn');
            const itineraryInput = document.getElementById('itenary-input');

            // Initialize Quill for activity description
            const quillActivity = new Quill('#activity-desc', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline'],
                        [{
                            'list': 'ordered'
                        }, {
                            'list': 'bullet'
                        }],
                        [{
                            'align': []
                        }],
                        ['clean']
                    ]
                }
            });

            function updateItineraryField() {
                itineraryInput.value = JSON.stringify(itenary);
                jsonPreview.textContent = itineraryInput.value;
            }

            function parseLocalDateTime(dateStr) {
                const [datePart, timePart] = dateStr.split(' ');
                const [y, m, d] = datePart.split('-').map(Number);
                const [h, min, s] = timePart ? timePart.split(':').map(Number) : [0, 0, 0];
                return new Date(y, m - 1, d, h, min, s);
            }

            function addDays(dateStr, days) {
                const base = parseLocalDateTime(dateStr);
                base.setDate(base.getDate() + days);
                const year = base.getFullYear();
                const month = String(base.getMonth() + 1).padStart(2, '0');
                const day = String(base.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            }

            function formatDateInfo(dateStr) {
                const [y, m, d] = dateStr.split('-').map(Number);
                const dateObj = new Date(y, m - 1, d);
                return {
                    formatted: dateObj.toLocaleDateString('en-US', {
                        month: 'short',
                        day: '2-digit',
                        year: 'numeric'
                    }),
                    dayName: dateObj.toLocaleDateString('en-US', {
                        weekday: 'long'
                    })
                };
            }

            function createEmptyDay(index) {
                const nextDate = addDays(startDate, index - 1);
                const {
                    formatted,
                    dayName
                } = formatDateInfo(nextDate);
                return {
                    id: Date.now() + Math.floor(Math.random() * 1000),
                    dayNumber: index,
                    title: `Day ${index}: Title || ${formatted} (${dayName})`,
                    date: nextDate,
                    overnightStay: cities[0]?.id || null,
                    meals: [],
                    activities: []
                };
            }

            function renderAll() {
                daysContainer.innerHTML = '';
                itenary.forEach((day, idx) => {
                    daysContainer.appendChild(createDayCard(day, idx + 1));
                });
                updateItineraryField();
            }

            function createDayCard(day, displayIndex) {
                const wrapper = document.createElement('div');
                wrapper.className = 'bg-white border rounded p-6 shadow-sm';
                wrapper.innerHTML = `
        <div class="flex items-start justify-between gap-3">
            <div class="flex-1">
                <input data-day-id="${day.id}" class="day-title block w-full text-lg font-semibold text-gray-800 border-b p-2" value="${escapeHtml(day.title)}" />
                <div class="mt-2 text-sm text-gray-500">Day ${displayIndex}</div>
            </div>
            <div class="flex items-start gap-2">
                <button data-day-id="${day.id}" class="delete-day-btn px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">Delete</button>
            </div>
        </div>

        <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Date</label>
                <input data-day-id="${day.id}" class="day-date mt-1 block w-full border rounded px-3 py-2" type="date" value="${day.date || ''}" readonly />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Overnight Stay</label>
                <select data-day-id="${day.id}" class="overnight-select mt-1 block w-full border rounded px-3 py-2">
                    ${cities.map(c => `<option value="${c.id}" ${day.overnightStay == c.id ? 'selected':''}>${escapeHtml(c.title)}</option>`).join('')}
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Meals Included</label>
                <div class="mt-1 space-y-1">
                    <label class="inline-flex items-center space-x-2 font-semibold text-blue-700">
                        <input type="checkbox" data-day-id="${day.id}" class="meal-check-all ml-1" />
                        <span class="text-sm">Check All</span>
                    </label>
                    ${mealOptions.map(m => `
                            <label class="inline-flex items-center space-x-2">
                                <input type="checkbox" data-day-id="${day.id}" class="meal-checkbox ml-1" value="${escapeHtml(m)}" ${day.meals.includes(m)?'checked':''}/>
                                <span class="text-sm text-gray-700">${escapeHtml(m)}</span>
                            </label>`).join('')}
                </div>
            </div>
        </div>

        <h3 class="mt-6 mb-3 font-semibold text-gray-800">Activities</h3>
        <div class="space-y-3" id="activities-area-${day.id}">
            ${day.activities.map((a, i) => renderActivityHtml(day.id, a, i)).join('')}
        </div>

        <div class="mt-4 flex gap-2">
            <button type="button" data-day-id="${day.id}" class="add-activity-btn px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">+ Add Activity</button>
        </div>
        `;

                // Event listeners
                wrapper.querySelector('.day-title').addEventListener('input', e => {
                    const d = itenary.find(x => x.id == e.target.dataset.dayId);
                    if (d) {
                        d.title = e.target.value;
                        updateItineraryField();
                    }
                });
                wrapper.querySelector('.overnight-select').addEventListener('change', e => {
                    const d = itenary.find(x => x.id == e.target.dataset.dayId);
                    if (d) {
                        d.overnightStay = e.target.value;
                        updateItineraryField();
                    }
                });
                wrapper.querySelectorAll('.meal-checkbox').forEach(cb => {
                    cb.addEventListener('change', e => {
                        const d = itenary.find(x => x.id == e.target.dataset.dayId);
                        if (d) {
                            const val = e.target.value;
                            if (e.target.checked) d.meals.push(val);
                            else d.meals = d.meals.filter(m => m !== val);
                            updateItineraryField();
                        }
                    });
                });
                // Handle "Check All" meals
                const checkAll = wrapper.querySelector('.meal-check-all');
                checkAll.addEventListener('change', e => {
                    const d = itenary.find(x => x.id == e.target.dataset.dayId);
                    const checkboxes = wrapper.querySelectorAll('.meal-checkbox');
                    if (d) {
                        if (e.target.checked) {
                            d.meals = [...mealOptions];
                            checkboxes.forEach(cb => cb.checked = true);
                        } else {
                            d.meals = [];
                            checkboxes.forEach(cb => cb.checked = false);
                        }
                        updateItineraryField();
                    }
                });

                wrapper.querySelector('.delete-day-btn').addEventListener('click', () => {
                    if (!confirm('Delete this day?')) return;
                    itenary = itenary.filter(d => d.id !== day.id);
                    itenary.forEach((d, i) => d.dayNumber = i + 1);
                    renderAll();
                });
                wrapper.querySelector('.add-activity-btn').addEventListener('click', () => openActivityModal('add',
                    day.id));

                return wrapper;
            }

            function renderActivityHtml(dayId, activity, index) {
                return `
        <div class="p-4 border rounded bg-gray-50 flex items-start justify-between">
            <div>
                <div class="font-medium text-gray-800">${escapeHtml(activity.title)}</div>
                <div class="text-sm text-gray-600 mt-1">${activity.description || ''}</div>
                ${activity.time?`<div class="text-xs text-gray-500 mt-1">Time: ${escapeHtml(activity.time)}</div>`:''}
            </div>
            <button type="button" data-day-id="${dayId}" data-activity-index="${index}" 
                class="edit-activity-btn text-blue-600 hover:text-blue-800 font-semibold ml-4">
                ✎ Edit
            </button>
        </div>`;
            }

            /* =========================
               Activity Modal Logic
            ==========================*/
            const activityModal = document.getElementById('activity-modal');
            const modalClose = document.getElementById('modal-close');
            const activityTitle = document.getElementById('activity-title');
            const activityTime = document.getElementById('activity-time');
            const saveActivityBtn = document.getElementById('save-activity-btn');
            const presetsList = document.getElementById('presets-list');
            const addSelectedPresetsBtn = document.getElementById('add-selected-presets');
            const refreshPresetsBtn = document.getElementById('refresh-presets');

            let modalMode = 'add';
            let targetDayId = null;
            let targetActivityIndex = null;

            function openActivityModal(mode, dayId, activityIndex = null) {
                modalMode = mode;
                targetDayId = dayId;
                targetActivityIndex = activityIndex;
                if (mode === 'edit') {
                    const day = itenary.find(d => d.id == dayId);
                    const activity = day.activities[activityIndex];
                    activityTitle.value = activity.title;
                    quillActivity.root.innerHTML = activity.description || '';
                    activityTime.value = activity.time || '';
                    document.getElementById('modal-heading').textContent = 'Edit Activity';
                    saveActivityBtn.textContent = 'Save Changes';
                } else {
                    activityTitle.value = '';
                    activityTime.value = '';
                    quillActivity.root.innerHTML = '';
                    document.getElementById('modal-heading').textContent = 'Add Activity';
                    saveActivityBtn.textContent = '+ Add';
                }
                populatePresets();
                activityModal.classList.remove('hidden');
                activityModal.classList.add('flex');
            }

            function closeActivityModal() {
                activityModal.classList.add('hidden');
                targetDayId = null;
                targetActivityIndex = null;
            }

            saveActivityBtn.addEventListener('click', () => {
                const title = activityTitle.value.trim();
                if (!title) return alert('Add a title');
                const desc = quillActivity.root.innerHTML.trim();
                const time = activityTime.value || '';
                const day = itenary.find(d => d.id == targetDayId);

                if (modalMode === 'edit' && targetActivityIndex !== null) {
                    day.activities[targetActivityIndex] = {
                        title,
                        description: desc,
                        time
                    };
                } else {
                    day.activities.push({
                        title,
                        description: desc,
                        time
                    });
                }

                closeActivityModal();
                renderAll();
            });

            function populatePresets() {
                presetsList.innerHTML = '';
                presetActivities.forEach((preset, i) => {
                    const item = document.createElement('div');
                    item.className =
                        'cursor-pointer p-2 bg-white rounded border hover:bg-blue-50 transition';
                    item.innerHTML = `
            <div class="font-medium text-gray-800">${escapeHtml(preset.title)}</div>
            <div class="text-sm text-gray-500">${escapeHtml(preset.description || '')}</div>`;
                    item.addEventListener('click', () => {
                        activityTitle.value = preset.title;
                        quillActivity.root.innerHTML = preset.description || '';
                        document.querySelectorAll('#presets-list .selected').forEach(el => el
                            .classList.remove('selected'));
                        item.classList.add('selected');
                    });
                    presetsList.appendChild(item);
                });
            }

            addSelectedPresetsBtn.addEventListener('click', () => {
                if (!targetDayId) return alert('No target day');
                const day = itenary.find(d => d.id == targetDayId);
                const selected = document.querySelector('#presets-list .selected');
                if (!selected) return alert('Select a preset first');
                const preset = presetActivities.find(p => p.title === activityTitle.value);
                if (preset) day.activities.push({
                    ...preset
                });
                closeActivityModal();
                renderAll();
            });

            modalClose.addEventListener('click', closeActivityModal);
            activityModal.addEventListener('click', e => {
                if (e.target === activityModal) closeActivityModal();
            });
            refreshPresetsBtn.addEventListener('click', populatePresets);

            addDayBtn.addEventListener('click', () => {
                const newDay = createEmptyDay(itenary.length + 1);
                itenary.push(newDay);
                renderAll();
            });

            exportBtn.addEventListener('click', () => {
                const blob = new Blob([JSON.stringify(itenary, null, 2)], {
                    type: 'application/json'
                });
                const a = document.createElement('a');
                a.href = URL.createObjectURL(blob);
                a.download = 'itenary.json';
                a.click();
                URL.revokeObjectURL(a.href);
            });

            function escapeHtml(unsafe) {
                return String(unsafe || '')
                    .replaceAll('&', '&amp;')
                    .replaceAll('<', '&lt;')
                    .replaceAll('>', '&gt;')
                    .replaceAll('"', '&quot;')
                    .replaceAll("'", "&#039;");
            }

            // Handle edit button clicks (delegated)
            daysContainer.addEventListener('click', (e) => {
                if (e.target.classList.contains('edit-activity-btn')) {
                    const dayId = e.target.dataset.dayId;
                    const actIndex = e.target.dataset.activityIndex;
                    openActivityModal('edit', dayId, actIndex);
                }
            });

            // initialize
            (function init() {
                if (startDate) {
                    const first = createEmptyDay(1);
                    first.activities = [];
                    itenary.push(first);
                    renderAll();
                }
            })();
        });
    </script>
@endpush
