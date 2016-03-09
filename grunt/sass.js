// -----------------------------
// Config Sass
// https://github.com/gruntjs/grunt-contrib-sass
// Compiling the css files from sass ones
// -----------------------------

module.exports = {
    release: {
        options: {
            loadPath: [
                '<%= config.f5scss.path %>',
                '<%= config.datepicker_scss.path %>'
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
            '<%= config.css.path %>/styles_bundle_2.css': '<%= config.css.path %>/styles_bundle_2.css',
            '<%= config.css.path %>/styles_bundle_1.css': '<%= config.css.path %>/styles_bundle_1.css'
        }
    },
    production: {
        options: {
            loadPath: [
                '<%= config.f5scss.path %>',
                '<%= config.datepicker_scss.path %>'
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
