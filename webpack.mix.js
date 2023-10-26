/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 */

let mix = require('laravel-mix')


//const proxy = "localhost"
const proxy = "https://showroom.local"

const config = {
    externals: {
        jquery: "jQuery"
    }/*,
    stats: {
        children: true,
    },*/
}

const options = {
    processCssUrls: false,
    postCss: [require('autoprefixer')],
    //presets: [['@babel/preset-react', { runtime: 'esnext' }]]
}

/*
 |--------------------------------------------------------------------------
 | CONFIGURATION
 |--------------------------------------------------------------------------
 */
mix
    .webpackConfig(config)
    .setPublicPath("./dist")
    .disableNotifications()
    .options(options)
    .sourceMaps(mix.inProduction(), 'source-map')

/*
 |--------------------------------------------------------------------------
 | COMPILE JS & CSS
 |--------------------------------------------------------------------------
 */
mix
    .js('src/js/app.js', 'dist/js/')
    .js('src/js/admin.js', 'dist/js/')
    .js('src/js/customizer.js', 'dist/js/')
    .js('src/js/theme.js', 'dist/js/')
    .extract()

    .js('src/js/editor.js', 'dist/js/')
    .react()
    .extract(["react"])

    .sass('src/scss/style.scss', 'dist/css/')
    .sass('src/scss/admin.scss', 'dist/css/admin.css')
    .sass('src/scss/editor.scss', 'dist/css/editor.css')
    .version()


/*
 |--------------------------------------------------------------------------
 | COPY ASSETS
 |--------------------------------------------------------------------------
 */
mix
    .copyDirectory("src/images", "dist/images")
    .copyDirectory("src/fonts", "dist/fonts")


/*
 |--------------------------------------------------------------------------
 | BROWSERSYNC
 |--------------------------------------------------------------------------
 */
mix
    .browserSync({
        proxy: proxy,
        open: false,
        files: [
            'dist/**/*.{css,js}',
            'templates/**/*.php'
        ]
    })
