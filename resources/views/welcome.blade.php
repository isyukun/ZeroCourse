<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ZeroCourse - Belajar Tanpa Batas</title>
    {{-- Menggunakan Tailwind via Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Font Premium --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-white text-gray-900 antialiased selection:bg-blue-100 selection:text-blue-600">
    
    {{-- ==========================================
         BAGIAN 1: NAVIGATION BAR
         ========================================== --}}
    <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-100">
        <div class="flex justify-between items-center p-5 max-w-7xl mx-auto">
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-200">
                    <span class="text-white font-black text-xl">Z</span>
                </div>
                <h1 class="text-2xl font-black tracking-tight text-gray-900">Zero<span class="text-blue-600">Course</span></h1>
            </div>
            
            <div class="flex items-center gap-6">
                @auth
                    <a href="{{ url('/dashboard') }}" class="px-6 py-2.5 bg-gray-900 text-white rounded-xl text-sm font-bold hover:bg-gray-800 transition shadow-md">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-bold text-gray-600 hover:text-blue-600 transition">Masuk</a>
                    <a href="{{ route('register') }}" class="bg-blue-600 text-white px-6 py-2.5 rounded-xl text-sm font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-100">Daftar Sekarang</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- ==========================================
         BAGIAN 2: HERO SECTION (UTAMA)
         ========================================== --}}
    <main>
        <section class="relative overflow-hidden pt-20 pb-16 lg:pt-32">
            <div class="max-w-7xl mx-auto px-6 text-center relative z-10">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-50 text-blue-700 text-xs font-bold mb-6 border border-blue-100 animate-bounce">
                    🚀 Platform LMS Masa Depan
                </div>
                <h2 class="text-5xl lg:text-7xl font-black tracking-tight text-gray-900 mb-6 leading-tight">
                    Kuasai Skill Digital <br> 
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Mulai dari Nol.</span>
                </h2>
                <p class="text-xl text-gray-500 mb-10 max-w-2xl mx-auto leading-relaxed">
                    ZeroCourse menyediakan kurikulum terstruktur untuk membantu kamu menguasai teknologi terkini dengan langkah yang mudah, efisien, dan bersertifikat.
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('register') }}" class="bg-blue-600 text-white px-10 py-5 rounded-2xl text-lg font-black shadow-xl shadow-blue-200 hover:bg-blue-700 hover:-translate-y-1 transition-all">
                        Mulai Belajar Gratis
                    </a>
                    <a href="#features" class="bg-white text-gray-700 border border-gray-200 px-10 py-5 rounded-2xl text-lg font-black hover:bg-gray-50 transition-all">
                        Lihat Kurikulum
                    </a>
                </div>
            </div>
            
            {{-- Background Decor --}}
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full -z-0 opacity-10 pointer-events-none">
                <div class="absolute top-20 left-10 w-72 h-72 bg-blue-400 rounded-full blur-[120px]"></div>
                <div class="absolute bottom-10 right-10 w-72 h-72 bg-indigo-400 rounded-full blur-[120px]"></div>
            </div>
        </section>

        {{-- ==========================================
             BAGIAN 3: FEATURE CARDS
             ========================================== --}}
        <section id="features" class="max-w-7xl mx-auto px-6 py-24">
            <div class="grid md:grid-cols-3 gap-8">
                <div class="group bg-white p-8 rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300">
                    <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-2xl mb-6 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        📚
                    </div>
                    <h3 class="font-black text-xl mb-3 text-gray-900">Kurikulum Terstruktur</h3>
                    <p class="text-gray-500 leading-relaxed text-sm">Materi disusun secara berurutan dari dasar hingga mahir agar alur belajarmu tetap jelas.</p>
                </div>

                <div class="group bg-white p-8 rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300">
                    <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-2xl mb-6 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                        📊
                    </div>
                    <h3 class="font-black text-xl mb-3 text-gray-900">Progress Tracking</h3>
                    <p class="text-gray-500 leading-relaxed text-sm">Pantau perkembangan belajarmu dengan fitur persentase otomatis di setiap kursus.</p>
                </div>

                <div class="group bg-white p-8 rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300">
                    <div class="w-14 h-14 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center text-2xl mb-6 group-hover:bg-purple-600 group-hover:text-white transition-colors">
                        🎓
                    </div>
                    <h3 class="font-black text-xl mb-3 text-gray-900">Sertifikat Digital</h3>
                    <p class="text-gray-500 leading-relaxed text-sm">Dapatkan pengakuan atas keahlianmu setelah menyelesaikan seluruh materi dan ujian.</p>
                </div>
            </div>
        </section>

        {{-- ==========================================
             BAGIAN 4: FOOTER
             ========================================== --}}
        <footer class="bg-gray-50 border-t border-gray-100 py-12">
            <div class="max-w-7xl mx-auto px-6 text-center">
                <p class="text-gray-400 text-sm font-medium">
                    &copy; 2026 ZeroCourse LMS.
                    {{-- Dibuat untuk Tugas Akhir Skripsi. --}}
                </p>
            </div>
        </footer>
    </main>

</body>
</html>