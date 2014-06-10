module.exports = function(grunt) {
	
	'use strict';
	
	/**
	* Project configuration
	*/
	grunt.initConfig({
		
		pkg: require('./package'), // <%=pkg.name%> grunt.file.readJSON('package.json'),
		
		/**
		* Config - Edit this section
		* ==========================
		* Choose javascript dist filename
		* Choose javascript dist location
		* Choose javascript files to be uglified
		* Choose css dist filename
		* Choose css dist location
		* Choose scss files to be compiled
		*/
		config : {
			
			js : {
				// <%=config.js.distDir%>
				distDir : 'web/js/',

				// <%=config.js.distFile%>
				distFile : 'app.min.js',

				// <%=config.js.fileList%>
				fileList : [
					'web/bower_components/foundation/js/foundation.min.js',
					//'web/bower_components/foundation/js/foundation.js',
					//'web/bower_components/foundation/js/foundation/*.js',
					'web/bower_components/jquery-ias/src/jquery-ias.js',
					'web/bower_components/jquery-ias/src/callbacks.js',
					'web/bower_components/jquery-ias/src/extension/spinner.js',
					'web/bower_components/jquery-ias/src/extension/noneleft.js',
					'web/js/onDomReady.js'
				]
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
				files: ['web/scss/**/*.scss'],
				tasks: ['sass:dist', 'autoprefixer:dist']
			},			
			
			js: {
				files: ['<%=config.js.fileList%>', 'Gruntfile.js'],
				tasks: ['uglify']
			}
		},
		

		/**
		* Sass compilation
		* https://github.com/gruntjs/grunt-contrib-sass
		* Includes kickoff.scss and kickoff-old-ie.scss by default
		* Also creates source maps
		*/		
		sass: {
			
			dist: {
			
				options: {
					loadPath: ['web/bower_components/foundation/scss'],				
					unixNewlines: true,
					style: 'expanded', //compressed - expanded
					lineNumbers: false,
					debugInfo : false,
					precision : 8,
					sourcemap: false
				},

				files: {
					'web/css/app.css' : 'web/scss/app.scss',
					'web/css/legacy.css': 'web/scss/legacy.scss'
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
			
			dist: {
			
				options: {
					// Task-specific options go here - we are supporting
					// the last 2 browsers, any browsers with >1% market share,
					// and ensuring we support IE7 + 8 with prefixes
					browsers: ['> 5%', 'last 4 versions', 'firefox > 3.6', 'ie > 6'],
					map: true
				},

				files: {
					'web/css/app.css' : 'web/css/app.css',
					'web/css/legacy.css': 'web/css/legacy.css'
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
				sourceMap: '<%=config.js.distDir%><%=config.js.distFile%>.map',
				sourceMappingURL: '/<%=config.js.distFile%>.map'
			},
			
			js: {
				src: '<%=config.js.fileList%>',
				dest: '<%=config.js.distDir%><%=config.js.distFile%>'
			}
		},
		
		
		/**
		* CSSO
		* https://github.com/t32k/grunt-csso
		* Minify CSS files with CSSO
		*/
		csso: {
			
			dist: {
			
				files: {
					'web/css/app.css' : 'web/css/app.css',
					'web/css/legacy.css': 'web/css/legacy.css'
				}
			}
		},		
		

		/**
		* JSHint
		* https://github.com/gruntjs/grunt-contrib-jshint
		* Manage the options inside .jshintrc file
		*/
		jshint: {
			
			all: '<%=config.js.fileList%>',
			
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
			
			src: '<%=config.js.fileList%>',
			
			options: {
				config: ".jscs.json"
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
					targetDir: "./web/bower_components",
					install: true
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
   
   /* ==========================================================================
	Available tasks:
	* grunt : run jshint, uglify and sass
	* grunt watch : run sass, uglify
	* grunt dev : run jshint, uglify and sass
	* grunt deploy : run jshint, uglify, sass and csso
	* grunt first_deployment : 
	* grunt deployment_prod : 
	* grunt deployment_prod : 
	* grunt availabletasks : view all available tasks
	========================================================================== */

	/**
	* GRUNT * Default task
	* run jshint, uglify and sass
	*/
	// Default task
	grunt.registerTask('default', [
		//'jshint',
		'uglify',
		'sass:dist',
		'autoprefixer:dist',
		'sf2-console:assetic_dump_dev'
	]);
	
	// Deployment to Dev task
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
		'bower:install',
		'uglify',
		'sass:dist',
		'autoprefixer:dist',
		'csso:dist',
		'sf2-console:assetic_dump_dev',
		'sf2-console:assetic_dump_prod'
	]);
	
	// Deployment to Prod task
	grunt.registerTask('deployment_prod', [
		'sf2-console:cache_clear_prod',
		'sf2-console:cache_warmup_prod',
		'uglify',
		'sass:dist',
		'autoprefixer:dist',
		'csso:dist',
		'sf2-console:assetic_dump_prod'
	]);


	/**
	* GRUNT DEV * A task for development
	* run jshint, uglify and sass
	*/
	grunt.registerTask('dev', [
		//'jshint',
		'uglify',
		'sass:dist',
		'autoprefixer:dist',
		'sf2-console:assetic_dump_dev'
	]);


	/**
	* GRUNT DEPLOY * A task for your production environment
	* run jshint, uglify and sass:production
	*/
	grunt.registerTask('deploy', [
		'uglify',
		'sass:dist',
		'autoprefixer:dist',
		'csso:dist',
		'sf2-console:assetic_dump_dev',
		'sf2-console:assetic_dump_prod'
	]);

	//Travis CI to test build
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
		//'jshint',
		'uglify',
		'sass:dist',
		'autoprefixer:dist',
		'csso:dist',
		'sf2-console:assetic_dump_dev',
		'sf2-console:assetic_dump_prod'
	]);
};