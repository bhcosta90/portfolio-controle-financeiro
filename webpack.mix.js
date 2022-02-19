const mix = require("laravel-mix");

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

// mix.js('resources/js/app.js', 'public/js')
//     .sass('resources/sass/app.scss', 'public/css')
//     .scripts(['resources/js/home.js'], 'public/js/home.js')
//     .sourceMaps();
mix.scripts(
    [
        "node_modules/jquery/dist/jquery.js",
        "node_modules/bootstrap/dist/js/bootstrap.js",
        "node_modules/select2/dist/js/select2.full.js",
        "node_modules/select2/dist/js/i18n/pt-BR.js",
        "node_modules/jquery-mask-plugin/dist/jquery.mask.js",
        "node_modules/toastr/build/toastr.min.js",
        "node_modules/sweetalert2/dist/sweetalert2.all.js",
        "resources/js/vendor.js",
    ],
    "public/js/vendor.js"
)
    .sass("resources/sass/app.scss", "public/css")
    .scripts(["resources/js/home.js"], "public/js/home.js")
    .sourceMaps()
    .version();
