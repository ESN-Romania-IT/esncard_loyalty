<x-app-layout>
    <header class="flex flex-col items-center justify-center mt-2 bg-white p-6 rounded-b-full h-44">
        <a href="/" class="flex items-center justify-center">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-12 w-auto">
        </a>
        <h1 class="text-3xl font-bold text-center mt-4 text-[#2e3192]">Business Profile</h1>
    </header>

    <div class="max-w-6xl mx-auto mt-10 px-6 pb-10">
        <div class="bg-white shadow-md p-6 rounded-3xl border border-gray-200">

            <div class="text-sm bg-[#2e3192]/5 p-4 rounded-3xl border border-[#2e3192]/20 mb-6">
                <div class="flex items-center justify-between mb-2">
                    <h2 class="text-base font-semibold text-[#2e3192]">Profile Details</h2>
                    <button type="button" id="profile-edit-btn"
                        class="px-3 py-1.5 rounded-3xl text-xs font-semibold text-[#2e3192] border border-[#2e3192]/30 hover:bg-[#2e3192]/5">
                        Edit
                    </button>
                </div>

                <div id="profile-view">
                    <div><b>Name:</b> <span id="profile-name-display">{{ $user->business_profile->business_name }}</span></div>
                    <div><b>Email:</b> <span id="profile-email-display">{{ $user->email }}</span></div>
                </div>

                <div id="profile-edit-form" class="hidden mt-2 space-y-3">
                    <div>
                        <label for="profile-name-input" class="block text-xs text-gray-600 mb-1">Business Name</label>
                        <input type="text" id="profile-name-input"
                            class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#2e3192]"
                            value="{{ $user->business_profile->business_name }}">
                        <p id="profile-name-error" class="hidden text-xs text-red-600 mt-1"></p>
                    </div>
                    <div>
                        <label for="profile-email-input" class="block text-xs text-gray-600 mb-1">Email</label>
                        <input type="email" id="profile-email-input"
                            class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#2e3192]"
                            value="{{ $user->email }}">
                        <p id="profile-email-error" class="hidden text-xs text-red-600 mt-1"></p>
                    </div>
                    <div class="flex justify-end gap-2 pt-1">
                        <button type="button" id="profile-cancel-btn"
                            class="px-4 py-2 rounded-3xl text-sm font-semibold text-gray-600 hover:bg-gray-100">
                            Cancel
                        </button>
                        <button type="button" id="profile-save-btn"
                            class="px-4 py-2 rounded-3xl text-sm font-semibold text-white bg-[#2e3192] hover:bg-[#25287a]">
                            Save
                        </button>
                    </div>
                    <p id="profile-success" class="hidden text-xs text-green-600"></p>
                </div>
            </div>

            <div class="mt-6 bg-white shadow-md p-6 rounded-3xl border border-gray-200">
                <h2 class="text-xl font-bold mb-4 text-center text-[#2e3192]">Your Locations</h2>
                <x-business-map-widget :locations="$locations" />
            </div>

        </div>
    </div>

    <script>
        const profileEditBtn = document.getElementById('profile-edit-btn');
        const profileView = document.getElementById('profile-view');
        const profileEditForm = document.getElementById('profile-edit-form');
        const profileCancelBtn = document.getElementById('profile-cancel-btn');
        const profileSaveBtn = document.getElementById('profile-save-btn');
        const nameInput = document.getElementById('profile-name-input');
        const emailInput = document.getElementById('profile-email-input');
        const nameError = document.getElementById('profile-name-error');
        const emailError = document.getElementById('profile-email-error');
        const nameDisplay = document.getElementById('profile-name-display');
        const emailDisplay = document.getElementById('profile-email-display');
        const successMsg = document.getElementById('profile-success');

        function openEdit() {
            profileView.classList.add('hidden');
            profileEditForm.classList.remove('hidden');
            profileEditBtn.classList.add('hidden');
            nameError.classList.add('hidden');
            emailError.classList.add('hidden');
            successMsg.classList.add('hidden');
        }

        function closeEdit() {
            profileView.classList.remove('hidden');
            profileEditForm.classList.add('hidden');
            profileEditBtn.classList.remove('hidden');
            nameInput.value = nameDisplay.textContent;
            emailInput.value = emailDisplay.textContent;
        }

        profileEditBtn.addEventListener('click', openEdit);
        profileCancelBtn.addEventListener('click', closeEdit);

        profileSaveBtn.addEventListener('click', () => {
            nameError.classList.add('hidden');
            emailError.classList.add('hidden');
            successMsg.classList.add('hidden');

            fetch('{{ route('business.profile.update') }}', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    business_name: nameInput.value.trim(),
                    email: emailInput.value.trim(),
                }),
            })
                .then(async res => {
                    const data = await res.json();

                    if (res.status === 422) {
                        if (data.errors?.business_name) {
                            nameError.textContent = data.errors.business_name[0];
                            nameError.classList.remove('hidden');
                        }
                        if (data.errors?.email) {
                            emailError.textContent = data.errors.email[0];
                            emailError.classList.remove('hidden');
                        }
                        return;
                    }

                    if (!res.ok) throw new Error('Update failed');

                    nameDisplay.textContent = data.business_name;
                    emailDisplay.textContent = data.email;
                    successMsg.textContent = data.message;
                    successMsg.classList.remove('hidden');
                    setTimeout(closeEdit, 1000);
                })
                .catch(err => console.error(err));
        });
    </script>

    <x-site-footer />
</x-app-layout>
