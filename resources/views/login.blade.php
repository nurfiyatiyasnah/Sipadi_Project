<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Arsip Digital</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-white flex flex-col min-h-screen">
    
    <!-- Main Content -->
    <main class="flex-grow flex items-center justify-center">
        <div class="w-full max-w-sm px-6">
            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf
                
                <!-- Input Email/Username -->
                <div class="relative">
                    <input type="text" name="email" placeholder="Email or Username" 
                        class="w-full px-4 py-3 border border-[#E9A150] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#E9A150] transition text-sm italic text-gray-600">
                </div>

                <!-- Input Password -->
                <div class="relative">
                    <input type="password" name="password" placeholder="Password" 
                        class="w-full px-4 py-3 border border-[#E9A150] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#E9A150] transition text-sm italic text-gray-600">
                    <!-- Icon Mata (Mockup) -->
                    <div class="absolute inset-y-0 right-4 flex items-center text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.644C3.414 8.65 7.378 5.625 12 5.625c4.622 0 8.586 3.025 9.964 6.053a1.012 1.012 0 010 .644C20.586 15.35 16.622 18.375 12 18.375c-4.622 0-8.586-3.025-9.964-6.053z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                </div>

                <!-- Button Sign In -->
                <div class="pt-4">
                    <button type="submit" 
                        class="w-full bg-[#E9A150] hover:bg-[#d88f3e] text-white font-medium py-3 rounded-xl shadow-md transition-all duration-200 text-sm">
                        Sign In
                    </button>
                </div>

                <!-- Footer Link -->
                <p class="text-center text-[10px] text-black">
                    Don't have an account? <a href="{{ route('register') }}" class="text-[#E9A150] font-bold hover:underline">Sign Up</a>
                </p>
            </form>
        </div>
    </main>

</body>
</html>