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

     * grunt :                      run sass, autoprefixer, csssplit, jshint, uglify, symfony2
     * grunt watch :                run sass, autoprefixer, csssplit, jshint, uglify, symfony2
     * grunt jsdev :                run jshint, uglify, symfony2
     * grunt dev :                  run sass, autoprefixer, csssplit, jshint, uglify, symfony2
     * grunt compileprod :          run sass, autoprefixer, combine_mq, csssplit, csso, uglify
     * grunt deploy :               run sass, autoprefixer, combine_mq, csssplit, csso, uglify, symfony2
     * grunt deployment_prod :      run sass, autoprefixer, combine_mq, csssplit, csso, uglify, symfony2
     * grunt setup :                run bower install, sass, autoprefixer, csssplit, jshint, uglify, symfony2
     * grunt first_deployment :     run bower install, sass, autoprefixer, combine_mq, csssplit, csso, uglify, symfony2
     * grunt runtests :             run jasmine
     * grunt travis :               run bower install, sass, autoprefixer, combine_mq, csssplit, csso, uglify, symfony2
     ============================================================================================ */

    /**
     * GRUNT
     * Default task
     * run sass, autoprefixer, csssplit, jshint, uglify, symfony2
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
        'sf2-console:assetic_dump_dev'
    ]);


    /**
     * GRUNT DEV
     * A task for development
     * run sass, autoprefixer, csssplit, jshint, uglify, symfony2
     */
    grunt.registerTask('dev', [
        'jsdev',
        'sass:release',
        'autoprefixer:release',
        'csssplit:release',
        'sf2-console:assetic_dump_dev'
    ]);


    /**
     * GRUNT COMPILEPROD
     * A task for your production environment
     * run sass, autoprefixer, combine_mq, csssplit, csso, uglify
     */
    grunt.registerTask('compileprod', [
        'uglify:production',
        'sass:production',
        'autoprefixer:release',
        'combine_mq:release',
        'csssplit:release',
        'csso:release'
    ]);


    /**
     * GRUNT DEPLOY
     * A task for your production environment
     * run sass, autoprefixer, combine_mq, csssplit, csso, uglify, symfony2
     */
    grunt.registerTask('deploy', [
        'compileprod',
        'sf2-console:assetic_dump_dev',
        'sf2-console:assetic_dump_prod'
    ]);


    /**
     * GRUNT DEPLOYMENT_PROD
     * A task for your production environment
     * run sass, autoprefixer, combine_mq, csssplit, csso, uglify, symfony2
     */
    grunt.registerTask('deployment_prod', [
        'sf2-console:cache_clear_prod',
        'sf2-console:cache_warmup_prod',
        'compileprod',
        'sf2-console:assetic_dump_prod'
    ]);


    /**
     * GRUNT SETUP
     * A task for downloading dependencies and initial build run
     * run bower install, sass, autoprefixer, csssplit, jshint, uglify, symfony2
     */
    grunt.registerTask('setup', [
        'bower:install',
        'dev'
    ]);


    /**
     * GRUNT FIRST_DEPLOYMENT
     * A task for the initial setup
     * run bower install, sass, autoprefixer, combine_mq, csssplit, csso, uglify, symfony2
     */
    grunt.registerTask('first_deployment', [
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
        'compileprod',
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
     * run bower install, sass, autoprefixer, combine_mq, csssplit, csso, uglify, symfony2
     */
    grunt.registerTask('travis', [
        'first_deployment'
    ]);
};
