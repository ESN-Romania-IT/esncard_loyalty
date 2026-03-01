<x-app-layout>
    <section class="relative w-full h-[320px] overflow-hidden">
        <div class="absolute inset-0 bg-pink-600"></div>
        <div class="absolute left-0 top-0 h-full w-1/2 bg-white rounded-r-full"></div>

        <div class="relative z-10 h-full max-w-7xl mx-auto grid grid-cols-2 items-center px-8">

            <div class="flex items-center gap-4 pr-4">
                <img src="{{ asset('images/icons/ESN_Logo.svg') }}" class="h-16" />
                <div>
                    <h1 class="text-4xl font-bold ">ESN</h1>
                    <p class="hidden md:block text-sm">Erasmus Student Network</p>
                    <p class="font-semibold">Romania</p>
                </div>
            </div>

            <div class="text-white max-w-md justify-self-end text-right">
                <h2 class="text-3xl font-semibold">Digital Loyalty Card</h2>
                <p class="hidden md:block mt-2 text-sm leading-relaxed">
                    Your digital passport to exclusive student benefits.<br>
                    No plastic, just perks.
                </p>
            </div>

        </div>
    </section>


    <section class="max-w-6xl mx-auto px-6 py-24 grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
        <div>
            <h3 class="text-2xl font-semibold mb-4">What is ESNcard Loyalty?</h3>
            <p class="text-gray-600 mb-4">
                The ESNcard Loyalty App is a digital initiative by ESN Romania that
                transforms the traditional ESNcard into a modern and paperless experience.
            </p>
            <p class="text-gray-600">
                Through the app, international and local students can quickly access
                discounts and exclusive offers from ESN Romania's partners.
            </p>
        </div>

        <div class="flex justify-center">
            <div class="bg-white p-4 shadow-lg rounded-xl">
                {!! QrCode::size(180)->generate('DEMO_QR') !!}
                <p class="mt-2 text-center font-semibold">SCAN ME</p>
            </div>
        </div>
    </section>

    <section class="bg-[#EBEBEB] py-24">
        <h3 class="text-center text-2xl font-semibold mb-10">How it Works</h3>

        <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 px-6">
            @foreach ([['images/icons/login-icon.png', 'Login', 'Students log in using their ESNcard number'], ['images/icons/qr-icon.png', 'Scan & Collect', 'When visiting a partner, the barista scans your QR code to apply a digital stamp'], ['images/icons/gift-icon.png', 'Get Rewards', 'After collecting stamps, receive a free drink or discount']] as $i => [$image, $title, $desc])
                <div class="bg-[#FFF8F8] rounded-2xl p-6 shadow text-center">
                    <img src="{{ asset($image) }}" alt="{{ $title }}" class="mx-auto mb-4 h-16 w-16" />
                    <h4 class="font-semibold mb-2">{{ $i + 1 }}. {{ $title }}</h4>
                    <p class="text-gray-500 text-sm">{{ $desc }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <section class="py-24">
        <h3 class="text-center text-2xl font-semibold mb-10">
            Why ESNcard Loyalty?
        </h3>

        <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-10 px-6 text-center">
            <div>
                <div class="mx-auto mb-4 h-20 w-20 rounded-full border flex items-center justify-center">
                    <img src="{{ asset('images/icons/sustainable-icon.png') }}" alt="Sustainable" class="h-18 w-18" />
                </div>
                <h4 class="font-semibold">Sustainable</h4>
                <p class="text-gray-500 text-sm">
                    Promotes sustainability with no plastic cards
                </p>
            </div>

            <div>
                <div class="mx-auto mb-4 h-20 w-20 rounded-full border flex items-center justify-center">
                    <img src="{{ asset('images/icons/phone-icon.png') }}" alt="Simple" class="h-18 w-18" />
                </div>
                <h4 class="font-semibold">Simple</h4>
                <p class="text-gray-500 text-sm">
                    Simplifies the loyalty process for students and cafés
                </p>
            </div>

            <div>
                <div class="mx-auto mb-4 h-20 w-20 rounded-full border flex items-center justify-center">
                    <img src="{{ asset('images/icons/contact-icon.png') }}" alt="Connected" class="h-18 w-18" />
                </div>
                <h4 class="font-semibold">Connected</h4>
                <p class="text-gray-500 text-sm">
                    Strengthens the connection between ESN Romania, students, and local partners
                </p>
            </div>
        </div>
    </section>

    <footer class="bg-gray-100 py-6 text-center text-sm text-gray-500">
        © 2026 ESN Romania. All rights reserved.
    </footer>

</x-app-layout>
