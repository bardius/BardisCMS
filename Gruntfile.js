// ------------------------------
// Grunt Configuration/Setup
// ------------------------------

module.exports = function (grunt) {

    'use strict';

    // Project configuration
    var options = {
        init: true,
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
                    releaseDir: 'web/js/release/',
                    // <%=config.js.releaseFile%>
                    releaseFile: 'scripts.min.js',
                    // <%=config.js.modernizrReleaseFile%>
                    modernizrReleaseFile: 'modernizr.min.js',
                    // <%=config.js.scriptFileList%>
                    scriptFileList: [
                        'web/bower_components/jquery/jquery.js',
                        // Include only used Foundation 5 scripts if needed instead of the minified full framework ones
                        //'web/bower_components/foundation/js/foundation.js',
                        //'web/bower_components/foundation/js/foundation/*.js',

                        // Include all Foundation 5 scripts
                        'web/bower_components/foundation/js/foundation.js',
                        'web/js/helpers/environment.js',
                        'web/js/helpers/supports.js',
                        'web/js/helpers/console.js',
                        'web/js/helpers/limit.js',
                        'web/js/helpers/notification-dispatcher.js',
                        'web/js/helpers/smartResize.js',
                        'web/js/libs/mobile/normalized.addressbar.js',
                        //'web/bower_components/jquery-ias/src/jquery-ias.js',
                        //'web/bower_components/jquery-ias/src/callbacks.js',
                        //'web/bower_components/jquery-ias/src/extension/spinner.js',
                        //'web/bower_components/jquery-ias/src/extension/noneleft.js',
                        //'web/js/sample_plugin.js',
                        'web/js/scripts.js'
                    ],
                    modernizrScriptFile: [
                        'web/bower_components/modernizr/modernizr.js'
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
                    path: 'web/scss'
                },
                f5scss: {
                    // <%= config.f5scss.path %>
                    path: 'web/bower_components/foundation/scss'
                },
                jstests: {
                    // <%= config.jstests.path %>
                    path: 'web/js/tests'
                },
                bower: {
                    // <%= config.bower.path %>
                    path: './web/bower_components'
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

     * grunt :                      run sass, postcss, csssplit, jshint, uglify, symfony2
     * grunt watch :                run sass, postcss, csssplit, jshint, uglify, symfony2
     * grunt jsdev :                run jshint, uglify, symfony2
     * grunt dev :                  run sass, postcss, csssplit, jshint, uglify, symfony2
     * grunt compileprod :          run sass, postcss, combine_mq, csssplit, csso, uglify
     * grunt deploy :               run sass, postcss, combine_mq, csssplit, csso, uglify, symfony2
     * grunt deployment_prod :      run sass, postcss, combine_mq, csssplit, csso, uglify, symfony2
     * grunt setup :                run bower install, sass, postcss, csssplit, jshint, uglify, symfony2
     * grunt first_deployment :     run bower install, sass, postcss, combine_mq, csssplit, csso, uglify, symfony2
     * grunt runtests :             run jasmine
     * grunt travis :               run bower install, sass, postcss, combine_mq, csssplit, csso, uglify, symfony2
     ============================================================================================ */

    /**
     * GRUNT
     * Default task
     * run sass, postcss, csssplit, jshint, uglify, symfony2
     */
    // Default task
    grunt.registerTask('default', [
        'dev'
    ]);


    /**
     * GRUNT JSDEV
     * A task for JavaScript development
     * run jshint, uglify, symfony2
     */
    grunt.registerTask('jsdev', [
        'jshint',
        'uglify:release',
        'symfony2:sf2-console:assetic_dump_dev'
    ]);


    /**
     * GRUNT DEV
     * A task for development
     * run sass, postcss, csssplit, jshint, uglify, symfony2
     */
    grunt.registerTask('dev', [
        'jsdev',
        'sass:release',
        'postcss:release',
        'csssplit:release',
        'symfony2:sf2-console:assetic_dump_dev'
    ]);


    /**
     * GRUNT COMPILEPROD
     * A task for your production environment
     * run sass, postcss, combine_mq, csssplit, csso, uglify
     */
    grunt.registerTask('compileprod', [
        'uglify:production',
        'sass:production',
        'postcss:release',
        'combine_mq:release',
        'csssplit:release',
        'csso:release'
    ]);


    /**
     * GRUNT DEPLOY
     * A task for your production environment
     * run sass, postcss, combine_mq, csssplit, csso, uglify, symfony2
     */
    grunt.registerTask('deploy', [
        'compileprod',
        'symfony2:sf2-console:assetic_dump_dev',
        'symfony2:sf2-console:assetic_dump_prod'
    ]);


    /**
     * GRUNT DEPLOYMENT_PROD
     * A task for your production environment
     * run sass, postcss, combine_mq, csssplit, csso, uglify, symfony2
     */
    grunt.registerTask('deployment_prod', [
        'symfony2:sf2-console:cache_clear_prod',
        'symfony2:sf2-console:cache_warmup_prod',
        'compileprod',
        'symfony2:sf2-console:assetic_dump_prod'
    ]);


    /**
     * GRUNT SETUP
     * A task for downloading dependencies and initial build run
     * run bower install, sass, postcss, csssplit, jshint, uglify, symfony2
     */
    grunt.registerTask('setup', [
        'bower:install',
        'dev'
    ]);


    /**
     * GRUNT FIRST_DEPLOYMENT
     * A task for the initial setup
     * run bower install, sass, postcss, combine_mq, csssplit, csso, uglify, symfony2
     */
    grunt.registerTask('first_deployment', [
        'symfony2:sf2-console:cache_clear_dev',
        'symfony2:sf2-console:cache_clear_prod',
        'symfony2:sf2-console:cache_warmup_dev',
        'symfony2:sf2-console:cache_warmup_prod',
        'symfony2:sf2-console:doctrine_schema_drop',
        'symfony2:sf2-console:doctrine_schema_create',
        'symfony2:sf2-console:doctrine_fixtures_load',
        'symfony2:sf2-console:sonata_media_sync_default',
        'symfony2:sf2-console:sonata_media_sync_intro',
        'symfony2:sf2-console:sonata_media_sync_bgimage',
        'symfony2:sf2-console:sonata_media_sync_icon',
        'symfony2:sf2-console:sonata_media_sync_admin',
        'compileprod',
        'symfony2:sf2-console:assetic_dump_dev',
        'symfony2:sf2-console:assetic_dump_prod'
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
     * run bower install, sass, postcss, combine_mq, csssplit, csso, uglify, symfony2
     */
    grunt.registerTask('travis', [
        'first_deployment'
    ]);
};
