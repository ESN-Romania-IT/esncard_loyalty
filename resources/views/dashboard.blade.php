<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard!') }}
        </h2>
    </x-slot>

    @auth('student')
        <p class="text-lg text-white">Hello, {{ auth('student')->user()->forename }} {{ auth('student')->user()->surname }}
        </p>

        <!-- QR SECTION -->

        @if (isset($qrData) && str_starts_with($qrData, 'ERROR_'))
            <div class="bg-red-100 text-red-800 p-4 rounded">
                {{ $qrError }}
            </div>
        @elseif(isset($qrData))
            <div class="mt-6">
                <button id="toggleQR" class="px-4 py-2 bg-blue-600 text-white rounded shadow hover:bg-blue-700">
                    Show / Hide QR
                </button>

                <div id="qrWrapper" class="mt-4 hidden">
                    <div id="qrSmall" class="inline-block cursor-pointer">
                        {!! QrCode::size(200)->generate($qrData) !!}
                    </div>
                </div>
            </div>

            <!-- FULLSCREEN OVERLAY -->
            <div id="qrOverlay" class="fixed inset-0 bg-black/60 flex items-center justify-center hidden cursor-pointer">
                <div class="bg-white p-4 rounded-lg shadow-xl">
                    {!! QrCode::size(400)->generate($qrData) !!}
                </div>
            </div>

            <script>
                const qrWrapper = document.getElementById('qrWrapper');
                const toggleQR = document.getElementById('toggleQR');
                const qrSmall = document.getElementById('qrSmall');
                const qrOverlay = document.getElementById('qrOverlay');

                toggleQR.addEventListener('click', () => {
                    qrWrapper.classList.toggle('hidden');
                });

                qrSmall.addEventListener('click', () => {
                    qrOverlay.classList.remove('hidden');
                });

                qrOverlay.addEventListener('click', () => {
                    qrOverlay.classList.add('hidden');
                });
            </script>
        @endif
    @endauth

</x-app-layout>
