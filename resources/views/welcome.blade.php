<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ZeroCourse - Belajar Tanpa Batas</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900 antialiased">
    
    <nav class="flex justify-between items-center p-6 max-w-7xl mx-auto">
        <h1 class="text-2xl font-bold text-blue-600">ZeroCourse</h1>
        <div>
            @auth
                <a href="{{ url('/dashboard') }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900 mr-4">Log in</a>
                <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700">Register</a>
            @endauth
        </div>
    </nav>

    <section class="max-w-7xl mx-auto px-6 py-16 text-center">
        <h2 class="text-5xl font-extrabold tracking-tight text-gray-900 mb-4">
            Mulai Belajar Skill Baru <br> <span class="text-blue-600">Mulai dari Nol.</span>
        </h2>
        <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
            ZeroCourse menyediakan kurikulum terstruktur untuk membantu kamu menguasai teknologi terkini dengan langkah yang mudah dan efisien.
        </p>
        <a href="{{ route('register') }}" class="bg-blue-600 text-white px-8 py-4 rounded-xl text-lg font-bold shadow-lg hover:bg-blue-700">
            Mulai Belajar Sekarang
        </a>
    </section>

    <section class="max-w-7xl mx-auto px-6 py-12 grid md:grid-cols-3 gap-8">
        <div class="bg-white p-6 rounded-xl shadow-sm">
            <h3 class="font-bold text-xl mb-2">Terstruktur</h3>
            <p class="text-gray-600">Materi disusun secara berurutan agar kamu tidak bingung saat memulai belajar.</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm">
            <h3 class="font-bold text-xl mb-2">Progress Belajar</h3>
            <p class="text-gray-600">Pantau perkembangan belajarmu dengan fitur pelacakan materi otomatis.</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm">
            <h3 class="font-bold text-xl mb-2">Akses Instan</h3>
            <p class="text-gray-600">Mulai belajar kapan saja dan di mana saja setelah kamu terdaftar.</p>
        </div>
    </section>

</body>
</html>