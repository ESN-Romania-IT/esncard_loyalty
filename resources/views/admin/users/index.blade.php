<x-app-layout>
    <a href="{{ route('admin.dashboard') }}"
        class="inline-block mt-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        Return to Dashboard
    </a>
    <div class="max-w-full mx-auto mt-10 bg-white shadow-md p-6 rounded">

        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold">Users</h2>
            <a href="{{ route('admin.users.create') }}"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                + New User
            </a>
        </div>

        @if (session('status'))
            <div class="mb-4 p-3 rounded bg-green-100 text-green-800">
                {{ session('status') }}
            </div>
        @endif

        <form method="GET" action="{{ route('admin.users.index') }}" class="flex gap-3 mb-6">
            <input type="text" name="q" value="{{ $q }}" placeholder="Search by name or email..."
                class="w-full border p-2 rounded">

            <select name="role" class="border p-2 rounded w-32">
                <option value="">All roles</option>
                @foreach ($roles as $key => $label)
                    <option value="{{ $key }}" {{ $role === $key ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>

            <button class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-900">
                Filter
            </button>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left border">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="p-3 border">ID</th>
                        <th class="p-3 border">Name</th>
                        <th class="p-3 border">Email</th>
                        <th class="p-3 border">Role</th>
                        <th class="p-3 border w-48">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr class="border-t">
                            <td class="p-3 border">{{ $user->id }}</td>
                            <td class="p-3 border">{{ $user->display_name() }}</td>
                            <td class="p-3 border">{{ $user->email }}</td>
                            <td class="p-3 border">{{ $roles[$user->role] ?? $user->role }}</td>
                            <td class="p-3 border">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.users.edit', $user) }}"
                                        class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
                                        Edit
                                    </a>

                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                        onsubmit="return confirm('Delete this user?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-4 text-center text-gray-600">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $users->links() }}
        </div>

    </div>
</x-app-layout>
