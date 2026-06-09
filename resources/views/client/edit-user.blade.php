<x-app-layout>

    <div class="min-h-screen bg-gray-50 py-12 px-4">

        <div class="max-w-4xl mx-auto">

            <!-- HEADER -->
            <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100 mb-10">

                <div class="flex items-center justify-between flex-wrap gap-6">

                    <div class="flex items-center gap-6">
                        <img src="{{ asset('images/icons/ESN_Logo.svg') }}" alt="ESN Logo" class="h-16" />

                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">
                                My Profile
                            </h1>
                            <p class="text-sm text-gray-500">
                                Update your personal information
                            </p>
                        </div>
                    </div>

                </div>

                <!-- USER INFO PLACEHOLDER -->
                <div class="mt-8 grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">

                    <div class="bg-gray-50 rounded-xl p-4">
                        <div class="text-gray-400 text-xs uppercase">Full Name</div>
                        <div class="font-semibold text-gray-800">
                            {{ $user->profile->first_name }} {{ $user->profile->last_name }}
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-4">
                        <div class="text-gray-400 text-xs uppercase">Email</div>
                        <div class="font-semibold text-gray-800">
                            {{ $user->email }}
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-4">
                        <div class="text-gray-400 text-xs uppercase">ESN Card</div>
                        <div class="font-semibold text-gray-800">
                            {{ $user->esncard_code }}
                        </div>
                    </div>

                </div>

            </div>

            <!-- EDIT FORM CARD -->
            <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">

                <h3 class="text-xl font-semibold mb-6 text-gray-800">
                    Edit Information
                </h3>

                <form method="POST" action="{{ route('client.dashboard.update-user', $user->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">First Name</label>
                            <input type="text" name="first_name"
                                value="{{ old('first_name', $user->profile->first_name) }}"
                                class="w-full border-gray-300 rounded-xl px-4 py-2 focus:ring-[#2e3192] focus:border-[#2e3192]">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Last Name</label>
                            <input type="text" name="last_name"
                                value="{{ old('last_name', $user->profile->last_name) }}"
                                class="w-full border-gray-300 rounded-xl px-4 py-2 focus:ring-[#2e3192] focus:border-[#2e3192]">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                class="w-full border-gray-300 rounded-xl px-4 py-2 focus:ring-[#2e3192] focus:border-[#2e3192]">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">ESN Card</label>
                            <input type="text" name="esncard_code"
                                value="{{ old('esncard_code', $user->esncard_code) }}"
                                class="w-full border-gray-300 rounded-xl px-4 py-2 focus:ring-[#2e3192] focus:border-[#2e3192]">
                        </div>
                    </div>

                    <div class="mt-8 flex gap-4 justify-center">
                        <div class="mt-8">
                            <button type="submit"
                                class="px-6 py-3 rounded-full font-semibold text-white bg-[#7ac143] hover:bg-[#68a436] font-bold py-2 px-4 rounded-3xl shadow-md transition">
                                Save Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="mt-12 flex gap-4 justify-center max-w-4xl mx-auto">
            <button onclick="document.getElementById('deleteModal').classList.remove('hidden')"
                class="px-8 py-5 rounded-full font-semibold text-white bg-red-600 hover:bg-red-700 shadow-md transition">
                Delete Account
            </button>
        </div>

        <!-- Delete Modal -->
        <div id="deleteModal" class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 hidden">
            <div class="bg-white rounded-2xl shadow-xl p-8 max-w-sm w-full mx-4 text-center">
                <div class="text-4xl mb-4">⚠️</div>
                <h2 class="text-xl font-bold text-gray-800 mb-2">Ești sigur?</h2>
                <p class="text-gray-500 text-sm mb-8">Contul tău va fi șters permanent și nu poate fi recuperat.</p>

                <div class="flex gap-4 justify-center">
                    <button onclick="document.getElementById('deleteModal').classList.add('hidden')"
                        class="px-8 py-5 rounded-full font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 shadow-md transition">
                        Nu, renunț
                    </button>

                    <form method="POST" action="{{ route('client.dashboard.delete-user') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-8 py-5 rounded-full font-semibold text-white bg-red-600 hover:bg-red-700 shadow-md transition">
                            Da, șterge
                        </button>
                    </form>
                </div>
            </div>
        </div>

</x-app-layout>
