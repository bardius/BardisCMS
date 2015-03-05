module.exports = function (grunt) {

    'use strict';

    /**
     * Project configuration
     */
    grunt.initConfig({
        pkg: require('./package'), // <%=pkg.name%> grunt.file.readJSON('package.json'),

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
        config: {
            js: {
                // <%=config.js.releaseDir%>
                releaseDir: 'web/js/release/',
                // <%=config.js.releaseFile%>
                releaseFile: 'scripts.min.js',
                // <%=config.js.modernizrReleaseFile%>
                modernizrReleaseFile: 'modernizr.min.js',
                // <%=config.js.scriptFileList%>
                scriptFileList: [
                    'web/bower_components/jquery/jquery.min.js',
                    // Include only used Foundation 5 scripts if needed instead of the minified full framework ones
                    //'web/bower_components/foundation/js/foundation.js',
                    //'web/bower_components/foundation/js/foundation/*.js',

                    // Include all Foundation 5 scripts
                    'web/bower_components/foundation/js/foundation.min.js',
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
        },
        /**
         * Watch
         * https://github.com/gruntjs/grunt-contrib-watch
         * Watches your scss, js etc for changes and compiles them
         */
        watch: {
            grunt: {
                files: ['Gruntfile.js']
            },
            scss: {
                files: ['<%= config.scss.path %>/**/*.scss'],
                tasks: ['sass:release', 'autoprefixer:release']
            },
            js: {
                files: ['<%=config.js.scriptFileList%>', 'Gruntfile.js'],
                tasks: ['uglify']
            },
            livereload: {
                options: {livereload: true},
                files: ['<%= config.css.path %>/*.css']
            }
        },
        /**
         * Sass compilation
         * https://github.com/gruntjs/grunt-contrib-sass
         * Also creates source maps
         */
        sass: {
            release: {
                options: {
                    loadPath: [
                        '<%= config.f5scss.path %>'
                    ],
                    unixNewlines: true,
                    style: 'expanded', //compressed - expanded
                    lineNumbers: false,
                    debugInfo: false,
                    precision: 8,
                    sourcemap: false
                },
                files: {
                    '<%= config.css.path %>/styles.css': '<%= config.scss.path %>/app.scss'
                }
            },
            minifyparts: {
                options: {
                    unixNewlines: false,
                    style: 'compressed',
                    lineNumbers: false,
                    debugInfo: false,
                    precision: 8,
                    sourcemap: false
                },
                files: {
                    //'<%= config.css.path %>/styles_bundle_2.css': '<%= config.css.path %>/styles_bundle_2.css',
                    '<%= config.css.path %>/styles_bundle_1.css': '<%= config.css.path %>/styles_bundle_1.css'
                }
            },
            production: {
                options: {
                    loadPath: [
                        '<%= config.f5scss.path %>'
                    ],
                    unixNewlines: false,
                    style: 'compressed', //compressed - expanded
                    lineNumbers: false,
                    debugInfo: false,
                    precision: 8,
                    sourcemap: false
                },
                files: {
                    '<%= config.css.path %>/styles.css': '<%= config.scss.path %>/app.scss'
                }
            }
        },
        /**
         * Autoprefixer
         * https://github.com/nDmitry/grunt-autoprefixer
         * https://github.com/ai/autoprefixer
         * Auto prefixes your CSS using caniuse data
         */
        autoprefixer: {
            release: {
                options: {
                    // Task-specific options go here - we are supporting
                    // the last 2 browsers, any browsers with >3% market share,
                    // and ensuring we support IE8 + 9 with prefixes
                    browsers: ['> 3%', 'last 3 versions', 'firefox > 3.6', 'ie > 8'],
                    map: true
                },
                files: {
                    '<%= config.css.path %>/styles.css': '<%= config.css.path %>/styles.css'
                }
            }
        },
        /**
         * combine_mq
         * https://github.com/frontendfriends/grunt-combine-mq
         * Combine matching media queries into one media query definition.
         * Useful for CSS generated by preprocessors using nested media queries.
         */
        combine_mq: {
            release: {
                options: {
                    beautify: false
                },
                src: '<%= config.css.path %>/styles.css',
                dest: '<%= config.css.path %>/styles.css'
            }
        },
        /**
         * csssplit
         * https://github.com/project-collins/grunt-csssplit
         * Auto splits css to multiple files as
         * IE9 has a per file css rule limit of 4096
         */
        csssplit: {
            release: {
                src: ['<%= config.css.path %>/styles.css'],
                dest: '<%= config.css.path %>',
                options: {
                    maxSelectors: 4095,
                    maxPages: 10,
                    suffix: '_bundle_'
                }
            }
        },
        /**
         * CSSO
         * https://github.com/t32k/grunt-csso
         * Minify CSS files with CSSO
         */
        csso: {
            release: {
                files: {
                    '<%= config.css.path %>/styles.css': '<%= config.css.path %>/styles.css',
                    '<%= config.css.path %>/styles_bundle_1.css': '<%= config.css.path %>/styles_bundle_1.css',
                    '<%= config.css.path %>/styles_bundle_2.css': '<%= config.css.path %>/styles_bundle_2.css'
                }
            }
        },
        /**
         * Uglify
         * https://github.com/gruntjs/grunt-contrib-uglify
         * Minifies and concatinates your JS
         * Also creates source maps
         */
        uglify: {
            options: {
                mangle: true, // mangle: Turn on or off mangling
                beautify: false, // beautify: beautify your code for debugging/troubleshooting purposes
                compress: false,
                // report: 'gzip', // report: Show file size report
                sourceMap: '<%=config.js.releaseDir%><%=config.js.releaseFile%>.map',
                sourceMappingURL: '/<%=config.js.releaseFile%>.map'
            },
            js: {
                files: {
                    '<%=config.js.releaseDir%><%=config.js.releaseFile%>': '<%=config.js.scriptFileList%>',
                    '<%= config.js.releaseDir %><%= config.js.modernizrReleaseFile %>': '<%= config.js.modernizrScriptFile %>'
                }
            }
        },
        /**
         * JSHint
         * https://github.com/gruntjs/grunt-contrib-jshint
         * Manage the options inside .jshintrc file
         */
        jshint: {
            all: '<%=config.js.scriptFileList%>',
            options: {
                jshintrc: '.jshintrc'
            }
        },
        /**
         * JSCS
         * https://github.com/dsheiko/grunt-jscs
         * Manage the options inside .jscs.json file
         */
        jscs: {
            src: '<%=config.js.scriptFileList%>',
            options: {
                config: ".jscs.json"
            }
        },
        /**
         * Jasmine
         * Run Jasmine unit tests
         */
        jasmine: {
            test: {
              src: '<%=config.js.releaseDir%>/*.js',
                options: {                    
                    vendor: [
                      'web/bower_components/jquery/jquery.min.js',
                      'web/bower_components/jasmine-jquery/lib/jasmine-jquery.js'
                    ],
                    specs: '<%= config.jstests.path %>/*.spec.js'
                }
            }
        },
        /**
         * Bower install
         * https://github.com/yatskevich/grunt-bower-task
         * Install bower dependencies
         */
        bower: {
            install: {
                options: {
                    targetDir: "./public_html/bower_components", //<%= config.bower.path %>,
                    install: true,
                    verbose: false,
                    cleanTargetDir: false,
                    cleanBowerDir: false
                }
            }
        },
        /**
         * Symfony2 Console
         * https://www.npmjs.org/package/grunt-symfony2
         * Grunt plugin for running Symfony2 commands
         */
        'sf2-console': {
            options: {
                bin: 'app/console'
            },
            cache_clear_prod: {
                cmd: 'cache:clear',
                args: {
                    env: 'prod'
                }
            },
            cache_clear_dev: {
                cmd: 'cache:clear',
                args: {
                    env: 'dev'
                }
            },
            cache_warmup_prod: {
                cmd: 'cache:warmup',
                args: {
                    env: 'prod'
                }
            },
            cache_warmup_dev: {
                cmd: 'cache:warmup',
                args: {
                    env: 'dev'
                }
            },
            doctrine_schema_drop: {
                cmd: 'doctrine:schema:drop',
                args: {
                    env: 'dev',
                    force: true
                }
            },
            doctrine_schema_create: {
                cmd: 'doctrine:schema:create',
                args: {
                    env: 'dev'
                }
            },
            doctrine_fixtures_load: {
                cmd: 'doctrine:fixtures:load',
                args: {
                    env: 'dev',
                    append: true
                }
            },
            doctrine_schema_update: {
                cmd: 'doctrine:schema:update',
                args: {
                    force: true
                }
            },
            doctrine_schema_validate: {
                cmd: 'doctrine:schema:validate',
                args: {
                }
            },
            sonata_media_sync_default: {
                cmd: 'sonata:media:sync sonata.media.provider.image default'
            },
            sonata_media_sync_intro: {
                cmd: 'sonata:media:sync sonata.media.provider.image intro'
            },
            sonata_media_sync_bgimage: {
                cmd: 'sonata:media:sync sonata.media.provider.image bgimage'
            },
            sonata_media_sync_icon: {
                cmd: 'sonata:media:sync sonata.media.provider.image icon'
            },
            assetic_dump_dev: {
                cmd: 'assetic:dump',
                args: {
                    env: 'dev'
                }
            },
            assetic_dump_prod: {
                cmd: 'assetic:dump',
                args: {
                    env: 'prod'
                }
            },
            twig_lint: {
                cmd: 'twig:lint',
                args: {
                }
            }
        }

    });

    // Load all the grunt tasks
    require('load-grunt-tasks')(grunt);

    /* ==========================================================================================
     Available tasks:

     * grunt :                      run uglify, sass, autoprefixer, csssplit, sf2-console
     * grunt watch :                run uglify, sass, autoprefixer, csssplit, sf2-console
     * grunt dev :                  run uglify, sass, autoprefixer, csssplit, sf2-console
     * grunt jsdev :                run uglify, sf2-console
     * grunt deploy :               run uglify, sass, autoprefixer, csssplit, sf2-console
     * grunt deployment_prod :      run uglify, sass, autoprefixer, csssplit, sf2-console
     * grunt setup :                run bower install, uglify, sass, autoprefixer, csssplit, sf2-console
     * grunt first_deployment :     run bower install, uglify, sass, autoprefixer, csssplit, sf2-console
     * grunt jasmine :              run jshint, jasmine
     * grunt travis :               run uglify, sass, autoprefixer, csssplit, sf2-console
     ============================================================================================ */

    /**
     * GRUNT
     * Default task
     * run sass, autoprefixer, csssplit, uglify
     */
    // Default task
    grunt.registerTask('default', [
        //'jshint',
        'uglify',
        'sass:release',
        'autoprefixer:release',
        'csssplit:release',
        'sf2-console:assetic_dump_dev'
    ]);


    /**
     * GRUNT DEV
     * A task for development
     * run sass, autoprefixer, csssplit, uglify
     */
    grunt.registerTask('dev', [
        //'jshint',
        'uglify',
        'sass:release',
        'autoprefixer:release',
        'csssplit:release',
        'sf2-console:assetic_dump_dev'
    ]);


    /**
     * GRUNT JSDEV
     * A task for JavaScript development
     * run uglify
     */
    grunt.registerTask('jsdev', [
        //'jshint',
        'uglify',
        'sf2-console:assetic_dump_dev'
    ]);


    /**
     * GRUNT DEPLOY
     * A task for your production environment
     * run sass, autoprefixer, combine_mq, csssplit, csso, uglify
     */
    grunt.registerTask('deploy', [
        //'jshint',
        'uglify',
        'sass:production',
        'autoprefixer:release',
        'combine_mq:release',
        'csssplit:release',
        'csso:release',
        'sf2-console:assetic_dump_dev',
        'sf2-console:assetic_dump_prod'
    ]);


    /**
     * GRUNT DEPLOYMENT_PROD
     * A task for your production environment
     * run sass, autoprefixer, combine_mq, csssplit, csso, uglify
     */
    grunt.registerTask('deployment_prod', [
        'sf2-console:cache_clear_prod',
        'sf2-console:cache_warmup_prod',
        //'jshint',
        'uglify',
        'sass:production',
        'autoprefixer:release',
        'combine_mq:release',
        'csssplit:release',
        'csso:release',
        'sf2-console:assetic_dump_prod'
    ]);


    /**
     * GRUNT SETUP
     * A task for downloading dependencies and initial build run
     * run bower install, sass, autoprefixer, csssplit, uglify
     */
    grunt.registerTask('setup', [
        'bower:install',
        'uglify',
        'sass:release',
        'autoprefixer:release',
        'csssplit:release',
        'sf2-console:assetic_dump_dev'
    ]);


    /**
     * GRUNT FIRST_DEPLOYMENT
     * A task for the initial setup
     * run bower install, uglify and sass
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
        //'bower:install',
        'uglify',
        'sass:production',
        'autoprefixer:release',
        'combine_mq:release',
        'csssplit:release',
        'csso:release',
        'sf2-console:assetic_dump_dev',
        'sf2-console:assetic_dump_prod'
    ]);


    /**
     * GRUNT JASMINE 
     * A task for testing
     * run jshint, jasmine
     */
    grunt.registerTask('jasmine', [
        //'jshint',
        'jasmine'
    ]);
    

    /**
     * GRUNT TRAVIS
     * A task for Travis CI to test build
     * run bower install, uglify and sass
     */
    grunt.registerTask('travis', [
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
        //'bower:install',
        'uglify',
        'sass:release',
        'autoprefixer:release',
        'combine_mq:release',
        'csssplit:release',
        'csso:release',
        'sf2-console:assetic_dump_dev',
        'sf2-console:assetic_dump_prod'
    ]);
};