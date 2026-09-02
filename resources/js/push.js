function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
    return Uint8Array.from(atob(base64), c => c.charCodeAt(0));
}

async function subscribeToPush() {
    const button = document.getElementById('subscribe-push-button');

    if (!('serviceWorker' in navigator) || !('PushManager' in window) || !('Notification' in window)) {
        throw new Error('Les notifications push ne sont pas supportees par ce navigateur.');
    }

    if (Notification.permission === 'denied') {
        throw new Error('Les notifications sont bloquees dans les reglages du navigateur.');
    }

    if (button) {
        button.disabled = true;
        button.textContent = 'Inscription...';
    }

    try {
        const permission = await Notification.requestPermission();
        if (permission !== 'granted') {
            throw new Error('La permission de notification n\'a pas ete accordee.');
        }

        const registration = await navigator.serviceWorker.ready;
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

        const response = await fetch('/push/subscribe', {
            method: 'POST',
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
        });

        if (!response.ok) {
            throw new Error(`L\'inscription a echoue (${response.status}).`);
        }

        if (button) {
            button.hidden = true;
        }
    } catch (error) {
        if (button) {
            button.disabled = false;
            button.textContent = "S'abonner";
        }
        console.error('Erreur lors de l\'inscription aux notifications:', error);
        throw error;
    }
}

window.subscribeToPush = subscribeToPush;
