// ------------------------------
// Grunt Configuration/Setup
// ------------------------------

module.exports = function (grunt) {
    var path = require('path');

    // Project configuration
    var options = {
        // path to task.js files, defaults to grunt dir
        configPath: path.join(process.cwd(), 'ui-src/grunt'),
        // auto grunt.initConfig
        init: true,
        // data passed into config.
        data: {
            pkg: grunt.file.readJSON('package.json'),
            releaseVersion: grunt.option('releaseVersion') || '',
            config: {
                /**
                 * Config - Edit this section
                 * ==========================
                 * Choose javascript release filename
                 * Choose javascript release location
                 * Choose javascript files to be uglified
                 * Choose images location
                 * Choose css release location
                 * Choose scss files to be compiled
                 * Choose foundation scss location
                 * Choose bower components location
                 */
                js: {
                    // <%=config.js.releaseDir%>
                    releaseDir: 'web/js/',
                    // <%=config.js.releaseFile%>
                    releaseFile: 'scripts.min.js',
                    // <%=config.js.scriptFileList%>
                    scriptFileList: [
                        // ES5 Shims for legacy browsers
                        //'ui-src/bower_components/es5-shim/es5-shim.js',
                        //'ui-src/bower_components/es5-shim/es5-sham.js',

                        // Libraries required by Foundation
                        'ui-src/bower_components/jquery/dist/jquery.js',
                        'ui-src/bower_components/what-input/what-input.js',

                        // Include full Foundation 6 scripts
                        'ui-src/bower_components/foundation-sites/dist/foundation.js',
                        'ui-src/bower_components/foundation-datepicker/js/foundation-datepicker.js',

                        // Core Foundation files
                        //"ui-src/bower_components/foundation-sites/js/foundation.core.js",
                        //"ui-src/bower_components/foundation-sites/js/foundation.util.*.js",

                        // Individual Foundation components
                        // If you aren't using a component, just remove it from the list,
                        //"ui-src/bower_components/foundation-sites/js/foundation.abide.js",
                        //"ui-src/bower_components/foundation-sites/js/foundation.accordion.js",
                        //"ui-src/bower_components/foundation-sites/js/foundation.accordionMenu.js",
                        //"ui-src/bower_components/foundation-sites/js/foundation.drilldown.js",
                        //"ui-src/bower_components/foundation-sites/js/foundation.dropdown.js",
                        //"ui-src/bower_components/foundation-sites/js/foundation.dropdownMenu.js",
                        //"ui-src/bower_components/foundation-sites/js/foundation.equalizer.js",
                        //"ui-src/bower_components/foundation-sites/js/foundation.interchange.js",
                        //"ui-src/bower_components/foundation-sites/js/foundation.magellan.js",
                        //"ui-src/bower_components/foundation-sites/js/foundation.offcanvas.js",
                        //"ui-src/bower_components/foundation-sites/js/foundation.orbit.js",
                        //"ui-src/bower_components/foundation-sites/js/foundation.responsiveMenu.js",
                        //"ui-src/bower_components/foundation-sites/js/foundation.responsiveToggle.js",
                        //"ui-src/bower_components/foundation-sites/js/foundation.reveal.js",
                        //"ui-src/bower_components/foundation-sites/js/foundation.slider.js",
                        //"ui-src/bower_components/foundation-sites/js/foundation.sticky.js",
                        //"ui-src/bower_components/foundation-sites/js/foundation.tabs.js",
                        //"ui-src/bower_components/foundation-sites/js/foundation.toggler.js",
                        //"ui-src/bower_components/foundation-sites/js/foundation.tooltip.js",

                        // Include helper scripts
                        'ui-src/js/helpers/console.js',
                        'ui-src/js/helpers/environment.js',
                        'ui-src/js/helpers/notification-dispatcher.js',
                        'ui-src/js/helpers/supports.js',
                        'ui-src/js/helpers/cookies.js',

                        // Include infinite scroller pagination scripts
                        //'ui-src/bower_components/jquery-ias/src/jquery-ias.js',
                        //'ui-src/bower_components/jquery-ias/src/callbacks.js',
                        //'ui-src/bower_components/jquery-ias/src/extension/spinner.js',
                        //'ui-src/bower_components/jquery-ias/src/extension/noneleft.js',

                        // Include custom jQuery plugin scripts
                        //'ui-src/js/sample_plugin.js',

                        // Main JavaScript application bootstrap file
                        'ui-src/js/cookiePolicy.js',
                        'ui-src/js/scripts.js'
                    ]
                },
                img: {
                    // <%= config.img.path %>
                    path: 'web/images/site_assets'
                },
                css: {
                    // <%= config.css.path %>
                    path: 'web/css'
                },
                scss: {
                    // <%= config.scss.path %>
                    path: 'ui-src/scss'
                },
                f6scss: {
                    // <%= config.f6scss.path %>
                    path: 'ui-src/bower_components/foundation-sites/scss'
                },
                motionUIscss: {
                    // <%= config.motionUIscss.path %>
                    path: 'ui-src/bower_components/motion-ui/src'
                },
                jstests: {
                    // <%= config.jstests.path %>
                    path: 'ui-src/js/tests'
                }
            }
        }
    };

    // Load the grunt configuration
    require('load-grunt-config')(grunt, options);
    require('jit-grunt')(grunt);

    // Load all the grunt tasks
    require('load-grunt-tasks')(grunt);

    /* ==========================================================================================
     Available tasks:

     * grunt :                      run scsslint, sass, autoprefixer, eslint, concat, babel, symfony2
     * grunt jsdev :                run eslint, concat, babel
     * grunt dev :                  run scsslint, sass, autoprefixer, eslint, concat, babel, symfony2
     * grunt compileprod :          run scsslint, sass, autoprefixer, csso, concat, babel, uglify
     * grunt release :              run scsslint, sass, autoprefixer, csso, concat, babel, uglify, symfony2
     * grunt deploy :               run scsslint, sass, autoprefixer, csso, concat, babel, uglify, symfony2
     * grunt cms_reset :            run scsslint, sass, autoprefixer, csso, concat, babel, uglify, symfony2
     * grunt runtests :             run jasmine
     * grunt travis :               run scsslint, sass, autoprefixer, csso, concat, babel, uglify, symfony2
     ============================================================================================ */

    /**
     * GRUNT
     * Default task
     * run sass, autoprefixer, eslint, concat, babel, symfony2
     */
     // Default task
    grunt.registerTask('default', [
        'dev'
    ]);


    /**
     * GRUNT JSDEV
     * A task for JavaScript development
     * run eslint, concat, babel
     */
    grunt.registerTask('jsdev', [
        'eslint',
        'concat:js',
        'babel'
    ]);


    /**
     * GRUNT DEV
     * A task for development
     * run sass, autoprefixer, eslint, concat, babel, symfony2
     */
    grunt.registerTask('dev', [
        'jsdev',
        'scsslint',
        'sass:dev',
        'autoprefixer:release',
        'sf2-console:assetic_dump_dev'
    ]);


    /**
     * GRUNT COMPILEPROD
     * A task for your production environment
     * run sass, autoprefixer, csso, concat, uglify
     */
    grunt.registerTask('compileprod', [
        'eslint',
        'concat:js',
        'babel',
        'uglify:release',
        'scsslint',
        'sass:release',
        'autoprefixer:release',
        'csso:release'
    ]);


    /**
     * GRUNT DEPLOY
     * A task for your production environment
     * run sass, autoprefixer, csso, concat, uglify, symfony2
     */
    grunt.registerTask('release', [
        'compileprod',
        'sf2-console:assetic_dump_dev',
        'sf2-console:assetic_dump_prod'
    ]);


    /**
     * GRUNT DEPLOY
     * A task for your production environment
     * run sass, autoprefixer, csso, concat, uglify, symfony2
     */
    grunt.registerTask('deploy', [
        'sf2-console:cache_clear_prod',
        'sf2-console:cache_warmup_prod',
        'release',
        'sf2-console:assetic_dump_prod'
    ]);


    /**
     * GRUNT CMS_RESET
     * A task for the initial setup
     * run sass, autoprefixer, csso, concat, uglify, symfony2
     */
    grunt.registerTask('cms_reset', [
        'sf2-console:cache_clear_dev',
        'sf2-console:cache_clear_prod',
        'sf2-console:cache_warmup_dev',
        'sf2-console:cache_warmup_prod',
        'sf2-console:doctrine_schema_drop',
        'sf2-console:doctrine_schema_create',
        'sf2-console:doctrine_fixtures_load',
        'sf2-console:sonata_media_sync_default',
        'sf2-console:sonata_media_sync_intro',
        'sf2-console:sonata_media_sync_bgimage',
        'sf2-console:sonata_media_sync_icon',
        'sf2-console:sonata_media_sync_admin',
        'release',
        'sf2-console:assetic_dump_dev',
        'sf2-console:assetic_dump_prod'
    ]);


    /**
     * GRUNT RUNTESTS
     * A task for testing
     * run jasmine
     */
    grunt.registerTask('runtests', [
        'jasmine'
    ]);


    /**
     * GRUNT TRAVIS
     * A task for Travis CI to test build
     * run sass, autoprefixer, csso, concat, uglify, symfony2
     */
    grunt.registerTask('travis', [
        'cms_reset'
    ]);
};
