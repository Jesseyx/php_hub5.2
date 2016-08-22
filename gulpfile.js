var elixir = require('laravel-elixir');
require('laravel-elixir-livereload');
require('laravel-elixir-compress');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

var production = elixir.config.production;
var baseJs = [
    'resources/assets/js/vendor/jquery.min.js',
    'resources/assets/js/vendor/bootstrap.min.js',
    'resources/assets/js/vendor/moment.min.js',
    'resources/assets/js/vendor/zh-cn.min.js',
    'resources/assets/js/vendor/emojify.min.js',
    'resources/assets/js/vendor/jquery.scrollUp.js',
    'resources/assets/js/vendor/jquery.pjax.js',
    'resources/assets/js/vendor/nprogress.js',
    'resources/assets/js/vendor/jquery.autosize.min.js',
    'resources/assets/js/vendor/prism.js',
    'resources/assets/js/vendor/jquery.textcomplete.js',
    'resources/assets/js/vendor/emoji.js',
    'resources/assets/js/vendor/marked.min.js',
    'resources/assets/js/vendor/ekko-lightbox.js',
    'resources/assets/js/vendor/localforage.min.js',
    'resources/assets/js/vendor/jquery.inline-attach.min.js',
    'resources/assets/js/vendor/snowfall.jquery.min.js',
    'resources/assets/js/vendor/upload-image.js',
    'resources/assets/js/vendor/bootstrap-switch.js',
    'resources/assets/js/vendor/messenger.js',
    'node_modules/sweetalert/dist/sweetalert.min.js',
    'node_modules/social-share.js/dist/js/social-share.min.js',
];

elixir(function(mix) {
    mix
        .copy([
            'node_modules/bootstrap-sass/assets/fonts/bootstrap'
        ], 'public/assets/fonts/bootstrap')

        .copy([
            'node_modules/font-awesome/fonts'
        ], 'public/assets/fonts/font-awesome')

        // https://github.com/overtrue/share.js
        .copy([
            'node_modules/social-share.js/dist/fonts'
        ], 'public/assets/fonts/iconfont')

        .copy([
            'node_modules/social-share.js/dist/fonts'
        ], 'public/build/assets/fonts/iconfont')

        .copy([
            'resources/assets/fonts/googlefont'
        ], 'public/build/assets/fonts/googlefont')

        .copy([
            'resources/assets/images/**/*'
        ], 'public/assets/images')

        .sass([
            'base.scss',
            'main.scss',
        ], 'public/assets/css/styles.css')

        .scripts(baseJs.concat([
            'resources/assets/js/main.js',
        ]), 'public/assets/js/scripts.js', './')

        // API Web View
        .sass([
            'api/api.scss'
        ], 'public/assets/css/api.css')
        // API Web View
        .scripts([
            'api/emojify.js',
            'api/api.js'
        ], 'public/assets/js/api.js')

        .version([
            'assets/css/styles.css',
            'assets/js/scripts.js',

            // API Web View
            'assets/css/api.css',
            'assets/js/api.js',
        ])

        .livereload();

    if (production) {
        mix.compress();
    }
});
