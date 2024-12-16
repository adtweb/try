/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */
window.s3Path = import.meta.env.VITE_S3_BASE_URL;

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import { DateTime } from "luxon";
window.DateTime = DateTime;

import Alpine from "alpinejs";
import mask from "@alpinejs/mask";
import Precognition from "laravel-precognition-alpine";
Alpine.plugin(mask);
Alpine.plugin(Precognition);

// Stores
import "./stores/locale";
import "./stores/user";
import "./stores/toasts";

window.Alpine = Alpine;
Alpine.start();

import { saveAs } from "file-saver";
window.saveAs = saveAs;

import Echo from "laravel-echo";

import Pusher from "pusher-js";
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: "reverb",
    key: import.meta.env.VITE_WS_APP_KEY,
    wsHost: import.meta.env.VITE_WS_HOST,
    wsPort: import.meta.env.VITE_WS_PORT,
    wssPort: import.meta.env.VITE_WS_PORT,
    forceTLS: (import.meta.env.VITE_WS_SCHEME ?? "https") === "https",
    enabledTransports: ["ws", "wss"],
});

// Pusher.log = function (message) {
//     if (window.console && window.console.log) window.console.log(message);
// };


