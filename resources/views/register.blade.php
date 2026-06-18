<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Arsip Digital</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-white flex flex-col min-h-screen">

    <main class="flex-grow flex items-center justify-center py-10">
        <div class="w-full max-w-md px-6">
            <h2 class="text-center text-4xl font-bold mb-10 text-black">Sign Up</h2>

            <form action="{{ route('register') }}" method="POST" class="space-y-4">
                @csrf
                
                <div class="relative">
                    <input type="text" name="name" placeholder="Full Name" required
                        class="w-full px-4 py-2.5 border border-[#E9A150] rounded-xl focus:outline-none focus:ring-1 focus:ring-[#E9A150] transition text-sm italic text-gray-600">
                </div>

                <div class="relative">
                    <input type="email" name="email" placeholder="Email Address" required
                        class="w-full px-4 py-2.5 border border-[#E9A150] rounded-xl focus:outline-none focus:ring-1 focus:ring-[#E9A150] transition text-sm italic text-gray-600">
                </div>

                <div class="relative">
                    <input type="text" name="phone" placeholder="Phone Number" required
                        class="w-full px-4 py-2.5 border border-[#E9A150] rounded-xl focus:outline-none focus:ring-1 focus:ring-[#E9A150] transition text-sm italic text-gray-600">
                </div>

                <div class="relative">
                    <input type="password" name="password" placeholder="Password" required
                        class="w-full px-4 py-2.5 border border-[#E9A150] rounded-xl focus:outline-none focus:ring-1 focus:ring-[#E9A150] transition text-sm italic text-gray-600">
                    <div class="absolute inset-y-0 right-4 flex items-center text-black">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.644C3.414 8.65 7.378 5.625 12 5.625c4.622 0 8.586 3.025 9.964 6.053a1.012 1.012 0 010 .644C20.586 15.35 16.622 18.375 12 18.375c-4.622 0-8.586-3.025-9.964-6.053z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                </div>

                <div class="relative">
                    <input type="password" name="password_confirmation" placeholder="Confirm Password" required
                        class="w-full px-4 py-2.5 border border-[#E9A150] rounded-xl focus:outline-none focus:ring-1 focus:ring-[#E9A150] transition text-sm italic text-gray-600">
                    <div class="absolute inset-y-0 right-4 flex items-center text-black">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.644C3.414 8.65 7.378 5.625 12 5.625c4.622 0 8.586 3.025 9.964 6.053a1.012 1.012 0 010 .644C20.586 15.35 16.622 18.375 12 18.375c-4.622 0-8.586-3.025-9.964-6.053z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                </div>

                <div class="pt-6 flex justify-center">
                    <button type="submit" 
                        class="px-12 bg-[#E9A150] hover:bg-[#d88f3e] text-white font-medium py-2 rounded-xl shadow-md transition text-sm">
                        Sign Up
                    </button>
                </div>

                <p class="text-center text-[11px] text-black pt-2">
                    Already have account? <a href="{{ route('login') }}" class="text-[#E9A150] font-bold hover:underline">Sign In</a>
                </p>
            </form>
        </div>
    </main>

</body>

</html>

