<x-app-layout>
    <div x-data="{ addOpen:false, removeOpen:false, removeClientId:null, removeMax:0, removeQty:'' }">
        <div class="max-w-7xl mx-auto mt-10 bg-white shadow-md p-6 rounded">

        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold">Offer: {{ $offer->title }}</h2>
                <p class="text-sm text-gray-600">
                    Business: {{ $business->business_name }} · Max uses/client: {{ $offer->uses_per_client }}
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.businesses.offers.index', $business) }}"
                class="text-blue-600 hover:underline text-sm">
                    ← Back to offers
                </a>

                <button type="button" @click="addOpen=true"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    + Add redemption
                </button>
            </div>
        </div>

        @if (session('status'))
            <div class="mb-4 p-3 rounded bg-green-100 text-green-800">{{ session('status') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-4 p-3 rounded bg-red-100 text-red-800">{{ session('error') }}</div>
        @endif

        <!-- ADD MODAL -->
        <div x-show="addOpen" x-cloak style="display:none;"
            class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div @click.away="addOpen=false" class="bg-white w-full max-w-lg rounded shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold">Add redemptions for this offer</h3>
                    <button type="button" @click="addOpen=false" class="text-gray-500 hover:text-gray-800">✕</button>
                </div>

                <form method="POST" action="{{ route('admin.businesses.offers.redemptions.store', [$business, $offer]) }}">
                    @csrf

                    <label class="block mb-2">Client</label>
                    <select name="client_profile_id" class="w-full border p-2 rounded mb-4" required>
                        <option value="">Select client…</option>
                        @foreach($clients as $c)
                            <option value="{{ $c->id }}">
                                {{ $c->first_name }} {{ $c->last_name }} — {{ $c->user?->email }}
                            </option>
                        @endforeach
                    </select>

                    <label class="block mb-2">Quantity</label>
                    <input type="number" name="qty" min="1" value="1"
                        class="w-full border p-2 rounded mb-4">

                    <div class="flex justify-end gap-3">
                        <button type="button" @click="addOpen=false"
                            class="px-4 py-2 rounded border hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Add
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- TABLE -->
        <h3 class="font-semibold mb-3">Clients who redeemed this offer</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left border">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="p-3 border">Client</th>
                        <th class="p-3 border">Email</th>
                        <th class="p-3 border">Used</th>
                        <th class="p-3 border w-40">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($redemptions as $row)
                        @php $cp = $row->clientProfile; @endphp
                        <tr class="border-t">
                            <td class="p-3 border">
                                <a href="{{ route('admin.clients.show', $cp) }}" class="text-blue-600 hover:underline">
                                    {{ $cp->first_name }} {{ $cp->last_name }}
                                </a>
                            </td>
                            <td class="p-3 border">{{ $cp->user?->email }}</td>
                            <td class="p-3 border">{{ $row->used_count }} / {{ $offer->uses_per_client }}</td>
                            <td class="p-3 border">
                                <button type="button"
                                    class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700"
                                    @click="removeClientId={{ $cp->id }}; removeMax={{ (int)$row->used_count }}; removeQty=''; removeOpen=true;">
                                    Remove
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="p-4 text-center text-gray-600">No redemptions yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">{{ $redemptions->links() }}</div>

        <!-- REMOVE MODAL -->
        <div x-show="removeOpen" x-cloak style="display:none;"
            class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div @click.away="removeOpen=false" class="bg-white w-full max-w-md rounded shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold">Remove redemptions (FIFO)</h3>
                    <button type="button" @click="removeOpen=false" class="text-gray-500 hover:text-gray-800">✕</button>
                </div>

                <p class="text-sm text-gray-600 mb-4">
                    Current redemptions: <span class="font-semibold" x-text="removeMax"></span>
                </p>

                <form method="POST" action="{{ route('admin.businesses.offers.redemptions.destroyForClient', [$business, $offer]) }}">
                    @csrf
                    @method('DELETE')

                    <input type="hidden" name="client_profile_id" :value="removeClientId">

                    <label class="block mb-2">Quantity to remove</label>
                    <input type="number" name="qty" min="1" :max="removeMax"
                        x-model="removeQty"
                        class="w-full border p-2 rounded mb-4">

                    <div class="flex justify-end gap-3">
                        <button type="button" @click="removeOpen=false"
                                class="px-4 py-2 rounded border hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit"
                                class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700"
                                :disabled="removeMax === 0">
                            Remove
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    </div>
</x-app-layout>
