<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <div class="flex items-center justify-center mt-2">
        <a href="/">
            <img src="{{ asset('images/archer-logo.png') }}" style="width: 250px;  height: auto;"  alt="Logo"> 
        </a>
    </div>
    <h2 class="text-[#292727]  text-2xl text-center mt-3">LOG IN</h2>
    <form method="POST" action="{{ route('login') }}">
        @csrf


        <div class="relative w-full mt-4">
            <i class="fa-solid fa-user absolute left-2 top-1/2 -translate-y-1/2 text-[#292727] text-base"></i>
            <input
                id="email"
                type="email"
                name="email"
                :value="old('email')"
                placeholder="Email"
                class="pl-10 border-b-2 border-[#292727] w-full pt-2 pb-1 text-base focus:outline-none focus:border-b-[#B6844A]"
            />
        </div>

        
        <div class="relative w-full mt-4">
            <i class="fa-solid fa-lock absolute left-2 top-1/2 -translate-y-1/2 text-[#292727] text-base"></i>
            <input
                id="password"
                type="password"
                name="password"
                placeholder="Password"
                class="pl-10 border-b-2 border-[#2B3991] w-full pt-2 pb-1 text-base focus:outline-none focus:border-b-[#B6844A]"
                autocomplete="current-password"
            />
        </div>

        <div class="flex items-center justify-center flex-wrap mt-4">
            <div class="flex flex-col justify-center content-center">
                <button class="bg-[#fff] text-gray-500  py-2 px-8 rounded-full cursor-pointer border border-[#B6844A]
                                hover:bg-[#B6844A] hover:border-[#B6844A] hover:text-[#fff]
                                transition-all duration-200 text-base mb-6">
                    Login
                </button>
                @if (Route::has('password.request'))
                    <a class="text-xl text-[#292727] mb-5" href="{{ route('password.request') }}">
                        Forgot password?
                    </a>
                @endif

            </div>
        </div>
    </form>
</x-guest-layout>
