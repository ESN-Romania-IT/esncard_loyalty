<x-app-layout>
    <div class="max-w-md mx-auto mt-10 bg-white shadow-md p-6 rounded">
        <h2 class="text-xl font-bold mb-2">Admin Dashboard</h2>
        <p class="mb-4">You are logged in as an <b>ADMIN</b>.</p>

        <div class="text-sm bg-gray-100 p-3 rounded">
            <div><b>Name:</b> {{ $user->first_name }} {{ $user->last_name }}</div>
            <div><b>Email:</b> {{ $user->email }}</div>
            <div><b>Role:</b> {{ $user->role }}</div>
        </div>
    </div>
</x-app-layout>
