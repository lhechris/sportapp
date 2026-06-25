<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>AS Labarthaise Basket</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-yellow-200 text-gray-900">

    <!-- NAVBAR -->
    <nav class="px-6 py-4 flex justify-between items-center shadow">
        <h1 class="text-lg font-bold">{{ __('🏀 Welcome to AS Labarthaise Basket') }}</h1>

        <div class="space-x-4">
            @auth
                <a href="/dashboard"
                   class="bg-black text-yellow-400 px-4 py-2 rounded-lg font-semibold">
                    {{ __('Dashboard') }}
                </a>
            @else
                <a href="/login" class="hover:underline">{{ __('Log in') }}</a>
                <a href="/register"
                   class="bg-white text-yellow-400 px-4 py-2 rounded-lg font-semibold">
                    {{ __('Register') }}
                </a>
            @endauth
        </div>
    </nav>

    <!-- HERO -->
    <section class="relative text-white py-24 text-center bg-yellow-200 overflow-hidden">

        <img src="{{ asset('images/baniere.jpg') }}" class="absolute inset-0 w-full h-full object-cover">
        <!-- Effet overlay -->
        <div class="absolute inset-0 bg-black/40"></div>

        <div class="relative z-10 max-w-3xl mx-auto">
            <h2 class="text-4xl md:text-5xl font-bold mb-6">
                {{ __('🏀 Welcome to AS Labarthaise Basket') }}
            </h2>

            <p class="text-lg mb-8">
                {{ __('Manage your teams, players and matches with simplicity and performance.') }}
            </p>

            @guest
                <a href="/register"
                   class="bg-white text-yellow-400 px-8 py-3 rounded-xl text-lg font-bold shadow hover:scale-105 transition">
                    {{ __('Get started') }}
                </a>
            @endguest
        </div>

    </section>

    <!-- FEATURES -->
    <section class="py-16 px-6 max-w-6xl mx-auto">

        <div class="grid md:grid-cols-3 gap-8 text-center">

            <div class="p-6 rounded-2xl shadow hover:shadow-lg transition bg-yellow-400">
                <h3 class="font-bold text-lg mb-3">{{ __('🏆 Teams') }}</h3>
                <p class="text-gray-500">
                    {{ __('Organize your basketball teams in a few clicks.') }}
                </p>
            </div>

            <div class="p-6 rounded-2xl shadow hover:shadow-lg transition bg-yellow-400">
                <h3 class="font-bold text-lg mb-3">{{ __('👥 Team numbers') }}</h3>
                <p class="text-gray-500">
                    {{ __('Manage players, coaches and staff easily.') }}
                </p>
            </div>

            <div class="p-6 rounded-2xl shadow hover:shadow-lg transition bg-yellow-400">
                <h3 class="font-bold text-lg mb-3">{{ __('📅 Matches') }}</h3>
                <p class="text-gray-500">
                    {{ __('Schedule matches and trainings efficiently.') }}
                </p>
            </div>

        </div>

    </section>

    <!-- CTA FINAL -->

    <section class="text-gray-900 py-16 text-center bg-yellow-400">
        <h3 class="text-2xl font-bold mb-6">
            {{ __('Join your team now 🏀') }}
        </h3>

        @guest
            <a href="/register"
               class="bg-white text-red-700 px-6 py-3 rounded-xl font-bold shadow">
                {{ __('Create an account') }}
            </a>
        @endguest
    </section>

    <!-- FOOTER -->
    <footer class="bg-black text-gray-400 text-center py-6">
        <p>© {{ date('Y') }} AS Labarthaise Basket</p>
    </footer>

</body>
</html>
