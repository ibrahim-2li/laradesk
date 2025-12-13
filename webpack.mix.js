const path = require('path');
const mix = require('laravel-mix');
require('laravel-mix-tailwind');

// Compile assets
mix.js('resources/js/app.js', 'public/js/app.js')
    .sourceMaps();

mix.sass('resources/sass/app.scss', 'public/css/app.css')
    .tailwind('./tailwind.config.js');

// Webpack config for Vue loader and aliases
mix.webpackConfig({
    module: {
        rules: [
            {
                test: /\.vue$/,
                loader: 'vue-loader',
                exclude: /node_modules/,
            },
            {
                test: /\.js$/,
                exclude: /node_modules/,
                loader: 'babel-loader',
            },
        ],
    },
    resolve: {
        alias: {
            '@': path.resolve('resources/js'),
            'moment': path.resolve(__dirname, 'node_modules', 'moment', 'moment.js'),
            'vue': 'vue/dist/vue.js',
        },
        extensions: ['.js', '.json', '.vue'],
    },
});

// Copy tinymce skins
mix.copy('node_modules/tinymce/skins', 'public/js/skins');

// Mix version
if (mix.inProduction()) {
    mix.version();
}

// Disable notifications
mix.disableSuccessNotifications();


// Copy tinymce skins
mix.copy('node_modules/tinymce/skins', 'public/js/skins');

// Mix version
if (mix.inProduction()) {
    mix.version();
}

// Disable notifications
mix.disableSuccessNotifications();
