<footer class="max-w-4xl mx-auto flex items-center justify-center mt-10 border-t border-gray-100 py-4 text-center">
    <a href="{{ route('about') }}" class="text-sm text-black hover:underline px-3">
        About us
    </a>
    <a href="{{ url('/terms-and-conditions') }}" class="text-sm text-black hover:underline px-3">
        Terms & Conditions
    </a>
    @guest
        <a href="{{ route('login') }}" class="text-sm text-black hover:underline px-3">
            Login
        </a>
        <a href="{{ route('register.show') }}" class="text-sm text-black hover:underline px-3">
            Register
        </a>
    @endguest
</footer>

<div class="flex h-4 w-full">
    <div class="flex-1 bg-[#2e3192]"></div>
    <div class="flex-1 bg-[#ec008c]"></div>
    <div class="flex-1 bg-[#7ac143]"></div>
</div>

<p class="text-sm text-gray-600 text-center py-4">
    © {{ date('Y') }} ESN Romania · All rights reserved
</p>
