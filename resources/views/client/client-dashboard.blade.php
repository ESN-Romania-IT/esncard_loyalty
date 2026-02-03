<x-app-layout>
    <div class="max-w-md mx-auto mt-10 bg-white shadow-md p-6 rounded">
        <h2 class="text-xl font-bold mb-2">Client Dashboard</h2>
        <p class="mb-4">You are logged in as a <b>STANDARD USER</b>.</p>

        <div class="text-sm bg-gray-100 p-3 rounded">
            <div><b>Name:</b> {{ $user->profile->first_name }} {{ $user->profile->last_name }}</div>
            <div><b>Email:</b> {{ $user->email }}</div>
            <div><b>Role:</b> {{ $user->role }}</div>
        </div>

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
            <div id="qrOverlay"
                class="fixed inset-0 bg-black/60 flex items-center justify-center hidden cursor-pointer">
                <div class="bg-white p-4 rounded-lg shadow-xl">
                    {!! QrCode::size(400)->generate($qrData) !!}
                </div>
            </div>
        @endif

        <div class="mt-6 bg-white shadow-md p-6 rounded">
            <h3 class="text-lg font-semibold mb-4">Your redemptions</h3>

            @if ($redemptionsByBusiness->isEmpty())
                <div class="text-sm text-gray-500">No redemptions yet.</div>
            @else
                <div class="space-y-4">
                    @foreach ($redemptionsByBusiness as $businessName => $offers)
                        <div class="border border-gray-200 rounded p-4">
                            <div class="font-semibold text-gray-800 mb-3">
                                {{ $businessName }}
                            </div>

                            <div class="space-y-2">
                                @foreach ($offers as $offer)
                                    <div class="flex items-center justify-between text-sm">
                                        <div>{{ $offer->offer_title }}</div>
                                        <div class="text-gray-600">
                                            {{ $offer->redeemed_count }} / {{ $offer->uses_per_client }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
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
</x-app-layout>
