// -----------------------------
// Config Sass
// https://github.com/gruntjs/grunt-contrib-sass
// Compiling the css files from sass ones
// -----------------------------

module.exports = {
    dev: {
        options: {
            loadPath: [
                '<%= config.f6scss.path %>',
                '<%= config.motionUIscss.path %>'
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
    release: {
        options: {
            loadPath: [
                '<%= config.f6scss.path %>',
                '<%= config.motionUIscss.path %>'
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
};
