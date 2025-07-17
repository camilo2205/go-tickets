const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js').postCss('resources/css/app.css', 'public/css', [
    require('postcss-import'),
    require('tailwindcss'),
    require('autoprefixer'),
]);
mix.js('resources/js/tickets.js', 'public/js/cruds/tickets.js')
mix.js('resources/js/clientes.js', 'public/js/cruds/clientes.js')
mix.js('resources/js/tareas.js', 'public/js/cruds/tareas.js')
mix.js('resources/js/sw.js', 'public/sw.js')
mix.js('resources/js/enable-push.js', 'public/js/enable-push.js')
mix.js('resources/js/dashboard.js', 'public/js/dashboard.js')
mix.css('resources/css/tickets.css', 'public/css/cruds/tickets.css')
mix.css('resources/css/posts.css', 'public/css/posts.css')
mix.css('resources/css/tareas.css', 'public/css/cruds/tareas.css')
