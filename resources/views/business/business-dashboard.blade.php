<x-app-layout>
    <header class="flex flex-col items-center justify-center mt-2 bg-white p-6 rounded-b-full h-44">
        <a href="/" class="flex items-center justify-center">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-12 w-auto">
        </a>
        <h1 class="text-3xl font-bold text-center mt-4 text-[#2e3192]">Business Dashboard</h1>
    </header>
    <div class="max-w-6xl mx-auto mt-10 px-6 pb-10">
        <div class="bg-white shadow-md p-6 rounded-3xl border border-gray-200">
            <p class="mb-4 text-gray-700">You are logged in as a <b>BUSINESS USER</b>.</p>

            <div class="text-sm bg-[#2e3192]/5 p-4 rounded-3xl border border-[#2e3192]/20 mb-6">
                <div><b>Name:</b> {{ $user->business_profile->business_name }}</div>
                <div><b>Email:</b> {{ $user->email }}</div>
                <div><b>Role:</b> {{ $user->role }}</div>
            </div>

            <div class="bg-white shadow-md p-6 rounded-3xl border border-gray-200">
                <h2 class="text-xl font-bold mb-4 text-center text-[#2e3192]">QR Scanner</h2>

                <div class="flex justify-center gap-3 mb-4">
                    <button id="btn-start" type="button"
                        class="bg-[#2e3192] text-white px-4 py-2 rounded-3xl hover:bg-[#25287a]">
                        Start
                    </button>

                    <button id="btn-stop" type="button" disabled
                        class="bg-gray-400 text-white px-4 py-2 rounded-3xl cursor-not-allowed">
                        Stop
                    </button>
                </div>

                {{-- Camera container --}}
                <div id="cititor-qr" class="mx-auto border-2 border-gray-700 rounded hidden"
                    style="width: 100%; max-width: 350px;">
                </div>

                {{-- Result --}}
                <div id="rezultat-scanare" class="mt-4 font-bold text-[#7ac143] text-center"></div>

                <div id="scan-result-card"
                    class="mt-4 hidden border border-[#7ac143]/40 bg-[#7ac143]/10 p-4 rounded-3xl">
                    <div class="text-sm text-gray-700">Client</div>
                    <div class="text-lg font-semibold" id="scan-client-name"></div>

                    <div class="mt-4">
                        <button id="btn-apply-offer" type="button"
                            class="bg-[#ec008c] text-white px-4 py-2 rounded-3xl hover:bg-[#be0070]">
                            View offers
                        </button>
                    </div>

                    <div id="offers-panel" class="mt-4 hidden border border-[#2e3192]/20 bg-white p-4 rounded-3xl">
                        <div class="flex items-center justify-between mb-3">
                            <div class="text-sm font-semibold text-gray-700">Available offers</div>
                        </div>

                        <div id="offers-notice" class="mb-3 text-sm text-[#2e3192] hidden"></div>
                        <div id="offers-error" class="mb-3 text-sm text-red-600 hidden"></div>
                        <div id="offers-loading" class="mb-3 text-sm text-gray-500 hidden">Loading offers...</div>
                        <div id="offers-empty" class="mb-3 text-sm text-gray-500 hidden">No active offers.</div>

                        <div id="offers-list" class="space-y-3"></div>

                        <div class="mt-4">
                            <button id="btn-offers-done" type="button"
                                class="w-full bg-[#2e3192] text-white px-4 py-2 rounded-3xl hover:bg-[#25287a]">
                                Done
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Errors --}}
                <div id="eroare-scanare" class="mt-2 text-sm text-red-600 text-center"></div>
            </div>

            <div class="mt-6 bg-white shadow-md p-6 rounded-3xl border border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold">Active offers</h3>
                    <a href="{{ route('business.offers.index') }}"
                        class="bg-[#ec008c] text-white px-4 py-2 rounded-3xl text-sm hover:bg-[#be0070]">
                        Manage offers
                    </a>
                </div>

                @if ($activeOffers->isEmpty())
                    <div class="text-sm text-gray-500">No active offers.</div>
                @else
                    <div class="space-y-3">
                        @foreach ($activeOffers as $offer)
                            <div class="border border-gray-200 rounded-3xl p-4 flex items-center justify-between gap-4">
                                <div>
                                    <div class="font-semibold text-gray-800">{{ $offer->title }}</div>
                                    <div
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-[#7ac143]/15 text-[#4f8a27] mt-1">
                                        {{ $offer->redemptions_count }} redemption(s)
                                    </div>
                                </div>
                                <a href="{{ route('business.offers.show', $offer) }}"
                                    class="bg-[#2e3192] text-white px-4 py-2 rounded-3xl text-sm hover:bg-[#25287a]">
                                    View
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <script src="https://unpkg.com/html5-qrcode"></script>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const startBtn = document.getElementById('btn-start');
                    const stopBtn = document.getElementById('btn-stop');
                    const qrRegion = document.getElementById('cititor-qr');
                    const resultEl = document.getElementById('rezultat-scanare');
                    const errorEl = document.getElementById('eroare-scanare');
                    const resultCard = document.getElementById('scan-result-card');
                    const clientNameEl = document.getElementById('scan-client-name');
                    const applyOfferBtn = document.getElementById('btn-apply-offer');
                    const offersPanel = document.getElementById('offers-panel');
                    const offersList = document.getElementById('offers-list');
                    const offersLoading = document.getElementById('offers-loading');
                    const offersEmpty = document.getElementById('offers-empty');
                    const offersError = document.getElementById('offers-error');
                    const offersNotice = document.getElementById('offers-notice');
                    const offersDoneBtn = document.getElementById('btn-offers-done');

                    let html5QrCode = null;
                    let isRunning = false;
                    let currentClientId = null;

                    function setButtons(running) {
                        isRunning = running;

                        if (running) {
                            startBtn.disabled = true;
                            startBtn.classList.add('bg-gray-400', 'cursor-not-allowed');
                            startBtn.classList.remove('bg-[#2e3192]', 'hover:bg-[#25287a]');

                            stopBtn.disabled = false;
                            stopBtn.classList.remove('bg-gray-400', 'cursor-not-allowed');
                            stopBtn.classList.add('bg-[#ec008c]', 'hover:bg-[#be0070]');
                        } else {
                            startBtn.disabled = false;
                            startBtn.classList.remove('bg-gray-400', 'cursor-not-allowed');
                            startBtn.classList.add('bg-[#2e3192]', 'hover:bg-[#25287a]');

                            stopBtn.disabled = true;
                            stopBtn.classList.add('bg-gray-400', 'cursor-not-allowed');
                            stopBtn.classList.remove('bg-[#ec008c]', 'hover:bg-[#be0070]');
                        }
                    }

                    function resetResult() {
                        resultEl.textContent = '';
                        errorEl.textContent = '';
                        clientNameEl.textContent = '';
                        resultCard.classList.add('hidden');
                        hideOffersPanel();
                        offersList.innerHTML = '';
                        offersNotice.textContent = '';
                        offersError.textContent = '';
                        offersLoading.classList.add('hidden');
                        offersEmpty.classList.add('hidden');
                        offersNotice.classList.add('hidden');
                        offersError.classList.add('hidden');
                        currentClientId = null;
                    }

                    async function closeFlow() {
                        if (isRunning) {
                            await stopScanner();
                        }
                        resetResult();
                    }

                    function showOffersPanel() {
                        offersPanel.classList.remove('hidden');
                    }

                    function hideOffersPanel() {
                        offersPanel.classList.add('hidden');
                    }

                    function setOffersStatus({
                        loading = false,
                        error = '',
                        notice = '',
                        empty = false
                    }) {
                        offersLoading.classList.toggle('hidden', !loading);

                        if (error) {
                            offersError.textContent = error;
                            offersError.classList.remove('hidden');
                        } else {
                            offersError.textContent = '';
                            offersError.classList.add('hidden');
                        }

                        if (notice) {
                            offersNotice.textContent = notice;
                            offersNotice.classList.remove('hidden');
                        } else {
                            offersNotice.textContent = '';
                            offersNotice.classList.add('hidden');
                        }

                        offersEmpty.classList.toggle('hidden', !empty);
                    }

                    async function loadOffers(clientId) {
                        if (!clientId) return;

                        setOffersStatus({
                            loading: true,
                            error: '',
                            notice: '',
                            empty: false
                        });
                        offersList.innerHTML = '';

                        const response = await fetch(
                            `{{ route('business.qr.offers') }}?client_profile_id=${encodeURIComponent(clientId)}`, {
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                }
                            }
                        );

                        if (response.status === 401) {
                            window.location.href = '{{ route('login') }}';
                            return;
                        }

                        const data = await response.json();

                        if (!response.ok || !data?.ok) {
                            setOffersStatus({
                                loading: false,
                                error: data?.message || 'Could not load offers.'
                            });
                            return;
                        }

                        const offers = data.offers || [];

                        if (!offers.length) {
                            setOffersStatus({
                                loading: false,
                                empty: true
                            });
                            return;
                        }

                        offers.forEach((offer) => {
                            const row = document.createElement('div');
                            row.className = 'border border-[#2e3192]/20 rounded-3xl p-3 bg-[#2e3192]/[0.03]';

                            const header = document.createElement('div');
                            header.className = 'flex items-start justify-between gap-3';

                            const title = document.createElement('div');
                            title.className = 'font-semibold text-gray-800';
                            title.textContent = offer.title;

                            const usage = document.createElement('div');
                            usage.className = 'text-xs px-2 py-1 rounded-full bg-[#7ac143]/15 text-[#4f8a27]';
                            usage.textContent = `${offer.used_count} / ${offer.uses_per_client} redeemed`;

                            header.appendChild(title);
                            header.appendChild(usage);

                            const actions = document.createElement('div');
                            actions.className = 'mt-3 flex flex-wrap items-center gap-3';

                            const qtyWrapper = document.createElement('div');
                            qtyWrapper.className = 'flex items-center gap-2';

                            const minusBtn = document.createElement('button');
                            minusBtn.type = 'button';
                            minusBtn.textContent = '−';
                            minusBtn.className =
                                'w-10 h-10 text-xl rounded-3xl bg-[#2e3192]/10 text-[#2e3192] hover:bg-[#2e3192]/20';

                            const qtyInput = document.createElement('input');
                            qtyInput.type = 'text';
                            qtyInput.readOnly = true;
                            qtyInput.value = '1';
                            qtyInput.className =
                                'w-14 h-10 text-center border border-[#2e3192]/20 rounded-3xl text-base';

                            const plusBtn = document.createElement('button');
                            plusBtn.type = 'button';
                            plusBtn.textContent = '+';
                            plusBtn.className =
                                'w-10 h-10 text-xl rounded-3xl bg-[#2e3192]/10 text-[#2e3192] hover:bg-[#2e3192]/20';

                            minusBtn.addEventListener('click', () => {
                                const current = parseInt(qtyInput.value, 10) || 1;
                                qtyInput.value = String(Math.max(1, current - 1));
                            });

                            plusBtn.addEventListener('click', () => {
                                const current = parseInt(qtyInput.value, 10) || 1;
                                qtyInput.value = String(Math.min(100, current + 1));
                            });

                            qtyWrapper.appendChild(minusBtn);
                            qtyWrapper.appendChild(qtyInput);
                            qtyWrapper.appendChild(plusBtn);

                            const redeemBtn = document.createElement('button');
                            redeemBtn.type = 'button';
                            redeemBtn.textContent = 'Redeem';
                            redeemBtn.className =
                                'bg-[#7ac143] text-white px-4 py-2 rounded-3xl text-sm hover:bg-[#669f35]';
                            redeemBtn.addEventListener('click', async () => {
                                const qty = parseInt(qtyInput.value, 10);

                                if (!qty || qty < 1) {
                                    setOffersStatus({
                                        error: 'Quantity must be at least 1.'
                                    });
                                    return;
                                }

                                const added = await redeemOffer(offer.id, qty);
                                if (added > 0) {
                                    offer.used_count += added;
                                    usage.textContent =
                                        `${offer.used_count} / ${offer.uses_per_client} redeemed`;
                                }
                            });

                            actions.appendChild(qtyWrapper);
                            actions.appendChild(redeemBtn);

                            row.appendChild(header);
                            row.appendChild(actions);
                            offersList.appendChild(row);
                        });

                        setOffersStatus({
                            loading: false
                        });
                    }

                    async function redeemOffer(offerId, qty) {
                        if (!currentClientId) return;

                        const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                        setOffersStatus({
                            loading: true,
                            error: '',
                            notice: '',
                            empty: false
                        });

                        const response = await fetch('{{ route('business.qr.redeem') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': csrf,
                            },
                            body: JSON.stringify({
                                client_profile_id: currentClientId,
                                offer_id: offerId,
                                qty,
                            }),
                        });

                        if (response.status === 401) {
                            window.location.href = '{{ route('login') }}';
                            return;
                        }

                        const data = await response.json();

                        if (!response.ok || !data?.ok) {
                            setOffersStatus({
                                loading: false,
                                error: data?.message || 'Could not redeem offer.'
                            });
                            return 0;
                        }

                        setOffersStatus({
                            loading: false,
                            notice: data?.message || 'Redemption added.'
                        });
                        return data?.added ?? 0;
                    }

                    function extractQrData(decodedText) {
                        try {
                            if (decodedText.startsWith('http://') || decodedText.startsWith('https://')) {
                                const url = new URL(decodedText);
                                const payload = url.searchParams.get('payload');
                                const signature = url.searchParams.get('signature');

                                if (payload && signature) {
                                    return {
                                        payload,
                                        signature,
                                        isEncoded: true
                                    };
                                }
                            }
                        } catch (_) {
                            // ignore URL parse errors
                        }

                        try {
                            const parsed = JSON.parse(decodedText);
                            if (parsed?.payload && parsed?.signature) {
                                return {
                                    payload: JSON.stringify(parsed.payload),
                                    signature: parsed.signature,
                                    isEncoded: false
                                };
                            }
                        } catch (_) {
                            // ignore JSON parse errors
                        }

                        return null;
                    }

                    async function verifyQr(payload, signature, isEncoded) {
                        const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                        let payloadJson = payload;

                        if (isEncoded) {
                            try {
                                const normalized = payload.replace(/-/g, '+').replace(/_/g, '/');
                                const padded = normalized + '='.repeat((4 - (normalized.length % 4)) % 4);
                                payloadJson = atob(padded);
                            } catch (e) {
                                throw new Error('Invalid QR payload.');
                            }
                        }

                        const response = await fetch('{{ route('business.qr.verify') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': csrf,
                            },
                            body: JSON.stringify({
                                payload: payloadJson,
                                signature
                            }),
                        });

                        if (response.status === 401) {
                            window.location.href = '{{ route('login') }}';
                            return null;
                        }

                        const data = await response.json();

                        if (!response.ok || !data?.ok) {
                            throw new Error(data?.message || 'Invalid or expired QR code.');
                        }

                        return data.client;
                    }

                    async function startScanner() {
                        errorEl.textContent = '';
                        resetResult();

                        if (isRunning) return;

                        try {
                            qrRegion.classList.remove('hidden');

                            html5QrCode = new Html5Qrcode("cititor-qr");

                            await html5QrCode.start({
                                    facingMode: "environment"
                                }, {
                                    fps: 10,
                                    qrbox: 250
                                },
                                async (decodedText) => {
                                        try {
                                            const qrData = extractQrData(decodedText);

                                            if (!qrData) {
                                                throw new Error('Unrecognized QR format.');
                                            }

                                            const client = await verifyQr(qrData.payload, qrData.signature, qrData
                                                .isEncoded);

                                            if (!client) {
                                                return;
                                            }

                                            currentClientId = client.client_profile_id;
                                            clientNameEl.textContent = `${client.first_name} ${client.last_name}`;
                                            resultCard.classList.remove('hidden');
                                            resultEl.textContent = 'QR verified.';
                                        } catch (e) {
                                            errorEl.textContent = e?.message || 'Could not verify QR.';
                                        } finally {
                                            await stopScanner();
                                        }
                                    },
                                    () => {
                                        /* ignore scan errors */
                                    }
                            );

                            setButtons(true);
                        } catch (e) {
                            qrRegion.classList.add('hidden');
                            errorEl.textContent = 'Could not start camera. Please allow camera permissions.';
                            setButtons(false);
                        }
                    }

                    async function stopScanner() {
                        if (!html5QrCode || !isRunning) {
                            qrRegion.classList.add('hidden');
                            setButtons(false);
                            return;
                        }

                        try {
                            await html5QrCode.stop();
                            await html5QrCode.clear(); // clears the UI region
                        } catch (e) {
                            // ignore
                        } finally {
                            html5QrCode = null;
                            qrRegion.classList.add('hidden');
                            setButtons(false);
                        }
                    }

                    startBtn.addEventListener('click', startScanner);
                    stopBtn.addEventListener('click', stopScanner);

                    applyOfferBtn.addEventListener('click', async () => {
                        if (!currentClientId) return;

                        if (offersPanel.classList.contains('hidden')) {
                            showOffersPanel();
                            if (!offersList.children.length) {
                                await loadOffers(currentClientId);
                            }
                        } else {
                            hideOffersPanel();
                        }
                    });

                    offersDoneBtn.addEventListener('click', () => {
                        closeFlow();
                    });

                    window.addEventListener('beforeunload', () => {
                        if (html5QrCode && isRunning) {
                            html5QrCode.stop().catch(() => {});
                        }
                    });

                    setButtons(false);

                    @if (!empty($scannedClient))
                        const scannedClient = @json($scannedClient);
                        currentClientId = scannedClient.client_profile_id;
                        clientNameEl.textContent = `${scannedClient.first_name} ${scannedClient.last_name}`;
                        resultCard.classList.remove('hidden');
                        resultEl.textContent = 'QR verified.';
                    @elseif (!empty($qrOpenError))
                        errorEl.textContent = @json($qrOpenError);
                    @endif
                });
            </script>
        </div>
    </div>

    <x-site-footer />
</x-app-layout>
