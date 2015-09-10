// -----------------------------
// Config CSSO
// https://github.com/t32k/grunt-csso
// Minify CSS files with CSSO
// -----------------------------

module.exports = {
    release: {
        files: {
            '<%= config.css.path %>/styles.css': '<%= config.css.path %>/styles.css',
            '<%= config.css.path %>/styles_bundle_1.css': '<%= config.css.path %>/styles_bundle_1.css',
            '<%= config.css.path %>/styles_bundle_2.css': '<%= config.css.path %>/styles_bundle_2.css'
        }
    }
};
