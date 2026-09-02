<div class="mb-3 flex gap-2">
    <input type="text" id="address-search" placeholder="Search an address to jump to it..."
        class="flex-1 px-3 py-2 rounded-lg border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#2e3192]" />
    <button type="button" id="address-search-btn"
        class="px-4 py-2 rounded-lg bg-[#2e3192] text-white text-sm font-semibold hover:bg-[#25287a]">
        Go
    </button>
</div>
<p id="address-search-status" class="mb-2 text-xs text-gray-500"></p>

<div id="map" style="height: 400px; width: 100%;" class="rounded-xl overflow-hidden"></div>

<div class="mt-4 border border-gray-100 rounded-xl overflow-hidden">
    <div id="pin-list" class="max-h-72 overflow-y-auto"></div>
</div>

<!-- ADD PIN MODAL -->
<div id="pin-modal" class="hidden fixed inset-0 bg-black/70 flex items-center justify-center z-[9999] px-4">
    <div class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-sm">
        <h3 class="text-lg font-semibold text-gray-800 mb-3">Add a pin here</h3>
        <label for="pin-address-input" class="block text-sm text-gray-600 mb-1">Address (optional)</label>
        <input type="text" id="pin-address-input" placeholder="e.g. Str. Exemplu 12"
            class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#2e3192] mb-4" />
        <div class="flex justify-end gap-2">
            <button type="button" id="pin-cancel-btn"
                class="px-4 py-2 rounded-3xl text-sm font-semibold text-gray-600 hover:bg-gray-100">
                Cancel
            </button>
            <button type="button" id="pin-save-btn"
                class="px-4 py-2 rounded-3xl text-sm font-semibold text-white bg-[#2e3192] hover:bg-[#25287a]">
                Save Pin
            </button>
        </div>
    </div>
</div>

