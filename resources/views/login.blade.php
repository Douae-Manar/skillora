<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.13.0/dist/cdn.min.js"></script>
    
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    
    <title>Skillora - Workspace</title>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .brand-title {
            font-family: 'Playfair Display', serif;
        }
        .glass-panel {
            background: rgba(237, 228, 215, 0.25);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }
        .glass-input {
            background: rgba(255, 255, 255, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }
        .glass-input:focus {
            background: rgba(255, 255, 255, 0.7);
            border-color: #56161D;
            box-shadow: 0 0 0 4px rgba(86, 22, 29, 0.15);
        }
    </style>
</head>
<body class="relative min-h-screen flex items-center justify-center p-4 md:p-8 m-0 overflow-x-hidden">

    <!-- الصورة ديال الخلفية -->
    <div class="fixed inset-0 bg-cover bg-center bg-no-repeat z-[-2]" 
         style="background-image: url('{{ asset('images/background.jpg') }}');">
    </div>
    
    <!-- الطبقة اللي كتعطي التأثير (Overlay) -->
    <div class="fixed inset-0 bg-[#EBE3D7]/70 z-[-1]"></div>

    <!-- الحاوية الرئيسية -->
    <div class="glass-panel w-full max-w-5xl rounded-[40px] shadow-2xl overflow-hidden p-6 md:p-12 relative z-10" x-data="{ show: false, mode: 'login' }">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
            
            <div class="lg:col-span-6 space-y-6 text-center lg:text-start px-4">
                <div class="inline-flex items-center gap-3 bg-[#56161D] text-[#E6DFD3] px-6 py-2.5 rounded-full text-xs font-semibold tracking-wider shadow-sm">
                    <i class="fa-solid fa-house-chimney animate-pulse"></i>
                    <span>SKILLORA WORKSPACE</span>
                </div>
                
                <div class="space-y-2">
                    <h1 class="brand-title text-5xl md:text-6xl font-bold text-[#2D1A12]">Skillora</h1>
                    <p class="brand-title italic text-[#56161D] text-xl md:text-2xl font-medium">
                        Discover the poetry of data in the tech labor market.
                    </p>
                </div>

                <p class="text-[#2D1A12]/80 text-sm md:text-base leading-relaxed max-w-md mx-auto lg:mx-0">
                    A tailored, elegant intelligence platform designed to track job offer occurrences, explore rich skills catalogs, and decode real-time market demands with minimal, curated telemetry.
                </p>

                <div class="flex gap-4 justify-center lg:justify-start pt-4">
                    <div class="w-14 h-14 rounded-full bg-[#56161D] text-[#E6DFD3] flex items-center justify-center text-xl shadow-md hover:scale-110 transition-transform">
                        <i class="fa-solid fa-briefcase"></i>
                    </div>
                    <div class="w-14 h-14 rounded-full bg-[#2D1A12] text-[#E6DFD3] flex items-center justify-center text-xl shadow-md hover:scale-110 transition-transform">
                        <i class="fa-solid fa-brain"></i>
                    </div>
                    <div class="w-14 h-14 rounded-full bg-[#A38A75] text-[#FFF] flex items-center justify-center text-xl shadow-md hover:scale-110 transition-transform">
                        <i class="fa-solid fa-chart-line"></i>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-6 w-full max-w-md mx-auto">
                <div class="bg-white/40 backdrop-blur-md border border-white/40 rounded-3xl p-8 shadow-xl relative">
                    
                    @if(session('error'))
                        <div class="bg-red-500/20 text-red-900 border border-red-500/30 text-xs p-3 rounded-xl mb-4 text-center font-medium">
                            <i class="fa-solid fa-triangle-exclamation mr-1"></i> {{ session('error') }}
                        </div>
                    @endif
                    @if(session('success'))
                        <div class="bg-green-500/20 text-green-900 border border-green-500/30 text-xs p-3 rounded-xl mb-4 text-center font-medium">
                            <i class="fa-solid fa-circle-check mr-1"></i> {{ session('success') }}
                        </div>
                    @endif

                    <div class="mb-6 text-center lg:text-start">
                        <h3 class="text-2xl font-bold text-[#2D1A12]" x-text="mode == 'login' ? 'Sign In' : 'Create Account'"></h3>
                        <p class="text-xs text-[#2D1A12]/70 mt-1" x-text="mode == 'login' ? 'Enter credentials to view your workspace' : 'Join Skillora to analyze market telemetry'"></p>
                    </div>

                    <form :action="mode == 'login' ? '/login' : '/register'" method="POST" class="space-y-4">
                        @csrf
                        
                        <template x-if="mode == 'register'">
                            <div class="space-y-1">
                                <label class="text-[11px] font-bold tracking-wider text-[#4A3E39] uppercase">Full Name</label>
                                <input type="text" name="name" placeholder="John Doe" required class="w-full p-3.5 rounded-xl glass-input text-sm text-[#2D1A12] focus:outline-none">
                            </div>
                        </template>

                        <div class="space-y-1">
                            <label class="text-[11px] font-bold tracking-wider text-[#4A3E39] uppercase">Email Address</label>
                            <input type="email" name="email" placeholder="yourname@example.com" required class="w-full p-3.5 rounded-xl glass-input text-sm text-[#2D1A12] focus:outline-none">
                        </div>

                        <div class="space-y-1">
                            <label class="text-[11px] font-bold tracking-wider text-[#4A3E39] uppercase">Password</label>
                            <div class="relative">
                                <input :type="show ? 'text' : 'password'" name="password" placeholder="••••••••" required class="w-full p-3.5 pr-14 rounded-xl glass-input text-sm text-[#2D1A12] focus:outline-none">
                                <button type="button" @click="show = !show" class="absolute right-4 top-4 text-[10px] font-bold tracking-wider text-[#56161D] opacity-70 hover:opacity-100 uppercase transition-opacity">
                                    <span x-text="show ? 'Hide' : 'Show'"></span>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-[#56161D] hover:bg-[#421015] text-white p-3.5 rounded-xl font-semibold text-sm shadow-md hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center gap-2 mt-2 cursor-pointer">
                            <span x-text="mode == 'login' ? 'Access Dashboard' : 'Register & Explore'"></span>
                            <i :class="mode == 'login' ? 'fa-solid fa-arrow-right-long' : 'fa-solid fa-user-plus'"></i>
                        </button>
                    </form>

                    <div class="mt-6 text-center text-xs text-[#2D1A12]/80">
                        <span x-text="mode == 'login' ? 'Don\'t have an account?' : 'Already have an account?'"></span>
                        <span @click="mode = (mode == 'login' ? 'register' : 'login')" class="text-[#56161D] font-bold underline cursor-pointer ml-1 hover:text-[#421015] transition-colors" x-text="mode == 'login' ? 'Create Account' : 'Sign In'"></span>
                    </div>

                </div>
            </div>
        </div>
    </div>
</body>
</html>