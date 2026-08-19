<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Business Locations</h2>
    </x-slot>

    <div class="p-6">
        <div id="map" style="height: 500px; width: 100%;"></div>
    </div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        const existingLocations = @json($locations);

        // TODO: replace with a coordinate that actually makes sense for your city
        const fallbackCenter = [44.4268, 26.1025]; // Bucharest, placeholder
        const initialCenter = existingLocations.length
            ? [existingLocations[0].latitude, existingLocations[0].longitude]
            : fallbackCenter;

        const map = L.map('map').setView(initialCenter, 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const markersById = {};

        function addMarker(loc) {
            const marker = L.marker([loc.latitude, loc.longitude]).addTo(map);
            marker.bindPopup(`
                <div>
                    <p>${loc.address || 'No address'}</p>
                    <button onclick="deleteLocation(${loc.id})">Delete</button>
                </div>
            `);
            markersById[loc.id] = marker;
        }

        const deleteUrlTemplate = "{{ route('business.business-locations.destroy', ['location' => '__ID__']) }}";

        function deleteLocation(id) {
            if (!confirm('Delete this pin?')) return;

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
                })
                .catch(err => console.error(err));
        }

        existingLocations.forEach(addMarker);

        map.on('click', function (e) {
            const { lat, lng } = e.latlng;
            const address = prompt('Address for this pin (optional):');

            fetch('{{ route('business.business-locations.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ latitude: lat, longitude: lng, address }),
            })
                .then(res => res.json())
                .then(data => {
                    addMarker(data.location);
                })
                .catch(err => console.error(err));
        });
    </script>
</x-app-layout>
