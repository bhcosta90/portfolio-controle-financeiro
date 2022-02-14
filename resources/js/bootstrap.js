window._ = require("lodash");

try {
    require("bootstrap");
} catch (e) {}

try {
    window.$ = require("jquery");

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            Authorization:
                "Bearer " + $('meta[name="user-token"]').attr("content"),
        },
    });
} catch (e) {}

try {
    window.select2 = require("select2");
    $.fn.select2.defaults.set("theme", "bootstrap-5");
    $("select.select2").select2();
} catch (e) {
    console.log(e);
}

try {
    require("jquery-mask-plugin");
    // $("input.value").mask("000.000.000.000.000,00", { reverse: true });
    $("input.value.negative").mask("000.000,00", {
        reverse: true,
        translation: {
            0: {
                pattern: /-|\d/,
                recursive: true,
            },
        },
        onChange: function (value, e) {
            e.target.value = value
                .replace(/^-\./, "-")
                .replace(/^-,/, "-")
                .replace(/(?!^)-/g, "");
        },
    });

    $("input.value").mask("000.000,00", {
        reverse: true,
        translation: {
            0: {
                pattern: /\d/,
                recursive: true,
            },
        },
        onChange: function (value, e) {
            e.target.value = value
                .replace(/^-\./, "-")
                .replace(/^-,/, "-")
                .replace(/(?!^)-/g, "");
        },
    });
} catch (e) {
    console.log(e);
}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require("axios");

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });
