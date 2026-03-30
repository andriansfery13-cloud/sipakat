<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Login - SIPAKAT (Sistem Informasi Pajak & Keuangan Terpadu)</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            @keyframes float {
                0% { transform: translateY(0px); box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); }
                50% { transform: translateY(-10px); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); }
                100% { transform: translateY(0px); box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); }
            }
            .animate-float {
                animation: float 6s ease-in-out infinite;
            }
            @keyframes fadeInScale {
                0% { opacity: 0; transform: scale(0.95) translateY(20px); }
                100% { opacity: 1; transform: scale(1) translateY(0); }
            }
            .animate-fade-in-scale {
                animation: fadeInScale 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            }
            @keyframes slowZoom {
                0% { transform: scale(1.05); }
                100% { transform: scale(1.15); }
            }
            .animate-slow-zoom {
                animation: slowZoom 20s linear infinite alternate;
            }
        </style>
    </head>
    <body class="font-sans text-slate-900 antialiased min-h-screen relative overflow-hidden" style="font-family: 'Plus Jakarta Sans', sans-serif;">
        <!-- Dynamic Backgrounds -->
        <div id="bg-slider" class="absolute inset-0 bg-cover bg-center transition-all duration-1000 z-0 ease-in-out animate-slow-zoom"></div>
        <div id="bg-slider-next" class="absolute inset-0 bg-cover bg-center transition-opacity duration-1000 opacity-0 z-0 ease-in-out animate-slow-zoom" style="animation-direction: alternate-reverse;"></div>
        
        <!-- Dark Overlay -->
        <div class="absolute inset-0 bg-emerald-950/40 z-0 backdrop-blur-[2px]"></div>

        <div class="relative z-10 flex min-h-screen items-center justify-center p-4 sm:p-6 lg:p-8">
            <div class="w-full max-w-md bg-white/85 backdrop-blur-2xl px-6 sm:px-10 py-10 shadow-2xl border border-white/50 rounded-3xl animate-fade-in-scale filter drop-shadow-2xl">
                
                <div class="flex flex-col items-center justify-center mb-8">
                    <!-- User Logo -->
                    <div class="w-24 h-24 mb-6 flex items-center justify-center bg-white rounded-2xl shadow-lg border border-slate-100 p-3 animate-float overflow-hidden">
                        <img id="login-logo" src="{{ asset('img/logo.png') }}" alt="SIPAKAT Logo" class="w-full h-full object-contain" onerror="this.src='https://ui-avatars.com/api/?name=SP&color=059669&background=ECFDF5&size=128&font-size=0.4&bold=true'">
                    </div>
                    
                    <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight lg:text-4xl text-center">
                        SIPAKAT
                    </h1>
                    <p class="text-[13px] font-bold text-emerald-700 mt-2 mb-2 text-center uppercase tracking-wider">
                        Sistem Informasi Pajak & Keuangan Terpadu
                    </p>
                    <p class="text-xs text-slate-500 font-medium text-center">
                        Silakan masuk menggunakan kredensial Anda.
                    </p>
                </div>

                <div class="w-full">
                    {{ $slot }}
                </div>
                
                <div class="mt-8 text-center text-xs text-slate-500 font-medium">
                    &copy; {{ date('Y') }} Coretax SIPAKAT. All rights reserved.
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // High Quality Unsplash Nature/Emerald Images
                const bgImages = [
                    'url("https://images.unsplash.com/photo-1542273917363-3b1817f69a2d?q=80&w=2560&auto=format&fit=crop")', // Forest path lush green
                    'url("https://images.unsplash.com/photo-1472214103451-9374bd1c798e?q=80&w=2560&auto=format&fit=crop")', // Green rolling hills
                    'url("https://images.unsplash.com/photo-1441974231531-c6227db76b6e?q=80&w=2560&auto=format&fit=crop")', // Deep forest view
                    'url("https://images.unsplash.com/photo-1518531933037-cc1bb1ab9be3?q=80&w=2560&auto=format&fit=crop")'  // Lush tropical leaves
                ];
                
                let currentIndex = 0;
                const bgElement = document.getElementById('bg-slider');
                const nextBgElement = document.getElementById('bg-slider-next');
                
                // Set first background
                bgElement.style.backgroundImage = bgImages[0];
                
                // Preload images
                bgImages.slice(1).forEach(url => {
                    const img = new Image();
                    img.src = url.replace(/url\(['"]?(.*?)['"]?\)/i, '$1');
                });
                
                setInterval(() => {
                    const nextIndex = (currentIndex + 1) % bgImages.length;
                    
                    nextBgElement.style.backgroundImage = bgImages[nextIndex];
                    nextBgElement.style.opacity = '1';
                    
                    setTimeout(() => {
                        bgElement.style.backgroundImage = bgImages[nextIndex];
                        nextBgElement.style.opacity = '0';
                        currentIndex = nextIndex;
                    }, 1000); 
                    
                }, 6000); // Change background every 6 seconds
            });
        </script>
    </body>
</html>
