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
                        <div id="qrOverlayContent"
                            class="bg-white p-3 sm:p-5 rounded-2xl shadow-2xl w-[92vw] h-[92vw] max-w-[92vh] max-h-[92vh]">
                            {!! QrCode::size(360)->generate($qrData) !!}
                        </div>
                    </div>
                @endif

            </div>

            <!-- REDEMPTIONS -->
            <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">

                <h3 class="text-xl font-semibold mb-6 text-gray-800">
                    Your Redemptions
                </h3>

                <div id="client-redemptions-empty"
                    class="text-sm text-gray-500 {{ $redemptionsByBusiness->isEmpty() ? '' : 'hidden' }}">
                    No redemptions yet.
                </div>

                <div id="client-redemptions-list"
                    class="space-y-6 {{ $redemptionsByBusiness->isEmpty() ? 'hidden' : '' }}">
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
                                            class="px-3 py-1 rounded-full text-xs font-semibold bg-[#8DC63F]/20 text-[#3c8d1e]">
                                            {{ $offer->redeemed_count }} / {{ $offer->uses_per_client }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                        </div>
                    @endforeach
                </div>

            </div>

        </div>
    </div>
    <style>
        #qrOverlayContent svg,
        #qrOverlayContent canvas,
        #qrOverlayContent img {
            width: 100% !important;
            height: 100% !important;
            display: block;
        }
    </style>
    <script>
        const qrWrapper = document.getElementById('qrWrapper');
        const toggleQR = document.getElementById('toggleQR');
        const qrSmall = document.getElementById('qrSmall');
        const qrOverlay = document.getElementById('qrOverlay');
        const redemptionsList = document.getElementById('client-redemptions-list');
        const redemptionsEmpty = document.getElementById('client-redemptions-empty');
        const redemptionChannel = 'BroadcastChannel' in window ? new BroadcastChannel('esn-redemptions') : null;

        function escapeHtml(value) {
            return String(value)
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;');
        }

        function renderRedemptions(businesses) {
            if (!Array.isArray(businesses) || !businesses.length) {
                redemptionsList.classList.add('hidden');
                redemptionsList.innerHTML = '';
                redemptionsEmpty.classList.remove('hidden');
                return;
            }

            redemptionsEmpty.classList.add('hidden');
            redemptionsList.classList.remove('hidden');

            redemptionsList.innerHTML = businesses.map((business) => {
                const offersHtml = (business.offers || []).map((offer) => `
                    <div class="flex items-center justify-between text-sm">
                        <div class="text-gray-700">${escapeHtml(offer.offer_title)}</div>
                        <div class="px-3 py-1 rounded-full text-xs font-semibold bg-[#8DC63F]/20 text-[#3c8d1e]">
                            ${offer.redeemed_count} / ${offer.uses_per_client}
                        </div>
                    </div>
                `).join('');

                return `
                    <div class="border border-gray-100 rounded-xl p-5 bg-gray-50">
                        <div class="font-semibold text-[#00AEEF] mb-4">${escapeHtml(business.business_name)}</div>
                        <div class="space-y-3">${offersHtml}</div>
                    </div>
                `;
            }).join('');
        }

        async function refreshRedemptions() {
            const response = await fetch('{{ route('client.dashboard.stats') }}', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                }
            });

            if (response.status === 401) {
                window.location.href = '{{ route('login') }}';
                return;
            }

            const data = await response.json();
            if (!response.ok || !data?.ok) {
                return;
            }

            renderRedemptions(data.businesses || []);
        }

        toggleQR.addEventListener('click', () => {
            qrWrapper.classList.toggle('hidden');
        });

        qrSmall.addEventListener('click', () => {
            qrOverlay.classList.remove('hidden');
        });

        qrOverlay.addEventListener('click', () => {
            qrOverlay.classList.add('hidden');
        });

        if (redemptionChannel) {
            redemptionChannel.addEventListener('message', (event) => {
                if (event?.data?.type === 'redemption-updated') {
                    refreshRedemptions().catch(() => {});
                }
            });
        }

        window.addEventListener('storage', (event) => {
            if (event.key === 'esn:redemption-updated') {
                refreshRedemptions().catch(() => {});
            }
        });

        window.addEventListener('beforeunload', () => {
            if (redemptionChannel) {
                redemptionChannel.close();
            }
        });
    </script>

</x-app-layout>
