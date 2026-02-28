import axios from 'axios';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

window.Pusher = Pusher;

const env = import.meta.env ?? {};
const pusherKey = env.VITE_PUSHER_APP_KEY ?? env.MIX_PUSHER_APP_KEY ?? '';
const pusherHost = env.VITE_PUSHER_HOST ?? env.MIX_PUSHER_HOST ?? window.location.hostname;
const pusherPort = env.VITE_PUSHER_PORT ?? env.MIX_PUSHER_PORT ?? 6001;
const pusherScheme = (env.VITE_PUSHER_SCHEME ?? env.MIX_PUSHER_SCHEME ?? '').toLowerCase();
const hasConfiguredPusherKey = typeof pusherKey === 'string' && pusherKey !== '' && pusherKey !== 'app-key-123';

if (hasConfiguredPusherKey) {
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: pusherKey,

        wsHost: pusherHost,
        wsPort: pusherPort,
        wssPort: pusherPort,

        disableStats: true,
        forceTLS: pusherScheme === 'https',
        encrypted: true,
        enabledTransports: ['ws', 'wss']
    });
}
