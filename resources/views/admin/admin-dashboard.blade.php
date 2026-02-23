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
                                Admin Dashboard
                            </h1>
                            <p class="text-sm text-gray-500">
                                Erasmus Student Network Romania
                            </p>
                        </div>
                    </div>

                    <!-- ROLE BADGE -->
                    <div class="px-4 py-2 bg-[#EC008C]/10 text-[#EC008C] text-xs font-semibold rounded-full">
                        ADMIN
                    </div>

                </div>

                <!-- ADMIN INFO -->
                <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">

                    <div class="bg-gray-50 rounded-xl p-4">
                        <div class="text-gray-400 text-xs uppercase">Email</div>
                        <div class="font-semibold text-gray-800">
                            {{ $user->email }}
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-4">
                        <div class="text-gray-400 text-xs uppercase">Role</div>
                        <div class="font-semibold text-gray-800">
                            {{ strtoupper($user->role) }}
                        </div>
                    </div>

                </div>

            </div>

            <!-- MANAGEMENT SECTION -->
            <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">

                <h3 class="text-xl font-semibold mb-6 text-gray-800">
                    Management Panel
                </h3>

                <div class="grid sm:grid-cols-3 gap-6">

                    <!-- USERS -->
                    <a href="{{ route('admin.users.index') }}"
                        class="group bg-[#00AEEF]/10 hover:bg-[#00AEEF] transition duration-200
                               rounded-2xl p-6 text-center shadow-md">

                        <div class="text-[#00AEEF] group-hover:text-white font-semibold">
                            Manage Users
                        </div>
                    </a>

                    <!-- CLIENTS -->
                    <a href="{{ route('admin.clients.index') }}"
                        class="group bg-[#8DC63F]/10 hover:bg-[#8DC63F] transition duration-200
                               rounded-2xl p-6 text-center shadow-md">

                        <div class="text-[#3c8d1e] group-hover:text-white font-semibold">
                            Manage Clients
                        </div>
                    </a>

                    <!-- BUSINESSES -->
                    <a href="{{ route('admin.businesses.index') }}"
                        class="group bg-[#EC008C]/10 hover:bg-[#EC008C] transition duration-200
                               rounded-2xl p-6 text-center shadow-md">

                        <div class="text-[#EC008C] group-hover:text-white font-semibold">
                            Manage Businesses
                        </div>
                    </a>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>
