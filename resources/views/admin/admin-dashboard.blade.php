<x-app-layout>
    <div class="max-w-xl mx-auto mt-10 bg-white shadow-md p-6 rounded">
        <h2 class="text-xl font-bold mb-2">Admin Dashboard</h2>
        <p class="mb-4">You are logged in as an <b>ADMIN</b>.</p>

        <div class="text-sm bg-gray-100 p-3 rounded">
            <div><b>Name:</b> {{ $user->first_name }} {{ $user->last_name }}</div>
            <div><b>Email:</b> {{ $user->email }}</div>
            <div><b>Role:</b> {{ $user->role }}</div>
        </div>

        <a href="{{ route('admin.users.index') }}"
            class="inline-block mt-4 bg-blue-600 text-white px-4 py-2 rounded hover:border-gray-700 border-transparent border-4">
            Manage Users
        </a>
        <a href="{{ route('admin.clients.index') }}"
            class="inline-block mt-4 bg-green-600 text-white px-4 py-2 rounded hover:border-gray-700 border-transparent border-4">
            Manage Clients
        </a>

        <a href="{{ route('admin.businesses.index') }}"
            class="inline-block mt-4 bg-purple-600 text-white px-4 py-2 rounded hover:border-gray-700 border-transparent border-4">
            Manage Businesses
        </a>

    </div>
</x-app-layout>
