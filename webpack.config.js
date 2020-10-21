var Encore = require('@symfony/webpack-encore');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    // only needed for CDN's or sub-directory deploy
    //.setManifestKeyPrefix('build/')

    .copyFiles({
         from: './assets/medias',

         // optional target path, relative to the output dir
         //to: 'images/[path][name].[ext]',

         // if versioning is enabled, add the file hash too
         //to: 'images/[path][name].[hash:8].[ext]',

         // only copy files matching this pattern
         //pattern: /\.(png|jpg|jpeg)$/
     })
     /* Cela copiera tous les dossiers et fichiers de 'assets/medias/' vers le chemin de sortie 'public/build/'.
     *  Si le contrôle de version est activé, les fichiers copiés comprendront un hachage basé sur leur contenu.
     *  Ex: effectuer le rendu à l'intérieur de Twig d'une image, en utilisant la fonction asset()
     *      <img src="{{ asset('build/images/icon/favicon.png') }}" alt="logo">
     *      Dossier d'origine dans le projet 'assets/medias/images/icon/favicon.png'
     *      Copira vers le dossier 'public/build/images/icon/favicon.png'
     */

    /*
     * ENTRY CONFIG
     *
     * Add 1 entry for each "page" of your app
     * (including one that's included on every page - e.g. "app")
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if your JavaScript imports CSS.
     */
    .addEntry('app', './assets/js/app.js')
    .addEntry('main', './assets/js/main.js')
    .addEntry('telecommandeWeb', './assets/js/telecommandeWeb.js')

    //.addEntry('page2', './assets/page2.js')

    // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
    .splitEntryChunks()

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()

    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    // .enableVersioning(Encore.isProduction())

    // enables @babel/preset-env polyfills
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })

    // enables Sass/SCSS support
    .enableSassLoader()

    // uncomment if you use TypeScript
    //.enableTypeScriptLoader()

    // uncomment to get integrity="..." attributes on your script & link tags
    // requires WebpackEncoreBundle 1.4 or higher
    //.enableIntegrityHashes(Encore.isProduction())

    // uncomment if you're having problems with a jQuery plugin
    //.autoProvidejQuery()

    // uncomment if you use API Platform Admin (composer req api-admin)
    //.enableReactPreset()
    //.addEntry('admin', './assets/admin.js')
    // .enableVersioning();
;

module.exports = Encore.getWebpackConfig();
