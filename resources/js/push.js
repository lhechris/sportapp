function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
    return Uint8Array.from(atob(base64), c => c.charCodeAt(0));
}

function withTimeout(promise, milliseconds, message) {
    let timeoutId;
    const timeout = new Promise((_, reject) => {
        timeoutId = setTimeout(() => reject(new Error(message)), milliseconds);
    });

    return Promise.race([promise, timeout]).finally(() => clearTimeout(timeoutId));
}

async function subscribeToPush() {
    const button = document.getElementById('subscribe-push-button');
    const status = document.getElementById('push-subscription-status');

    const showStatus = (message, isError = false) => {
        if (status) {
            status.textContent = message;
            status.className = isError
                ? 'mt-2 text-sm text-red-600'
                : 'mt-2 text-sm text-green-700';
        }
    };

    if (!('serviceWorker' in navigator) || !('PushManager' in window) || !('Notification' in window)) {
        const error = new Error('Les notifications push ne sont pas supportees par ce navigateur.');
        showStatus(error.message, true);
        throw error;
    }

    if (!window.isSecureContext) {
        const error = new Error('Les notifications necessitent une connexion HTTPS.');
        showStatus(error.message, true);
        throw error;
    }

    if (Notification.permission === 'denied') {
        const error = new Error('Les notifications sont bloquees dans les reglages du navigateur.');
        showStatus(error.message, true);
        throw error;
    }

    if (button) {
        button.disabled = true;
        button.textContent = 'Inscription...';
    }

    try {
        showStatus('Autorisation des notifications...');
        const permission = await Notification.requestPermission();
        if (permission !== 'granted') {
            throw new Error('La permission de notification n\'a pas ete accordee.');
        }

        showStatus('Activation du service de notifications...');
        let registration = await navigator.serviceWorker.getRegistration('/');
        if (!registration) {
            registration = await withTimeout(
                navigator.serviceWorker.register('/service-worker.js', { scope: '/' }),
                10000,
                'Le service de notifications ne repond pas.'
            );
        }
        registration = await withTimeout(
            navigator.serviceWorker.ready,
            10000,
            'Le service de notifications ne demarre pas. Rechargez la page.'
        );

        showStatus('Creation de la souscription...');
        let subscription = await registration.pushManager.getSubscription();

        if (!subscription) {
            const vapidKey = document.querySelector('meta[name="vapid-public-key"]')?.content;
            if (!vapidKey) {
                throw new Error('La cle publique VAPID est absente.');
            }

            subscription = await registration.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: urlBase64ToUint8Array(vapidKey),
            });
        }

        showStatus('Enregistrement de la souscription...');
        const response = await withTimeout(fetch('/push/subscribe', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({
                ...subscription.toJSON(),
                contentEncoding: Array.isArray(PushManager.supportedContentEncodings)
                    ? (PushManager.supportedContentEncodings.includes('aes128gcm') ? 'aes128gcm' : 'aesgcm')
                    : 'aes128gcm',
            }),
            }), 10000, 'Le serveur ne repond pas.');

        if (!response.ok) {
            const details = await response.text();
            throw new Error(`L\'inscription a echoue (${response.status})${details ? `: ${details.slice(0, 160)}` : '.'}`);
        }

        if (button) {
            button.hidden = true;
        }
        showStatus('Notifications activees.');
    } catch (error) {
        if (button) {
            button.disabled = false;
            button.textContent = "S'abonner";
        }
        showStatus(error.message || 'Erreur lors de l\'inscription aux notifications.', true);
        console.error('Erreur lors de l\'inscription aux notifications:', error);
    } finally {
        if (button && !button.hidden) {
            button.disabled = false;
            button.textContent = "S'abonner";
        }
    }
}

window.subscribeToPush = subscribeToPush;
