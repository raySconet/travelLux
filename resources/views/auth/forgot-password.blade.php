<x-guest-layout>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="flex items-center justify-center mt-2">
        <a href="/">
            <img src="{{ asset('images/archer-logo.png') }}" style="width:250px" alt="Logo">
        </a>
    </div>

    <h2 class="text-[#2B3991] text-2xl text-center mt-3">
        FORGOT PASSWORD
    </h2>

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="relative w-full mt-4">
            <i class="fa-solid fa-user absolute left-2 top-1/2 -translate-y-1/2 text-[#2B3991]"></i>

            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="Email"
                class="pl-10 border-b-2 border-[#2B3991] w-full pt-2 pb-1 focus:outline-none"
            >
        </div>

        @error('email')
            <p class="text-red-600 mt-2">{{ $message }}</p>
        @enderror

        <div class="flex justify-center mt-6">
            <button type="submit"
                class="bg-white border border-[#B6844A] rounded-full px-8 py-2 hover:bg-[#B6844A] hover:text-white">
                Send
            </button>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('login') }}">
                Back to login
            </a>
        </div>

    </form>

</x-guest-layout>