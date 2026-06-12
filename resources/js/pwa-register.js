// Enregistrement du Service Worker pour la PWA
if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => {
    navigator.serviceWorker
      .register('/service-worker.js', { scope: '/' })
      .then((registration) => {
        console.log('Service Worker enregistré:', registration);
        
        // Vérifier les mises à jour toutes les heures
        setInterval(() => {
          registration.update();
        }, 60 * 60 * 1000);
      })
      .catch((error) => {
        console.log('Erreur lors de l\'enregistrement du Service Worker:', error);
      });
  });

  // Gérer les mises à jour du Service Worker
  navigator.serviceWorker.addEventListener('controller', () => {
    window.location.reload();
  });
}

// Détection de l'événement d'installation (pour les navigateurs qui le supportent)
let deferredPrompt;
window.addEventListener('beforeinstallprompt', (e) => {
  // Empêcher le mini-infobar d'apparaître automatiquement
  e.preventDefault();
  // Stocker l'événement pour utilisation ultérieure
  deferredPrompt = e;
  
  // Afficher un bouton d'installation personnalisé si désiré
  const installButton = document.getElementById('install-pwa-button');
  if (installButton) {
    installButton.style.display = 'block';
    installButton.addEventListener('click', async () => {
      deferredPrompt.prompt();
      const { outcome } = await deferredPrompt.userChoice;
      console.log(`Installation ${outcome === 'accepted' ? 'acceptée' : 'refusée'}`);
      deferredPrompt = null;
      installButton.style.display = 'none';
    });
  }
});

// Détecter si l'app est lancée en tant que PWA
if (window.matchMedia('(display-mode: standalone)').matches) {
  console.log('L\'app est en mode standalone');
}
