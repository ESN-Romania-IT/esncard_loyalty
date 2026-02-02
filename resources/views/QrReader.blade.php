<x-app-layout>
    <div class="max-w-md mx-auto mt-10 bg-white shadow-md p-6 rounded">

        <h2 class="text-xl font-bold mb-4 text-center">
            Scanare Cod QR
        </h2>

        {{-- Container cameră --}}
        <div id="cititor-qr" class="mx-auto border-2 border-gray-700 rounded" style="width: 100%; max-width: 350px;">
        </div>

        {{-- Rezultat --}}
        <div id="rezultat-scanare" class="mt-4 font-bold text-green-600 text-center">
        </div>

    </div>

    {{-- Librăria QR --}}
    <script src="https://unpkg.com/html5-qrcode"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const html5QrCode = new Html5Qrcode("cititor-qr");

            html5QrCode.start({
                    facingMode: "environment"
                }, // camera spate (mobil)
                {
                    fps: 10,
                    qrbox: 250
                },
                function(decodedText) {
                    document.getElementById('rezultat-scanare').innerText =
                        window.location.href = decodedText;

                    // Oprește camera după prima scanare
                    html5QrCode.stop();
                },
                function(error) {
                    // ignorăm erorile de scanare
                }
            );
        });
    </script>
</x-app-layout>
