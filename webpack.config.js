var Encore = require('@symfony/webpack-encore');

Encore
    // the project directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // the public path used by the web server to access the previous directory
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    .addEntry('js/index', './assets/js/index.js')
    .addStyleEntry('css/style', './assets/css/style.scss')
    // uncomment to create hashed filenames (e.g. app.abc123.css)
    // .enableVersioning(Encore.isProduction())

    // uncomment if you use Sass/SCSS files
    .enableSassLoader()
;

module.exports = Encore.getWebpackConfig();
