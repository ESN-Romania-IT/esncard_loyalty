<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-8 px-4">
        <div class="max-w-3xl mx-auto mt-2 bg-white shadow-md p-6 rounded-3xl border border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-2xl font-bold text-[#2e3192]">Create Offer</h2>
                    <p class="text-sm text-gray-600">Business: {{ $business->business_name }}</p>
                </div>

                <a href="{{ route('admin.businesses.offers.index', $business) }}"
                    class="bg-[#2e3192] text-white px-4 py-2 rounded-3xl text-sm hover:bg-[#25287a]">
                    ← Back to offers
                </a>
            </div>

            @if ($errors->any())
                <div class="mb-4 p-3 rounded bg-red-100 text-red-800 text-sm">Please fix the errors below.</div>
            @endif

            <form method="POST" action="{{ route('admin.businesses.offers.store', $business) }}" class="space-y-4"
                id="offer-form">
                @csrf

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Title</label>
                    <input type="text" name="title" value="{{ old('title') }}"
                        class="w-full border border-gray-300 p-2 rounded-3xl">
                    @error('title')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Offer Type</label>
                    <div class="flex gap-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="type" value="discount" class="accent-[#2e3192]"
                                {{ old('type', 'discount') === 'discount' ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700">Discount</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="type" value="stamp_card" class="accent-[#2e3192]"
                                {{ old('type') === 'stamp_card' ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700">Stamp Card</span>
                        </label>
                    </div>
                    @error('type')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div id="field-uses-per-client" class="{{ old('type') === 'stamp_card' ? 'hidden' : '' }}">
                    <label class="block mb-1 text-sm font-medium text-gray-700">Max uses per client</label>
                    <input type="number" name="uses_per_client" min="1" max="1000"
                        value="{{ old('uses_per_client', 1) }}" class="w-full border border-gray-300 p-2 rounded-3xl">
                    @error('uses_per_client')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div id="field-stamps-required" class="{{ old('type') === 'stamp_card' ? '' : 'hidden' }}">
                    <label class="block mb-1 text-sm font-medium text-gray-700">Stamps required to complete</label>
                    <input type="number" name="stamps_required" min="2" max="1000"
                        value="{{ old('stamps_required', 10) }}" class="w-full border border-gray-300 p-2 rounded-3xl">
                    <p class="text-xs text-gray-500 mt-1">How many stamps a client must collect before redeeming the
                        reward.</p>
                    @error('stamps_required')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" class="accent-[#7ac143]"
                            {{ old('is_active', 1) ? 'checked' : '' }}>
                        <span class="text-sm text-gray-700">Active</span>
                    </label>
                    @error('is_active')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full bg-[#ec008c] text-white p-2 rounded-3xl hover:bg-[#be0070]">
                    Create Offer
                </button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const radios = document.querySelectorAll('input[name="type"]');
            const fieldUses = document.getElementById('field-uses-per-client');
            const fieldStamps = document.getElementById('field-stamps-required');

            function updateFields() {
                const selected = document.querySelector('input[name="type"]:checked')?.value;
                if (selected === 'stamp_card') {
                    fieldUses.classList.add('hidden');
                    fieldStamps.classList.remove('hidden');
                } else {
                    fieldUses.classList.remove('hidden');
                    fieldStamps.classList.add('hidden');
                }
            }

            radios.forEach(r => r.addEventListener('change', updateFields));
            updateFields();
        });
    </script>

    <x-site-footer />
</x-app-layout>
