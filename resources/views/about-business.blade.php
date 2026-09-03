<x-app-layout>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,500;0,9..144,600;1,9..144,500&family=Inter:wght@400;500;600&display=swap');

        :root {
            --paper: #FAF9F6;
            --ink: #1C1917;
            --magenta: #E4067E;
            --plum: #341128;
            --sand: #F1EAE2;
            --mauve: #8B7E87;
        }

        .font-display {
            font-family: 'Fraunces', serif;
        }

        .font-body {
            font-family: 'Inter', sans-serif;
        }
    </style>

    <div class="font-body bg-[var(--paper)]">

        {{-- HERO --}}
        <section class="relative overflow-hidden">
            <div class="max-w-6xl mx-auto px-6 md:px-8 pt-10 md:pt-12">
                <a href="{{ auth()->check() ? route('me') : route('login') }}" class="inline-flex items-center gap-2">
                    <img src="{{ asset('images/icons/ESN_Logo.svg') }}" alt="ESN Logo" class="h-10 w-auto">
                    <span class="text-xl font-semibold text-black tracking-wide">
                        ESN Loyalty
                    </span>
                </a>
            </div>

            <div
                class="max-w-6xl mx-auto px-6 md:px-8 pt-6 pb-20 md:pb-28 grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
                <div>
                    <p class="text-xs tracking-[0.2em] uppercase text-[var(--magenta)] font-semibold mb-4">
                        ESNcard Loyalty &middot; Parteneri
                    </p>

                    <h1 class="font-display text-4xl md:text-[3.25rem] leading-[1.08] text-[var(--ink)] mb-6">
                        Studenții internaționali vin.<br />
                        <span class="italic text-[var(--magenta)]">Afacerea ta</span> crește.
                    </h1>

                    <p class="text-[var(--ink)]/70 text-base md:text-lg leading-relaxed max-w-md mb-9">
                        Conectează-te cu mii de studenți Erasmus și membri ESN printr-un singur cod QR —
                        fără carduri fizice, fără costuri de implementare.
                    </p>

                    <a href="#contact"
                        class="inline-flex items-center gap-2 bg-[var(--ink)] text-white text-sm font-medium px-6 py-3.5 rounded-full hover:bg-[var(--magenta)] transition-colors">
                        Devino partener
                        <svg class="w-4 h-4" viewBox="0 0 16 16" fill="none">
                            <path d="M3.5 8h9M8.5 3.5L13 8l-4.5 4.5" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </a>
                </div>
            </div>
        </section>
        {{-- WHAT IT MEANS --}}
        <section
            class="max-w-6xl mx-auto px-6 md:px-8 py-20 md:py-28 grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
            <div>
                <h2 class="font-display text-3xl md:text-4xl text-[var(--ink)] mb-6 leading-[1.15]">
                    Ce înseamnă să fii partener ESNcard Loyalty
                </h2>
                <p class="text-[var(--ink)]/70 leading-relaxed mb-5">
                    ESNcard Loyalty conectează afacerea ta cu mii de studenți Erasmus și studenți locali
                    din întreaga țară, printr-un sistem digital simplu de fidelizare bazat pe cod QR.
                </p>
                <p class="text-[var(--ink)]/70 leading-relaxed">
                    Fără carduri fizice, fără proceduri complicate — doar o scanare rapidă și o comunitate
                    de tineri care revin constant la partenerii ESN.
                </p>
            </div>

            <div class="flex justify-center">
                <div class="w-full max-w-xs rounded-[1.75rem] bg-[var(--sand)] p-10 flex items-center justify-center">
                    {{-- stylised QR mark, grounded in the product's actual scan mechanism --}}
                    <svg viewBox="0 0 100 100" class="w-32 h-32">
                    </svg>
                </div>
            </div>
        </section>

        {{-- WHY BECOME A PARTNER --}}
        <section class="bg-[var(--sand)] py-20 md:py-28">
            <div class="max-w-6xl mx-auto px-6 md:px-8">
                <h2 class="font-display text-3xl md:text-4xl text-[var(--ink)] text-center mb-16">
                    De ce să devii partener
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    <div class="bg-[var(--paper)] rounded-2xl p-8">
                        <svg class="w-8 h-8 mb-6 text-[var(--magenta)]" viewBox="0 0 24 24" fill="none">
                            <circle cx="9" cy="8" r="3" stroke="currentColor" stroke-width="1.5" />
                            <path d="M3.5 19c0-3 2.5-5 5.5-5s5.5 2 5.5 5" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" />
                            <circle cx="17" cy="7" r="2.4" stroke="currentColor" stroke-width="1.5" />
                            <path d="M14.8 12.3c2.3.3 4.2 2.1 4.2 4.7" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" />
                        </svg>
                        <h3 class="font-display text-xl text-[var(--ink)] mb-2">Trafic nou</h3>
                        <p class="text-[var(--ink)]/65 text-sm leading-relaxed">
                            Acces la o comunitate activă de mii de studenți internaționali și locali.
                        </p>
                    </div>

                    <div class="bg-[var(--paper)] rounded-2xl p-8">
                        <svg class="w-8 h-8 mb-6 text-[var(--magenta)]" viewBox="0 0 24 24" fill="none">
                            <path d="M3 10v4a1 1 0 001 1h2l6 4V5L6 9H4a1 1 0 00-1 1z" stroke="currentColor"
                                stroke-width="1.5" stroke-linejoin="round" />
                            <path d="M17 9c1 1 1 5 0 6M19.5 7c2 2.2 2 7.6 0 10" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" />
                        </svg>
                        <h3 class="font-display text-xl text-[var(--ink)] mb-2">Vizibilitate</h3>
                        <p class="text-[var(--ink)]/65 text-sm leading-relaxed">
                            Promovare gratuită pe canalele ESN Romania și ESN local.
                        </p>
                    </div>

                    <div class="bg-[var(--paper)] rounded-2xl p-8">
                        <svg class="w-8 h-8 mb-6 text-[var(--magenta)]" viewBox="0 0 24 24" fill="none">
                            <rect x="4" y="4" width="7" height="7" rx="1.5" stroke="currentColor"
                                stroke-width="1.5" />
                            <rect x="13" y="4" width="7" height="7" rx="1.5" stroke="currentColor"
                                stroke-width="1.5" />
                            <rect x="4" y="13" width="7" height="7" rx="1.5" stroke="currentColor"
                                stroke-width="1.5" />
                            <path d="M16.5 16.5h4M18.5 14.5v4" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" />
                        </svg>
                        <h3 class="font-display text-xl text-[var(--ink)] mb-2">Simplu de folosit</h3>
                        <p class="text-[var(--ink)]/65 text-sm leading-relaxed">
                            Sistem digital, fără costuri de implementare sau echipamente suplimentare.
                        </p>
                    </div>

                </div>
            </div>
        </section>

        {{-- HOW TO BECOME A PARTNER — a real sequence, so numbering earns its place --}}
        <section class="py-20 md:py-28">
            <div class="max-w-6xl mx-auto px-6 md:px-8">
                <h2 class="font-display text-3xl md:text-4xl text-[var(--ink)] text-center mb-20">
                    Cum devii partener
                </h2>

                <div class="relative">
                    <div class="hidden md:block absolute top-10 left-0 right-0 h-px bg-[var(--ink)]/10"></div>

                    <div class="relative grid grid-cols-1 md:grid-cols-3 gap-14 text-center">
                        @foreach ([['1', 'Ne contactezi', 'Trimiți o cerere de parteneriat prin formularul de mai jos.'], ['2', 'Stabilim oferta', 'Împreună definim discountul sau beneficiul oferit studenților.'], ['3', 'Ești live', 'Primești acces la sistemul de scanare QR și apari în aplicație.']] as [$number, $title, $desc])
                            <div>
                                <div
                                    class="relative z-10 mx-auto mb-6 w-20 h-20 rounded-full bg-[var(--paper)] border border-[var(--ink)]/15 flex items-center justify-center">
                                    <span
                                        class="font-display italic text-2xl text-[var(--magenta)]">{{ $number }}</span>
                                </div>
                                <h3 class="font-display text-lg text-[var(--ink)] mb-2">{{ $title }}</h3>
                                <p class="text-[var(--ink)]/65 text-sm leading-relaxed max-w-[16rem] mx-auto">
                                    {{ $desc }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

    </div>

    <x-site-footer />

</x-app-layout>
