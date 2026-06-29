// resources/js/pwa-install.js

let deferredPrompt = null;
const btn = () => document.getElementById('btn-install');

// Capture l'événement d'installation avant qu'il ne disparaisse
window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault(); // empêche la bannière auto du navigateur
    deferredPrompt = e;

    // Affiche le bouton
    btn()?.classList.remove('hidden');
    btn()?.classList.add('flex');
});

// Déclenché au clic
window.installPWA = async () => {
    if (!deferredPrompt) return;

    deferredPrompt.prompt();

    const { outcome } = await deferredPrompt.userChoice;
    console.log(`Choix de l'utilisateur : ${outcome}`); // 'accepted' ou 'dismissed'

    deferredPrompt = null;

    // Cache le bouton après l'interaction
    btn()?.classList.add('hidden');
    btn()?.classList.remove('flex');
};

// Si l'app est déjà installée, cache définitivement le bouton
window.addEventListener('appinstalled', () => {
    btn()?.classList.add('hidden');
    deferredPrompt = null;
});