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
        <h1 class="text-lg font-bold">🏀 AS Labarthaise Basket</h1>

        <div class="space-x-4">
            @auth
                <a href="/dashboard"
                   class="bg-black text-yellow-400 px-4 py-2 rounded-lg font-semibold">
                    Dashboard
                </a>
            @else
                <a href="/login" class="hover:underline">Connexion</a>
                <a href="/register"
                   class="bg-white text-yellow-400 px-4 py-2 rounded-lg font-semibold">
                    Inscription
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
                🏀 Bienvenue à l'AS Labarthaise Basket
            </h2>

            <p class="text-lg mb-8">
                Gérez vos équipes, joueurs et matchs avec simplicité et performance.
            </p>

            @guest
                <a href="/register"
                   class="bg-white text-yellow-400 px-8 py-3 rounded-xl text-lg font-bold shadow hover:scale-105 transition">
                    Commencer
                </a>
            @endguest
        </div>

    </section>

    <!-- FEATURES -->
    <section class="py-16 px-6 max-w-6xl mx-auto">

        <div class="grid md:grid-cols-3 gap-8 text-center">

            <div class="p-6 rounded-2xl shadow hover:shadow-lg transition bg-yellow-400">
                <h3 class="font-bold text-lg mb-3">🏆 Équipes</h3>
                <p class="text-gray-500">
                    Organisez vos équipes de basket en quelques clics.
                </p>
            </div>

            <div class="p-6 rounded-2xl shadow hover:shadow-lg transition bg-yellow-400">
                <h3 class="font-bold text-lg mb-3">👥 Effectif</h3>
                <p class="text-gray-500">
                    Gérez joueurs, entraîneurs et staff facilement.
                </p>
            </div>

            <div class="p-6 rounded-2xl shadow hover:shadow-lg transition bg-yellow-400">
                <h3 class="font-bold text-lg mb-3">📅 Matchs</h3>
                <p class="text-gray-500">
                    Planifiez matchs et entraînements efficacement.
                </p>
            </div>

        </div>

    </section>

    <!-- CTA FINAL -->

    <section class="text-gray-900 py-16 text-center bg-yellow-400">
        <h3 class="text-2xl font-bold mb-6">
            Rejoignez votre équipe maintenant 🏀
        </h3>

        @guest
            <a href="/register"
               class="bg-white text-red-700 px-6 py-3 rounded-xl font-bold shadow">
                Créer un compte
            </a>
        @endguest
    </section>

    <!-- FOOTER -->
    <footer class="bg-black text-gray-400 text-center py-6">
        <p>© {{ date('Y') }} AS Labarthaise Basket</p>
    </footer>

</body>
</html>
