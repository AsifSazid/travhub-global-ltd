<input type="hidden" name="itenary" id="itenary-input">

<div class="max-w-5xl mx-auto">
    <header class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-800"></h1>
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

<!-- Edit Activity Modal -->
<div id="activity-edit-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl p-6 transform transition-transform scale-95"
        style="width: 90%; max-height: 90%; overflow-y: auto;">
        <div class="flex items-center justify-between mb-4">
            <h3 id="edit-modal-heading" class="text-lg font-semibold text-gray-800">Edit Activity</h3>
            <button id="edit-modal-close" type="button" class="text-gray-500 hover:text-gray-700">✕</button>
        </div>

        <div id="edit-form" class="space-y-4"></div>

        <div class="mt-4 flex justify-end gap-2">
            <button id="edit-cancel" type="button" class="px-4 py-2 border rounded">Cancel</button>
            <button id="edit-save" type="button" class="px-4 py-2 bg-blue-600 text-white rounded">Save Changes</button>
        </div>
    </div>
</div>

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const cities = @json($cities);
            const packDesDetails = @json($packDesDetails);
            const startDate = @json($packQuatDetails->start_date);
            const maxDays = @json($packQuatDetails->duration);
            const presetActivities = @json($activities);
            let itenary = [];

            const mealOptions = ["Breakfast", "Lunch", "Dinner", "Snacks"];
            const daysContainer = document.getElementById('days-container');
            const jsonPreview = document.getElementById('json-preview');
            const addDayBtn = document.getElementById('add-day-btn');
            const exportBtn = document.getElementById('export-json-btn');
            const itineraryInput = document.getElementById('itenary-input');

            const editModal = document.getElementById('activity-edit-modal');
            const editForm = document.getElementById('edit-form');
            const editSaveBtn = document.getElementById('edit-save');
            const editCancelBtn = document.getElementById('edit-cancel');
            const editCloseX = document.getElementById('edit-modal-close');
            const editModalHeading = document.getElementById('edit-modal-heading');

            function escapeHtml(unsafe) {
                return String(unsafe || '')
                    .replaceAll('&', '&amp;')
                    .replaceAll('<', '&lt;')
                    .replaceAll('>', '&gt;')
                    .replaceAll('"', '&quot;')
                    .replaceAll("'", "&#039;");
            }

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
                const y = base.getFullYear();
                const m = String(base.getMonth() + 1).padStart(2, '0');
                const d = String(base.getDate()).padStart(2, '0');
                return `${y}-${m}-${d}`;
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
                    id: Date.now() + Math.floor(Math.random() * 1000) + index,
                    dayNumber: index,
                    title: `Day ${index}: Title || ${formatted} (${dayName})`,
                    date: nextDate,
                    overnightStay: cities[0]?.id || null,
                    meals: [],
                    activities: []
                };
            }

            function renderActivityHtml(dayId, activity, index) {
                const descPreview = activity.description ? activity.description : '';
                return `
    <div class="p-4 border rounded bg-gray-50 flex flex-col gap-2 break-words" data-day-id="${dayId}" data-act-index="${index}">
        <!-- Buttons on top left -->
        <div class="flex justify-end gap-2">
            <button type="button" class="edit-activity-btn text-blue-600 hover:text-blue-800 font-semibold" data-day-id="${dayId}" data-activity-index="${index}">✎ Edit</button>
            <button type="button" class="delete-activity-btn text-red-600 hover:text-red-800 font-semibold" data-day-id="${dayId}" data-activity-index="${index}">🗑 Delete</button>
        </div>

        <!-- Content below buttons -->
        <div class="mt-2 break-words">
            <div class="font-medium text-gray-800">${escapeHtml(activity.title)}</div>
            <div class="text-sm text-gray-600 mt-1 break-words">
                ${escapeHtml(descPreview)}
        ${activity.data ? `
        <div class="mt-1 text-xs text-gray-500">
            ${Object.entries(activity.data).map(([k, v]) => {
                if (k === 'city_id') {
                    const city = cities.find(c => c.id == v);
                    return `City: ${escapeHtml(city ? city.title : v)}`;
                } else if (k === 'country_id') {
                    return `Country: ${escapeHtml(packDesDetails.country_title ?? v)}`;
                } else {
                    return `${escapeHtml(k)}: ${escapeHtml(v)}`;
                }
            }).join(', ')}
        </div>` : ''}
            </div>
            ${activity.time ? `<div class="text-xs text-gray-500 mt-1">Time: ${escapeHtml(activity.time)}</div>` : ''}
        </div>
    </div>
        `;
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
                    ${cities.map(c=>`<option value="${c.id}" ${day.overnightStay==c.id?'selected':''}>${escapeHtml(c.title)}</option>`).join('')}
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Meals Included</label>
                <div class="mt-1 space-y-1">
                    <label class="inline-flex items-center space-x-2 font-semibold text-blue-700">
                        <input type="checkbox" data-day-id="${day.id}" class="meal-check-all ml-1" />
                        <span class="text-sm">Check All</span>
                    </label>
                    ${mealOptions.map(m=>`
                                        <label class="inline-flex items-center space-x-2">
                                            <input type="checkbox" data-day-id="${day.id}" class="meal-checkbox ml-1" value="${escapeHtml(m)}" ${day.meals.includes(m)?'checked':''}/>
                                            <span class="text-sm text-gray-700">${escapeHtml(m)}</span>
                                        </label>`).join('')}
                </div>
            </div>
        </div>

        <h3 class="mt-6 mb-3 font-semibold text-gray-800">Activities</h3>
        <div class="space-y-3" id="activities-area-${day.id}">
            ${day.activities.map((a,i)=>renderActivityHtml(day.id,a,i)).join('')}
        </div>

        <div class="mt-4 flex flex-wrap gap-2">
            ${presetActivities.map(a=>`
                                <button type="button" data-day-id="${day.id}" data-activity-title="${escapeHtml(a.title)}" class="add-preset-btn px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
                                    + ${escapeHtml(a.title)}
                                </button>`).join('')}
        </div>
        `;

                // Event listeners...
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
                wrapper.querySelector('.meal-check-all').addEventListener('change', e => {
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

                return wrapper;
            }

            function renderAll() {
                daysContainer.innerHTML = '';
                itenary.forEach((day, idx) => daysContainer.appendChild(createDayCard(day, idx + 1)));
                updateItineraryField();
            }

            // Delegated listeners for add/edit/delete activity
            daysContainer.addEventListener('click', e => {
                // Add preset
                if (e.target.classList.contains('add-preset-btn')) {
                    const dayId = e.target.dataset.dayId;
                    const title = e.target.dataset.activityTitle;
                    const day = itenary.find(d => d.id == dayId);
                    if (itenary.length > maxDays) {
                        alert('Maximum days reached');
                        return;
                    }
                    const template = presetActivities.find(a => a.title === title);
                    if (!day || !template) return;
                    const data = {};
                    Object.keys(template).forEach(k => {
                        if (!['id', 'title', 'description'].includes(k)) {
                            data[k] = template[k];
                        }
                    });
                    day.activities.push({
                        title: template.title,
                        description: template.description || '',
                        time: '',
                        data
                    });
                    renderAll();
                }

                // Edit activity
                if (e.target.classList.contains('edit-activity-btn')) {
                    const dayId = e.target.dataset.dayId;
                    const idx = Number(e.target.dataset.activityIndex);
                    const day = itenary.find(d => d.id == dayId);
                    if (!day) return;
                    const activity = day.activities[idx];
                    openEditModal(dayId, idx, activity);
                }

                // Delete activity
                if (e.target.classList.contains('delete-activity-btn')) {
                    const dayId = e.target.dataset.dayId;
                    const idx = Number(e.target.dataset.activityIndex);
                    const day = itenary.find(d => d.id == dayId);
                    if (!day) return;
                    if (!confirm('Delete this activity?')) return;
                    day.activities.splice(idx, 1);
                    renderAll();
                }
            });

            // Modal logic
            let currentEdit = {
                dayId: null,
                idx: null
            };

            function isPresetStatic(preset) {
                if (!preset) return false;
                return Object.keys(preset).some(k => !['id', 'title', 'description'].includes(k));
            }

            function openEditModal(dayId, idx, activity) {
                editForm.innerHTML = '';
                currentEdit = {
                    dayId,
                    idx
                };

                const preset = presetActivities.find(p => p.title === activity.title);
                const staticMode = isPresetStatic(preset);
                editModalHeading.textContent = `Edit Activity — ${activity.title || ''}`;

                // Title
                const titleWrapper = document.createElement('div');
                titleWrapper.innerHTML =
                    `<label class="block text-sm font-medium">Title</label>
        <input id="modal-edit-title" class="mt-1 block w-full border rounded px-3 py-2" value="${escapeHtml(activity.title)}" />`;
                editForm.appendChild(titleWrapper);

                // Description
                const descWrapper = document.createElement('div');
                descWrapper.innerHTML =
                    `<label class="block text-sm font-medium">Description</label>
        <textarea id="modal-edit-desc" rows="4" class="mt-1 block w-full border rounded px-3 py-2 resize-y break-words">${escapeHtml(activity.description||'')}</textarea>`;
                editForm.appendChild(descWrapper);

                // Static fields
                if (staticMode) {
                    const note = document.createElement('div');
                    note.className = 'text-sm text-gray-600 mb-2';
                    note.textContent = 'Fill the predefined fields for this activity:';
                    editForm.appendChild(note);

                    const gridWrapper = document.createElement('div');
                    gridWrapper.className = 'grid grid-cols-1 md:grid-cols-3 gap-4';
                    editForm.appendChild(gridWrapper);

                    Object.keys(preset).forEach(key => {
                        if (!['id', 'title', 'description'].includes(key)) {
                            const val = (activity.data && activity.data[key]) ? activity.data[key] : preset[
                                key] ?? '';
                            const wrapper = document.createElement('div');

                            let fieldHTML = '';

                            if (key === 'city_id') {
                                const options = cities.map(c =>
                                    `<option value="${c.id}" ${val == c.id ? 'selected' : ''}>${escapeHtml(c.title)}</option>`
                                ).join('');
                                fieldHTML = `
                                            <label class="block text-sm font-medium">City</label>
                                            <select data-static-field="city_id" class="mt-1 block w-full border rounded px-3 py-2">
                                                <option value="">Select a city</option>
                                                ${options}
                                            </select>`;
                            } else {
                                fieldHTML =
                                    `
                                    <label class="block text-sm font-medium">${escapeHtml(key)}</label>
                                    <input data-static-field="${escapeHtml(key)}" class="mt-1 block w-full border rounded px-3 py-2 break-words" value="${escapeHtml(val)}"/>`;
                            }

                            wrapper.innerHTML = fieldHTML;
                            gridWrapper.appendChild(wrapper);
                        }
                    });
                }

                // Time
                const timeWrapper = document.createElement('div');
                timeWrapper.innerHTML =
                    `<label class="block text-sm font-medium">Time (optional)</label>
        <input id="modal-edit-time" type="time" class="mt-1 block w-full border rounded px-3 py-2" value="${escapeHtml(activity.time||'')}" />`;
                editForm.appendChild(timeWrapper);

                editModal.classList.remove('hidden');
                editModal.classList.add('flex');
            }

            function closeEditModal() {
                editModal.classList.add('hidden');
                editModal.classList.remove('flex');
                editForm.innerHTML = '';
                currentEdit = {
                    dayId: null,
                    idx: null
                };
            }

            editCloseX.addEventListener('click', closeEditModal);
            editCancelBtn.addEventListener('click', closeEditModal);
            editModal.addEventListener('click', e => {
                if (e.target === editModal) closeEditModal();
            });

            editSaveBtn.addEventListener('click', () => {
                const {
                    dayId,
                    idx
                } = currentEdit;
                if (dayId === null || idx === null) return closeEditModal();
                const day = itenary.find(d => d.id == dayId);
                if (!day) return closeEditModal();
                const activity = day.activities[idx];
                if (!activity) return closeEditModal();

                activity.title = document.getElementById('modal-edit-title').value.trim() || activity.title;
                activity.description = document.getElementById('modal-edit-desc').value.trim();
                activity.time = document.getElementById('modal-edit-time').value || '';

                const staticInputs = editForm.querySelectorAll('[data-static-field]');
                if (staticInputs.length > 0) {
                    activity.data = activity.data || {};
                    staticInputs.forEach(inp => {
                        const fieldName = inp.dataset.staticField || inp.getAttribute(
                            'data-static-field');
                        activity.data[fieldName] = inp.value;
                    });
                }

                closeEditModal();
                renderAll();
            });

            // Add day button
            addDayBtn.addEventListener('click', () => {
                if (itenary.length >= maxDays) {
                    alert('Maximum days reached');
                    return;
                }
                const newDay = createEmptyDay(itenary.length + 1);
                itenary.push(newDay);
                renderAll();
            });

            // Export JSON
            exportBtn.addEventListener('click', () => {
                const dataStr = JSON.stringify(itenary, null, 2);
                const blob = new Blob([dataStr], {
                    type: "application/json"
                });
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = "itinerary.json";
                a.click();
                URL.revokeObjectURL(url);
            });

            // Initial render
            if (itenary.length === 0) {
                const firstDay = createEmptyDay(1);
                itenary.push(firstDay);
            }
            renderAll();
        });
    </script>
@endpush
