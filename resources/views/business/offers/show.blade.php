<x-app-layout>
    <div class="max-w-3xl mx-auto mt-10 bg-white shadow-md p-6 rounded-3xl border border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-l font-bold text-[#2e3192]">{{ $offer->title }}</h2>
            <div class="flex items-center gap-3">
                <a href="{{ route('business.offers.index') }}"
                    class="bg-[#2e3192] text-white px-4 py-2 rounded-3xl text-sm hover:bg-[#25287a] inline-flex items-center gap-1">
                    ← Offers
                </a>
                <a href="{{ route('business.offers.edit', $offer) }}"
                    class="bg-[#ec008c] text-white px-3 py-1 rounded-3xl hover:bg-[#be0070]">
                    Edit
                </a>
            </div>
        </div>

        <div class="space-y-2 text-sm text-gray-700 bg-[#2e3192]/5 p-4 rounded-3xl border border-[#2e3192]/20">
            <div><span class="font-semibold">Max uses / client:</span> {{ $offer->uses_per_client }}</div>
            <div>
                <span class="font-semibold">Active:</span>
                <span
                    class="inline-flex items-center px-2 py-1 rounded-full text-xs {{ $offer->is_active ? 'bg-[#7ac143]/15 text-[#4f8a27]' : 'bg-[#ec008c]/15 text-[#a2005f]' }}">
                    {{ $offer->is_active ? 'Yes' : 'No' }}
                </span>
            </div>
            <div>
                <span class="font-semibold">Total redemptions:</span>
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-[#7ac143]/15 text-[#4f8a27]">
                    {{ $offer->redemptions_count }}
                </span>
            </div>
        </div>

        <div class="mt-8">
            <h3 class="text-lg font-semibold mb-3">Clients who redeemed</h3>

            @if ($clients->isEmpty())
                <div class="text-sm text-gray-500">No redemptions yet.</div>
            @else
                <div class="overflow-x-auto rounded-3xl border border-gray-200">
                    <table class="w-full text-left">
                        <thead class="bg-[#2e3192]/10 text-[#2e3192]">
                            <tr>
                                <th class="p-3 border-b">Client</th>
                                <th class="p-3 border-b">Redemptions</th>
                                <th class="p-3 border-b">Last redeemed</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($clients as $row)
                                <tr class="border-t">
                                    <td class="p-3 border-b">
                                        {{ $row->clientProfile?->first_name }} {{ $row->clientProfile?->last_name }}
                                    </td>
                                    <td class="p-3 border-b">
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-[#7ac143]/15 text-[#4f8a27]">
                                            {{ $row->used_count }}
                                        </span>
                                    </td>
                                    <td class="p-3 border-b">
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

    <x-site-footer />
</x-app-layout>
