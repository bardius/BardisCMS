/**
 * Config Autoprefixer
 * https://github.com/nDmitry/grunt-autoprefixer
 * https://github.com/ai/autoprefixer
 * Auto prefixes your CSS using caniuse data
 */
module.exports = {
    release: {
        options: {
            // Task-specific options go here - we are supporting
            // the last 2 browsers, any browsers with >1% market share,
            // and ensuring we support IE7 + 8 with prefixes
            browsers: [
                '> 2%',
                'last 3 versions',
                'ie > 7',
                'iOS > 6',
                'Android > 3'
            ],
                map: true
        },
        files: {
            '<%= config.css.path %>/styles.css' : '<%= config.css.path %>/styles.css'
        }
    }
};
