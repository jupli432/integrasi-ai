const mix = require('laravel-mix');

mix.js('resources/assets/js/app.js', 'public/js')
   .vue() 
    .sass('resources/assets/sass/app.scss', 'public/css')
    .webpackConfig({
    resolve: {
        alias: {
            'vue$': 'vue/dist/vue.esm-bundler.js'
        }
    }
    })
    .browserSync('127.0.0.1:8830');