<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-8 px-4">
        <div class="max-w-7xl mx-auto bg-white shadow-md p-6 rounded-3xl border border-gray-200">

            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-2xl font-bold text-[#2e3192]">Users</h2>
                    <p class="text-sm text-gray-600">Manage all platform users and roles</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.dashboard') }}"
                        class="bg-[#2e3192] text-white px-4 py-2 rounded-3xl text-sm hover:bg-[#25287a]">
                        ← Back to Dashboard
                    </a>
                    <a href="{{ route('admin.users.create') }}"
                        class="bg-[#ec008c] text-white px-4 py-2 rounded-3xl hover:bg-[#be0070] text-sm">
                        + New User
                    </a>
                </div>
            </div>

            @if (session('status'))
                <div class="mb-4 p-3 rounded-3xl bg-[#7ac143]/15 text-[#4f8a27] border border-[#7ac143]/30">
                    {{ session('status') }}
                </div>
            @endif

            <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap gap-3 mb-6">
                <input type="text" name="q" value="{{ $q }}"
                    placeholder="Search by name or email..."
                    class="flex-1 min-w-[240px] border border-gray-300 p-2 rounded-3xl">

                <select name="role" class="border border-gray-300 p-2 rounded-3xl w-full sm:w-40">
                    <option value="">All roles</option>
                    @foreach ($roles as $key => $label)
                        <option value="{{ $key }}" {{ $role === $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>

                <button class="bg-[#2e3192] text-white px-4 py-2 rounded-3xl hover:bg-[#25287a]">
                    Filter
                </button>
            </form>

            <div class="overflow-x-auto rounded-3xl border border-gray-200">
                <table class="w-full text-left">
                    <thead class="bg-[#2e3192]/10 text-[#2e3192]">
                        <tr>
                            <th class="p-3 border-b">ID</th>
                            <th class="p-3 border-b">Name</th>
                            <th class="p-3 border-b">Email</th>
                            <th class="p-3 border-b">Role</th>
                            <th class="p-3 border-b w-48">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr class="border-t">
                                <td class="p-3 border-b">{{ $user->id }}</td>
                                <td class="p-3 border-b font-medium text-gray-800">{{ $user->display_name() }}</td>
                                <td class="p-3 border-b">{{ $user->email }}</td>
                                <td class="p-3 border-b">
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-[#00AEEF]/15 text-[#007fb0]">
                                        {{ $roles[$user->role] ?? $user->role }}
                                    </span>
                                </td>
                                <td class="p-3 border-b">
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.users.edit', $user) }}"
                                            class="bg-[#ec008c] text-white px-3 py-1 rounded-3xl hover:bg-[#be0070] text-sm">
                                            Edit
                                        </a>

                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                            onsubmit="return confirm('Delete this user?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="bg-red-600 text-white px-3 py-1 rounded-3xl hover:bg-red-700 text-sm">
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

    </div>

    <x-site-footer />
</x-app-layout>
