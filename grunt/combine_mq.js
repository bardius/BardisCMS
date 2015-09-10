// -----------------------------
// Config Combine Media Queries
// https://github.com/frontendfriends/grunt-combine-mq
// Combine matching media queries into one media query definition.
// -----------------------------

module.exports = {
    release: {
        options: {
            beautify: false
        },
        src: '<%= config.css.path %>/styles.css',
        dest: '<%= config.css.path %>/styles.css'
    }
};
