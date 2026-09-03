<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Business Locations</h2>
    </x-slot>

    <div class="p-6">
        <x-business-map-widget :locations="$locations" />
    </div>
</x-app-layout>
