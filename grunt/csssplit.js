// -----------------------------
// Config csssplit
// https://github.com/project-collins/grunt-csssplit
// Auto splits css to multiple files as IE9 has a per file css rule limit of 4096
// -----------------------------

module.exports = {
    release: {
        src: ['<%= config.css.path %>/styles.css'],
        dest: '<%= config.css.path %>/styles.css',
        options: {
            suppressSinglePage: false,
            maxSelectors: 4095,
            maxPages: 1,
            suffix: '_bundle_'
        }
    }
};
