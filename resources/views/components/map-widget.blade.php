<div class="mb-4">
    <button type="button" id="toggleListBtn"
        class="px-4 py-2 rounded-full font-semibold text-white bg-[#2e3192] hover:bg-[#25287a] transition">
        View All Businesses
    </button>
</div>

<div id="map" style="height: 400px; width: 100%;" class="rounded-xl overflow-hidden"></div>
<p id="location-status" class="mt-2 text-sm text-gray-500"></p>

<div id="business-list-panel" class="hidden mt-4 border border-gray-100 rounded-xl overflow-hidden">
    <div class="p-3 border-b border-gray-100 bg-gray-50">
        <input type="text" id="business-search" placeholder="Search by business name or address..."
            class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#2e3192]" />
    </div>
    <div id="business-list" class="max-h-72 overflow-y-auto"></div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    const businessLocations = @json($locations);

    const fallbackCenter = [44.4268, 26.1025]; // TODO: set correct fallback map coordinates

    let map;
    const markersById = {};
    let currentUserLat = null;
    let currentUserLng = null;
    let baseListOrder = [];

    function deg2rad(deg) {
        return deg * (Math.PI / 180);
    }

    function distanceKm(lat1, lon1, lat2, lon2) {
        const R = 6371;
        const dLat = deg2rad(lat2 - lat1);
        const dLon = deg2rad(lon2 - lon1);
        const a =
            Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
            Math.sin(dLon / 2) * Math.sin(dLon / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return R * c;
    }

    function popupHtml(loc, userLat, userLng) {
        const lat = parseFloat(loc.latitude);
        const lng = parseFloat(loc.longitude);
        const name = loc.businessProfile ? loc.businessProfile.business_name : 'Business';
        const mapsLink = `https://www.google.com/maps/search/?api=1&query=${lat},${lng}`;

        let distanceText = '';
        if (userLat !== null) {
            const km = distanceKm(userLat, userLng, lat, lng);
            distanceText = `<p>${km.toFixed(1)} km away</p>`;
        }

        return `
            <div>
                <p><strong>${name}</strong></p>
                <p>${loc.address || 'No address'}</p>
                ${distanceText}
                <a href="${mapsLink}" target="_blank">Open in Google Maps</a>
            </div>
        `;
    }

    function shuffle(array) {
        const result = [...array];
        for (let i = result.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [result[i], result[j]] = [result[j], result[i]];
        }
        return result;
    }

    const MAX_LIST_DISTANCE_KM = 5; //max distance to have pin displayed in user's "View All Businesses" list

    function getOrderedLocations() {
        if (currentUserLat !== null) {
            return [...businessLocations]
                .filter(loc => distanceKm(currentUserLat, currentUserLng, parseFloat(loc.latitude), parseFloat(loc.longitude)) <= MAX_LIST_DISTANCE_KM)
                .sort((a, b) => {
                    const da = distanceKm(currentUserLat, currentUserLng, parseFloat(a.latitude), parseFloat(a.longitude));
                    const db = distanceKm(currentUserLat, currentUserLng, parseFloat(b.latitude), parseFloat(b.longitude));
                    return da - db;
                });
        }
        return shuffle(businessLocations);
    }

    function renderList(filterText = '') {
        const listEl = document.getElementById('business-list');
        const query = filterText.trim().toLowerCase();

        const filtered = baseListOrder.filter(loc => {
            const name = (loc.businessProfile ? loc.businessProfile.business_name : '').toLowerCase();
            const address = (loc.address || '').toLowerCase();
            return name.includes(query) || address.includes(query);
        });

        if (filtered.length === 0) {
            const message = (baseListOrder.length === 0 && currentUserLat !== null)
                ? `No businesses within ${MAX_LIST_DISTANCE_KM} km.`
                : 'No businesses found.';
            listEl.innerHTML = `<p class="text-sm text-gray-400 p-4">${message}</p>`;
            return;
        }

        listEl.innerHTML = filtered.map(loc => {
            const name = loc.businessProfile ? loc.businessProfile.business_name : 'Business';
            const address = loc.address || 'No address';
            let distanceText = '';
            if (currentUserLat !== null) {
                const km = distanceKm(currentUserLat, currentUserLng, parseFloat(loc.latitude), parseFloat(loc.longitude));
                distanceText = `<span class="text-xs text-gray-400 whitespace-nowrap ml-2">${km.toFixed(1)} km</span>`;
            }
            return `
                <button type="button" onclick="focusLocation(${loc.id})"
                    class="w-full text-left px-4 py-3 border-b border-gray-100 hover:bg-gray-50 flex items-center justify-between">
                    <span>
                        <span class="block font-medium text-gray-800">${name}</span>
                        <span class="block text-xs text-gray-500">${address}</span>
                    </span>
                    ${distanceText}
                </button>
            `;
        }).join('');
    }

    function focusLocation(id) {
        const marker = markersById[id];
        if (!marker) return;
        map.setView(marker.getLatLng(), 15);
        marker.openPopup();
    }

    function renderMap(userLat, userLng) {
        currentUserLat = userLat;
        currentUserLng = userLng;

        let center = fallbackCenter;
        if (userLat !== null) {
            center = [userLat, userLng];
        } else if (businessLocations.length > 0) {
            center = [
                parseFloat(businessLocations[0].latitude),
                parseFloat(businessLocations[0].longitude)
            ];
        }

        map = L.map('map').setView(center, 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        if (userLat !== null) {
            L.circleMarker([userLat, userLng], {
                radius: 8,
                color: '#2e3192',
                fillColor: '#2e3192',
                fillOpacity: 0.8
            }).addTo(map).bindPopup('You are here');
        }

        businessLocations.forEach(loc => {
            const lat = parseFloat(loc.latitude);
            const lng = parseFloat(loc.longitude);
            const marker = L.marker([lat, lng]).addTo(map).bindPopup(popupHtml(loc, userLat, userLng));
            markersById[loc.id] = marker;
        });

        baseListOrder = getOrderedLocations();
        renderList();
    }

    const statusEl = document.getElementById('location-status');

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function (position) {
                renderMap(position.coords.latitude, position.coords.longitude);
            },
            function () {
                statusEl.textContent = 'Location permission denied — showing map without your position or distances.';
                renderMap(null, null);
            }
        );
    } else {
        statusEl.textContent = 'Geolocation not supported by this browser.';
        renderMap(null, null);
    }

    document.getElementById('toggleListBtn').addEventListener('click', () => {
        document.getElementById('business-list-panel').classList.toggle('hidden');
    });

    document.getElementById('business-search').addEventListener('input', (e) => {
        renderList(e.target.value);
    });
</script>
