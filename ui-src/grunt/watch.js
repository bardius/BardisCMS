// -----------------------------
// Config Watch
// https://github.com/gruntjs/grunt-contrib-watch
// Watches your scss, js, hbs etc for changes and compiles them
// -----------------------------

module.exports = {
    grunt: {
        files: ['Gruntfile.js']
    },
    scss: {
        files: ['<%= config.scss.path %>/**/*.scss'],
        tasks: ['sass:dev', 'autoprefixer:release', 'sf2-console:assetic_dump_dev']
    },
    js: {
        files: ['<%=config.js.scriptFileList%>', '<%=config.js.babelScriptFileList%>', 'Gruntfile.js'],
        tasks: ['jsdev', 'sf2-console:assetic_dump_dev']
    },
    livereload: {
        options: {
            livereload: false
        },
        files: ['<%= config.css.path %>/*.css']
    }
};
