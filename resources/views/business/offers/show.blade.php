<x-app-layout>
    <div class="max-w-3xl mx-auto mt-10 bg-white shadow-md p-6 rounded">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold">Offer: {{ $offer->title }}</h2>
            <div class="flex items-center gap-3">
                <a href="{{ route('business.offers.index') }}" class="text-blue-600 hover:underline text-sm">
                    ← Back to Offers
                </a>
                <a href="{{ route('business.offers.edit', $offer) }}"
                    class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
                    Edit
                </a>
            </div>
        </div>

        <div class="space-y-2 text-sm text-gray-700">
            <div><span class="font-semibold">Max uses / client:</span> {{ $offer->uses_per_client }}</div>
            <div><span class="font-semibold">Active:</span> {{ $offer->is_active ? 'Yes' : 'No' }}</div>
            <div><span class="font-semibold">Total redemptions:</span> {{ $offer->redemptions_count }}</div>
        </div>

        <div class="mt-8">
            <h3 class="text-lg font-semibold mb-3">Clients who redeemed</h3>

            @if ($clients->isEmpty())
                <div class="text-sm text-gray-500">No redemptions yet.</div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="p-3 border">Client</th>
                                <th class="p-3 border">Redemptions</th>
                                <th class="p-3 border">Last redeemed</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($clients as $row)
                                <tr class="border-t">
                                    <td class="p-3 border">
                                        {{ $row->clientProfile?->first_name }} {{ $row->clientProfile?->last_name }}
                                    </td>
                                    <td class="p-3 border">{{ $row->used_count }}</td>
                                    <td class="p-3 border">
                                        {{ $row->last_redeemed_at ? \Illuminate\Support\Carbon::parse($row->last_redeemed_at)->format('Y-m-d H:i') : '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
