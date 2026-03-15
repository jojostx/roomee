import axios from 'axios';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.Pusher = Pusher;

const env = import.meta.env ?? {};
const reverbKey = env.VITE_REVERB_APP_KEY ?? '';
const reverbHost = env.VITE_REVERB_HOST ?? window.location.hostname;
const reverbPort = env.VITE_REVERB_PORT ?? 8080;
const reverbScheme = (env.VITE_REVERB_SCHEME ?? 'http').toLowerCase();

if (reverbKey) {
    window.Echo = new Echo({
        broadcaster: 'reverb',
        key: reverbKey,
        wsHost: reverbHost,
        wsPort: reverbPort,
        wssPort: reverbPort,
        forceTLS: reverbScheme === 'https',
        enabledTransports: ['ws', 'wss'],
    });
}
