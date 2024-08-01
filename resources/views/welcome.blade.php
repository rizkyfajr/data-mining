<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Prediksi Kinerja Operasional</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-100 font-['Poppins']">

    <header class="bg-gradient-to-r from-blue-500 to-blue-600 py-6">
        <div class="container mx-auto flex justify-between items-center px-6">
            <a href="/" class="flex items-center">
                <img src="{{ asset('assets/logo.png') }}" alt="Logo" style="width: 100px; height: 100px;">
            </a>
            <div class="flex items-center">
                <svg class="h-12 w-auto text-white" viewBox="0 0 62 65" fill="none" xmlns="http://www.w3.org/2000/svg">
                    </svg>
                <h1 class="ml-4 text-4xl font-bold text-white">Prediksi Kinerja Operasional</h1>
            </div>

            @if (Route::has('login'))
                <nav>
                    @auth
                        <a href="{{ url('/dashboard') }}" class="bg-white hover:bg-gray-100 text-blue-500 font-bold py-2 px-4 rounded">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="bg-white hover:bg-gray-100 text-blue-500 font-bold py-2 px-4 rounded mr-4">
                            Log in
                        </a>

                    @endauth
                </nav>
            @endif
        </div>
    </header>

    <main class="container mx-auto py-12">
        <section class="flex flex-col-reverse lg:flex-row items-center justify-between">

            <div class="lg:w-1/2 px-6">
                <h2 class="text-3xl font-bold mb-4">Tingkatkan Efisiensi dan Hasil</h2>
                <p class="text-gray-700 mb-8">
                    Data mining adalah kunci untuk mengungkap potensi tersembunyi tim. Prediksi kinerja pekerja membantu Tim membuat keputusan yang lebih baik, meningkatkan efisiensi operasional, dan mencapai hasil yang lebih baik.
                </p>
                <a href="#" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                    Mulai Sekarang
                </a>
            </div>

            <div class="lg:w-1/2 mb-8 lg:mb-0">
                <img src="{{ asset('assets/Wavy_Tech-01_Single-02.jpg') }}" alt="Data Mining Illustration" class="rounded-lg shadow-lg" width="550" height="550">
            </div>
            </div>
        </section>
    </main>

</body>
</html>
