// -----------------------------
// Config PostCSS
// https://github.com/nDmitry/grunt-postcss
// Postprocessing of less files with autoprefixer
// -----------------------------

module.exports = {
    release: {
        options: {
            processors: [
                require('autoprefixer-core')({
                    // We are supporting the last 2 versions,
                    // any browsers with >2% market share,
                    // and ensuring we support 8 and 9 with prefixes
                    browsers: ['last 2 versions', 'ie 8', 'ie 9', '> 2%'],
                    map: false
                })
            ]
        },
        dist: {
            src: '<%= config.css.path %>/styles.css'
        }
    }
};
