<x-guest-layout>

    <div class="flex items-center justify-center mt-2">
        <a href="/">
            <img src="{{ asset('images/archer-logo.png') }}" style="width: 450px;  height: auto;"  alt="Logo"> 
        </a>
    </div>
    <h2 class="text-[#292727]  text-2xl text-center mt-3">RESET PASSWORD</h2>
    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="relative w-full mt-4">
            <i class="fa-solid fa-user absolute left-2 top-1/2 -translate-y-1/2 text-[#292727] text-base"></i>
            <input
                type="password"
                name="password"
                placeholder="Password"
                class="pl-10 border-b-2 border-[#2B3991] w-full pt-2 pb-1 text-base focus:outline-none focus:border-b-[#B6844A]"
            >
            @error('password')
                <p class="text-red-600 mt-2">{{ $message }}</p>
            @enderror
        </div>

        
        <div class="relative w-full mt-4">
            <i class="fa-solid fa-lock absolute left-2 top-1/2 -translate-y-1/2 text-[#292727] text-base"></i>
            <input
                type="password"
                name="password_confirmation"
                placeholder="Confirm Password"
                class="pl-10 border-b-2 border-[#2B3991] w-full pt-2 pb-1 text-base focus:outline-none focus:border-b-[#B6844A]"
            >
            @error('password_confirmation')
                <p class="text-red-600 mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-center flex-wrap mt-4">
            <button type="submit" class="bg-[#fff] text-gray-500  py-2 px-8 rounded-full cursor-pointer border border-[#B6844A] hover:bg-[#B6844A] hover:border-[#B6844A] hover:text-[#fff] transition-all duration-200 text-base mb-6">
                Submit
            </button>

        </div>
      
    </form>
</x-guest-layout>