<!-- DELETE CONFIRM MODAL -->
<div id="delete-modal" class="hidden fixed inset-0 bg-black/70 flex items-center justify-center z-[9999] px-4">
    <div class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-sm">
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Delete this pin?</h3>
        <p class="text-sm text-gray-500 mb-4">This can't be undone.</p>
        <div class="flex justify-end gap-2">
            <button type="button" id="delete-cancel-btn"
                class="px-4 py-2 rounded-3xl text-sm font-semibold text-gray-600 hover:bg-gray-100">
                Cancel
            </button>
            <button type="button" id="delete-confirm-btn"
                class="px-4 py-2 rounded-3xl text-sm font-semibold text-white bg-[#ec008c] hover:bg-[#be0070]">
                Delete
            </button>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    const existingLocations = @json($locations);

    const fallbackCenter = [44.4268, 26.1025]; //fallback coords
    const initialCenter = existingLocations.length
        ? [existingLocations[0].latitude, existingLocations[0].longitude]
        : fallbackCenter;

    const map = L.map('map').setView(initialCenter, 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    const markersById = {};
    let currentLocations = existingLocations.slice();

    function addMarker(loc) {
        const marker = L.marker([loc.latitude, loc.longitude]).addTo(map);
        marker.bindPopup(`
            <div>
                <p>${loc.address || 'No address'}</p>
                <a href="https://www.google.com/maps/search/?api=1&query=${loc.latitude},${loc.longitude}" target="_blank">Open in Google Maps</a><br>
                <button onclick="requestDelete(${loc.id})">Delete</button>
            </div>
        `);
        markersById[loc.id] = marker;
    }

    function focusLocation(id) {
        const marker = markersById[id];
        if (!marker) return;
        map.setView(marker.getLatLng(), 16);
        marker.openPopup();
    }

    function renderPinList() {
        const listEl = document.getElementById('pin-list');

        if (!currentLocations.length) {
            listEl.innerHTML = '<p class="text-sm text-gray-400 p-4">No pins yet, click the map to add one.</p>';
            return;
        }

        listEl.innerHTML = currentLocations.map(loc => `
            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 last:border-b-0 hover:bg-gray-50">
                <button type="button" onclick="focusLocation(${loc.id})" class="text-left flex-1 text-sm text-gray-800">
                    ${loc.address || 'No address'}
                </button>
                <button type="button" onclick="requestDelete(${loc.id})" class="ml-3 text-gray-400 hover:text-[#ec008c]" aria-label="Delete pin">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
  <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
</svg>
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-1 1v1H4a1 1 0 000 2h12a1 1 0 100-2h-4V3a1 1 0 00-1-1H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm4-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        `).join('');
    }

    existingLocations.forEach(addMarker);
    renderPinList();

    // add pin flow
    const pinModal = document.getElementById('pin-modal');
    const pinAddressInput = document.getElementById('pin-address-input');
    const pinCancelBtn = document.getElementById('pin-cancel-btn');
    const pinSaveBtn = document.getElementById('pin-save-btn');

    let pendingLatLng = null;

    map.on('click', function (e) {
        pendingLatLng = e.latlng;
        pinAddressInput.value = '';
        pinModal.classList.remove('hidden');
        pinAddressInput.focus();
    });

    function closePinModal() {
        pinModal.classList.add('hidden');
        pendingLatLng = null;
    }

    pinCancelBtn.addEventListener('click', closePinModal);

    pinSaveBtn.addEventListener('click', () => {
        if (!pendingLatLng) return;
        const { lat, lng } = pendingLatLng;
        const address = pinAddressInput.value.trim();

        fetch('{{ route('business.business-locations.store') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ latitude: lat, longitude: lng, address: address || null }),
        })
            .then(res => res.json())
            .then(data => {
                addMarker(data.location);
                currentLocations.push(data.location);
                renderPinList();
                closePinModal();
            })
            .catch(err => console.error(err));
    });

    pinAddressInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            pinSaveBtn.click();
        } else if (e.key === 'Escape') {
            closePinModal();
        }
    });

    // delete flow
    const deleteModal = document.getElementById('delete-modal');
    const deleteCancelBtn = document.getElementById('delete-cancel-btn');
    const deleteConfirmBtn = document.getElementById('delete-confirm-btn');
    const deleteUrlTemplate = "{{ route('business.business-locations.destroy', ['location' => '__ID__']) }}";

    let pendingDeleteId = null;

    function requestDelete(id) {
        pendingDeleteId = id;
        deleteModal.classList.remove('hidden');
    }

    function closeDeleteModal() {
        deleteModal.classList.add('hidden');
        pendingDeleteId = null;
    }

    deleteCancelBtn.addEventListener('click', closeDeleteModal);

    deleteConfirmBtn.addEventListener('click', () => {
        if (!pendingDeleteId) return;
        const id = pendingDeleteId;

        fetch(deleteUrlTemplate.replace('__ID__', id), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
        })
            .then(res => {
                if (!res.ok) throw new Error('Delete failed');
                return res.json();
            })
            .then(() => {
                map.removeLayer(markersById[id]);
                delete markersById[id];
                currentLocations = currentLocations.filter(loc => loc.id !== id);
                renderPinList();
                closeDeleteModal();
            })
            .catch(err => console.error(err));
    });

    const searchInput = document.getElementById('address-search');
    const searchBtn = document.getElementById('address-search-btn');
    const searchStatus = document.getElementById('address-search-status');

    async function searchAddress() {
        const query = searchInput.value.trim();
        if (!query) return;

        searchStatus.textContent = 'Searching...';
        searchStatus.className = 'mb-2 text-xs text-gray-500';

        try {
            const response = await fetch(
                `https://nominatim.openstreetmap.org/search?format=json&limit=1&q=${encodeURIComponent(query)}`
            );
            const results = await response.json();

            if (!results.length) {
                searchStatus.textContent = 'Address not found. Please try a more specific search.';
                searchStatus.className = 'mb-2 text-xs text-red-600';
                return;
            }

            const lat = parseFloat(results[0].lat);
            const lng = parseFloat(results[0].lon);

            map.setView([lat, lng], 16);
            searchStatus.textContent = `Jumped to: ${results[0].display_name}`;
            searchStatus.className = 'mb-2 text-xs text-gray-500';
        } catch (err) {
            console.error(err);
            searchStatus.textContent = 'Search failed. Please check your connection and try again.';
            searchStatus.className = 'mb-2 text-xs text-red-600';
        }
    }

    searchBtn.addEventListener('click', searchAddress);
    searchInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            searchAddress();
        }
    });
</script>
