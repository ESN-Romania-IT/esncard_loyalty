<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Welcome!') }}
        </h2>
    </x-slot>

    <div>
        @if (session('status'))
            <div class="alert alert-info">
                {{ session('status') }}
            </div>
        @endif
        <h1>Try this link:</h1>
        <a href="/register"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Register
            to Cafe Bar</a>
        <a href="/login" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Login (test)</a>
        <p>Use any account and number you want (mock auth for now)</p>
        <p>Then, try the /me, /me/stats, /me/settings, /about and /info/terms pages</p>


    </div>
</x-app-layout>
