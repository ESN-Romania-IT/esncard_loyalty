<x-app-layout>



    <div class="min-h-screen bg-gray-50 py-12 px-4">

        <div class="max-w-4xl mx-auto">

            <!-- HEADER -->
            <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100 mb-10">

                <div class="flex items-center justify-between flex-wrap gap-6">

                    <div class="flex items-center gap-6">
                        <img src="{{ asset('images/icons/ESN_Logo.svg') }}" alt="ESN Logo" class="h-16" />

                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">
                                Digital Loyalty Dashboard
                            </h1>
                            <p class="text-sm text-gray-500">
                                Erasmus Student Network Romania
                            </p>
                        </div>
                    </div>

                    <div class="px-4 py-2 bg-[#EC008C]/10 text-[#EC008C] text-xs font-semibold rounded-full">
                        {{ strtoupper($user->role) }}
                    </div>

                </div>

                <!-- USER INFO -->
                <div class="mt-8 grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">

                    <div class="bg-gray-50 rounded-xl p-4">
                        <div class="text-gray-400 text-xs uppercase">Full Name</div>
                        <div class="font-semibold text-gray-800">
                            {{ $user->profile->first_name }} {{ $user->profile->last_name }}
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-4">
                        <div class="text-gray-400 text-xs uppercase">Email</div>
                        <div class="font-semibold text-gray-800">
                            {{ $user->email }}
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-4">
                        <div class="text-gray-400 text-xs uppercase">ESN Card</div>
                        <div class="font-semibold text-gray-800">
                            {{ $user->esncard_code }}
                        </div>
                    </div>

                </div>

                <!-- QR SECTION -->
                @if (isset($qrData))
                    <div class="mt-10 text-center">

                        <button id="toggleQR"
                            class="px-6 py-3 rounded-full font-semibold text-white
bg-[#EC008C] hover:bg-[#d6007d]
shadow-md transition duration-200">
                            Show / Hide QR
                        </button>

                        <div id="qrWrapper" class="mt-6 hidden">
                            <div id="qrSmall"
                                class="inline-block p-4 bg-white rounded-2xl shadow-lg cursor-pointer hover:scale-105 transition">
                                {!! QrCode::size(200)->generate($qrData) !!}
                            </div>
                            <div class="text-xs text-gray-500 mt-2">
                                Click QR to enlarge
                            </div>
                        </div>
                    </div>

                    <!-- FULLSCREEN OVERLAY -->
                    <div id="qrOverlay"
                        class="fixed inset-0 bg-black/70 flex items-center justify-center hidden cursor-pointer z-50">
                        <div class="bg-white p-6 rounded-2xl shadow-2xl">
                            {!! QrCode::size(400)->generate($qrData) !!}
                        </div>
                    </div>
                @endif

            </div>

            <!-- REDEMPTIONS -->
            <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">

                <h3 class="text-xl font-semibold mb-6 text-gray-800">
                    Your Redemptions
                </h3>

                @if ($redemptionsByBusiness->isEmpty())
                    <div class="text-sm text-gray-500">
                        No redemptions yet.
                    </div>
                @else
                    <div class="space-y-6">
                        @foreach ($redemptionsByBusiness as $businessName => $offers)
                            <div class="border border-gray-100 rounded-xl p-5 bg-gray-50">

                                <div class="font-semibold text-[#00AEEF] mb-4">
                                    {{ $businessName }}
                                </div>

                                <div class="space-y-3">
                                    @foreach ($offers as $offer)
                                        <div class="flex items-center justify-between text-sm">
                                            <div class="text-gray-700">
                                                {{ $offer->offer_title }}
                                            </div>

                                            <div
                                                class="px-3 py-1 rounded-full text-xs font-semibold
bg-[#8DC63F]/20 text-[#3c8d1e]">
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
