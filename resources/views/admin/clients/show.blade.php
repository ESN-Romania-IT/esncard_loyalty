<x-app-layout>
    <div x-data="{
        open: false,
        businesses: @js($businesses),
        usedByOffer: @js($usedByOffer),
    
        businessId: null,
        offerId: null,
        qty: 1,
        removeOpen: false,
        removeOfferId: null,
        removeOfferTitle: '',
        removeMaxQty: 1,
        removeQty: 1,
    
        openRemove(offerId, offerTitle, currentCount) {
            this.removeOfferId = offerId;
            this.removeOfferTitle = offerTitle;
            this.removeMaxQty = Number(currentCount);
            this.removeQty = Math.min(1, this.removeMaxQty || 1);
            this.removeOpen = true;
        },
        normalizeRemoveQty() {
            if (this.removeQty === '' || this.removeQty === null) {
                this.removeQty = 1;
                return;
            }
    
            let v = Number(this.removeQty);
            if (Number.isNaN(v) || v < 1) v = 1;
            if (v > this.removeMaxQty) v = this.removeMaxQty;
    
            this.removeQty = v;
        },
    
    
    
        offersForBusiness() {
            const b = this.businesses.find(x => Number(x.id) === Number(this.businessId));
            return b?.offers ?? [];
        },
    
        selectedOffer() {
            return this.offersForBusiness().find(o => Number(o.id) === Number(this.offerId)) || null;
        },
    
        usedCount() {
            return Number(this.usedByOffer?.[this.offerId] ?? 0);
        },
    
        maxUses() {
            return Number(this.selectedOffer()?.uses_per_client ?? 0);
        },
    
        remainingUses() {
            return Math.max(0, this.maxUses() - this.usedCount());
        },
    
        normalizeQty() {
            const rem = this.remainingUses();
    
            // allow empty while typing
            if (this.qty === '' || this.qty === null) {
                this.qty = 1;
                return;
            }
    
            let v = Number(this.qty);
    
            if (Number.isNaN(v) || v < 1) v = 1;
            if (rem > 0 && v > rem) v = rem;
    
            this.qty = v;
        },
    
    
        onBusinessChange() {
            this.offerId = null;
            this.qty = 1;
        },
    
        onOfferChange() {
            const rem = this.remainingUses();
            this.qty = rem > 0 ? 1 : 1;
            this.clampQty();
        }
    }" class="mb-6">
        <div class="max-w-7xl mx-auto mt-10 bg-white shadow-md p-6 rounded">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-xl font-bold">
                        Client: {{ $client->first_name }} {{ $client->last_name }}
                    </h2>
                    <p class="text-sm text-gray-600">
                        Email: {{ $client->user?->email }}
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.clients.index') }}" class="text-blue-600 hover:underline text-sm">
                        ← Back to clients
                    </a>

                    <button type="button" @click="open = true"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        + Add redemption
                    </button>
                </div>
            </div>
            <form method="GET" class="flex gap-3 mb-6">
                <input type="text" name="q" value="{{ $q }}"
                    placeholder="Search by business or offer name..." class="flex-1 border p-2 rounded">

                <button class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-900">
                    Search
                </button>

                @if ($q)
                    <a href="{{ route('admin.clients.show', $client) }}"
                        class="px-4 py-2 rounded border hover:bg-gray-50">
                        Clear
                    </a>
                @endif
            </form>
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
            <h3 class="font-semibold mb-3">Accessed offers</h3>

            <div class="overflow-x-auto">
                <table class="w-full text-left border">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="p-3 border">Business</th>
                            <th class="p-3 border">Offer</th>
                            <th class="p-3 border">Redeemed</th>
                            <th class="p-3 border">Max / Client</th>
                            <th class="p-3 border">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rows as $row)
                            @php
                                $locked = (int) $row->redeemed_count >= (int) $row->uses_per_client;
                            @endphp

                            <tr class="border-t">
                                <td class="p-3 border">
                                    <a href="{{ route('admin.businesses.show', $row->business_id) }}"
                                        class="text-blue-600 hover:underline">
                                        {{ $row->business_name }}
                                    </a>
                                </td>

                                <td class="p-3 border">
                                    <a href="{{ route('admin.businesses.offers.show', [$row->business_id, $row->offer_id]) }}"
                                        class="text-blue-600 hover:underline">
                                        {{ $row->offer_title }}
                                    </a>
                                </td>

                                <td class="p-3 border">{{ $row->redeemed_count }}</td>
                                <td class="p-3 border">{{ $row->uses_per_client }}</td>
                                <td class="p-3 border">{{ $locked ? 'Locked out' : 'Available' }}</td>
                                <td clampQty="p-3 border">
                                    <button type="button"
                                        class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700"
                                        @click="openRemove({{ $row->offer_id }}, @js($row->offer_title), {{ $row->redeemed_count }})"
                                        {{ $row->redeemed_count > 0 ? '' : 'disabled' }}>
                                        Remove
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-4 text-center text-gray-600">No offer usage yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $rows->links() }}
            </div>
        </div>
        <div x-show="open" x-cloak style="display:none;"
            class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div @click.away="open = false" class="bg-white w-full max-w-lg rounded shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold">Redeem an offer for this client</h3>
                    <button type="button" @click="open = false" class="text-gray-500 hover:text-gray-800">✕</button>
                </div>

                <form method="POST" action="{{ route('admin.clients.redemptions.store', $client) }}">
                    @csrf

                    <label class="block mb-2">Business</label>
                    <select x-model="businessId" @change="onBusinessChange()" class="w-full border p-2 rounded mb-4"
                        required>
                        <option value="">Select business…</option>
                        <template x-for="b in businesses" :key="b.id">
                            <option :value="b.id" x-text="b.business_name"></option>
                        </template>
                    </select>

                    <label class="block mb-2">Offer</label>
                    <select x-model="offerId" name="offer_id" @change="onOfferChange()"
                        class="w-full border p-2 rounded mb-2" :disabled="!businessId" required>
                        <option value="">Select offer…</option>
                        <template x-for="o in offersForBusiness()" :key="o.id">
                            <option :value="o.id" x-text="o.title + ' (max ' + o.uses_per_client + ')'">
                            </option>
                        </template>
                    </select>

                    <p class="text-sm text-gray-600 mb-4" x-show="offerId">
                        Used: <span x-text="usedCount()"></span>
                        / <span x-text="maxUses()"></span>
                        · Remaining: <span x-text="remainingUses()"></span>
                    </p>

                    <label class="block mb-2">How many redemptions?</label>
                    <input type="number" name="qty" x-model="qty" min="1" :max="remainingUses()"
                        :disabled="!offerId || remainingUses() === 0" @blur="normalizeQty()"
                        class="w-full border p-2 rounded mb-4">


                    <div class="flex justify-end gap-3">
                        <button type="button" @click="open = false" class="px-4 py-2 rounded border hover:bg-gray-50">
                            Cancel
                        </button>

                        <button type="submit" :disabled="!offerId || remainingUses() === 0"
                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 disabled:opacity-50">
                            Redeem
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div x-show="removeOpen" x-cloak style="display:none;"
            class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div @click.away="removeOpen = false" class="bg-white w-full max-w-md rounded shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold">Remove redemptions</h3>
                    <button type="button" @click="removeOpen = false"
                        class="text-gray-500 hover:text-gray-800">✕</button>
                </div>

                <p class="text-sm text-gray-700 mb-4">
                    Offer: <span class="font-semibold" x-text="removeOfferTitle"></span><br>
                    Current redemptions: <span class="font-semibold" x-text="removeMaxQty"></span>
                </p>

                <form method="POST" action="{{ route('admin.clients.redemptions.destroyForOffer', $client) }}">
                    @csrf
                    @method('DELETE')

                    <input type="hidden" name="offer_id" :value="removeOfferId">

                    <label class="block mb-2">How many to remove?</label>
                    <input type="number" name="qty" x-model="removeQty" min="1" :max="removeMaxQty"
                        @blur="normalizeRemoveQty()" class="w-full border p-2 rounded mb-4">


                    <div class="flex justify-end gap-3">
                        <button type="button" @click="removeOpen = false"
                            class="px-4 py-2 rounded border hover:bg-gray-50">
                            Cancel
                        </button>

                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700"
                            :disabled="removeMaxQty === 0">
                            Remove
                        </button>
                    </div>
                </form>
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
