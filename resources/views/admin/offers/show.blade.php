<x-app-layout>
    <div x-data="{ addOpen: false, removeOpen: false, removeClientId: null, removeMax: 0, removeQty: '' }" class="min-h-screen bg-gray-50 py-8 px-4">
        <div class="max-w-7xl mx-auto bg-white shadow-md p-6 rounded-3xl border border-gray-200">

            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-[#2e3192]">Offer: {{ $offer->title }}</h2>
                    <p class="text-sm text-gray-600">
                        Business: {{ $business->business_name }} · Max uses/client: {{ $offer->uses_per_client }}
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.businesses.offers.index', $business) }}"
                        class="bg-[#2e3192] text-white px-4 py-2 rounded-3xl text-sm hover:bg-[#25287a]">
                        ← Back to offers
                    </a>

                    <button type="button" @click="addOpen=true"
                        class="bg-[#ec008c] text-white px-4 py-2 rounded-3xl hover:bg-[#be0070] text-sm">
                        + Add redemption
                    </button>
                </div>
            </div>

            @if (session('status'))
                <div class="mb-4 p-3 rounded-3xl bg-[#7ac143]/15 text-[#4f8a27] border border-[#7ac143]/30">
                    {{ session('status') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-3 rounded-3xl bg-red-100 text-red-800 border border-red-200">{{ session('error') }}
                </div>
            @endif

            <!-- ADD MODAL -->
            <div x-show="addOpen" x-cloak style="display:none;"
                class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
                <div @click.away="addOpen=false"
                    class="bg-white w-full max-w-lg rounded-3xl shadow-md p-6 border border-gray-200">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-[#2e3192]">Add redemptions for this offer</h3>
                        <button type="button" @click="addOpen=false"
                            class="text-gray-500 hover:text-gray-800">✕</button>
                    </div>

                    <form method="POST"
                        action="{{ route('admin.businesses.offers.redemptions.store', [$business, $offer]) }}">
                        @csrf

                        <label class="block mb-2 text-sm font-medium text-gray-700">Client</label>
                        <select name="client_profile_id" class="w-full border border-gray-300 p-2 rounded-3xl mb-4"
                            required>
                            <option value="">Select client…</option>
                            @foreach ($clients as $c)
                                <option value="{{ $c->id }}">
                                    {{ $c->first_name }} {{ $c->last_name }} — {{ $c->user?->email }}
                                </option>
                            @endforeach
                        </select>

                        <label class="block mb-2 text-sm font-medium text-gray-700">Quantity</label>
                        <input type="number" name="qty" min="1" value="1"
                            class="w-full border border-gray-300 p-2 rounded-3xl mb-4">

                        <div class="flex justify-end gap-3">
                            <button type="button" @click="addOpen=false"
                                class="px-4 py-2 rounded-3xl border border-gray-300 hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit"
                                class="bg-[#ec008c] text-white px-4 py-2 rounded-3xl hover:bg-[#be0070]">
                                Add
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- TABLE -->
            <h3 class="font-semibold mb-3 text-[#2e3192]">Clients who redeemed this offer</h3>
            <div class="overflow-x-auto rounded-3xl border border-gray-200">
                <table class="w-full text-left">
                    <thead class="bg-[#2e3192]/10 text-[#2e3192]">
                        <tr>
                            <th class="p-3 border-b">Client</th>
                            <th class="p-3 border-b">Email</th>
                            <th class="p-3 border-b">Used</th>
                            <th class="p-3 border-b w-40">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($redemptions as $row)
                            @php $cp = $row->clientProfile; @endphp
                            <tr class="border-t">
                                <td class="p-3 border-b">
                                    <a href="{{ route('admin.clients.show', $cp) }}"
                                        class="text-[#2e3192] hover:underline font-medium">
                                        {{ $cp->first_name }} {{ $cp->last_name }}
                                    </a>
                                </td>
                                <td class="p-3 border-b">{{ $cp->user?->email }}</td>
                                <td class="p-3 border-b">
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-[#7ac143]/15 text-[#4f8a27]">
                                        {{ $row->used_count }} / {{ $offer->uses_per_client }}
                                    </span>
                                </td>
                                <td class="p-3 border-b">
                                    <button type="button"
                                        class="bg-red-600 text-white px-3 py-1 rounded-3xl hover:bg-red-700 text-sm"
                                        @click="removeClientId={{ $cp->id }}; removeMax={{ (int) $row->used_count }}; removeQty=''; removeOpen=true;">
                                        Remove
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-4 text-center text-gray-600">No redemptions yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">{{ $redemptions->links() }}</div>

            <!-- REMOVE MODAL -->
            <div x-show="removeOpen" x-cloak style="display:none;"
                class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
                <div @click.away="removeOpen=false"
                    class="bg-white w-full max-w-md rounded-3xl shadow-md p-6 border border-gray-200">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-[#2e3192]">Remove redemptions (FIFO)</h3>
                        <button type="button" @click="removeOpen=false"
                            class="text-gray-500 hover:text-gray-800">✕</button>
                    </div>

                    <p class="text-sm text-gray-600 mb-4">
                        Current redemptions: <span class="font-semibold" x-text="removeMax"></span>
                    </p>

                    <form method="POST"
                        action="{{ route('admin.businesses.offers.redemptions.destroyForClient', [$business, $offer]) }}">
                        @csrf
                        @method('DELETE')

                        <input type="hidden" name="client_profile_id" :value="removeClientId">

                        <label class="block mb-2 text-sm font-medium text-gray-700">Quantity to remove</label>
                        <input type="number" name="qty" min="1" :max="removeMax" x-model="removeQty"
                            class="w-full border border-gray-300 p-2 rounded-3xl mb-4">

                        <div class="flex justify-end gap-3">
                            <button type="button" @click="removeOpen=false"
                                class="px-4 py-2 rounded-3xl border border-gray-300 hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-3xl hover:bg-red-700"
                                :disabled="removeMax === 0">
                                Remove
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <x-site-footer />
</x-app-layout>
