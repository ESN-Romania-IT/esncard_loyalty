<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Login
        </h2>
    </x-slot>

    <form method="POST" action="{{ route('login.submit') }}" class="max-w-md mx-auto mt-6">
        @csrf

        <div class="mb-4">
            <label class="block font-bold mb-1">Name</label>
            <input name="name" class="w-full border rounded px-3 py-2" required>
            @error('name')
                <p class="text-red-500 text-xs">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block font-bold mb-1">Surname</label>
            <input name="surname" class="w-full border rounded px-3 py-2" required>
            @error('surname')
                <p class="text-red-500 text-xs">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block font-bold mb-1">ESNcard Serial</label>
            <input name="esncard_serial_code" class="w-full border rounded px-3 py-2" required>
            @error('esncard_serial_code')
                <p class="text-red-500 text-xs">{{ $message }}</p>
            @enderror
        </div>

        <button class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
            Login
        </button>
    </form>
</x-app-layout>
