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
};
