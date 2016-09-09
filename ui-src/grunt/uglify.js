// -----------------------------
// Config Uglify
// https://github.com/gruntjs/grunt-contrib-uglify
// Minifies and concatenates your JS
// -----------------------------

module.exports = {
    dev: {
        options: {
            mangle: false, // mangle: Turn on or off mangling
            beautify: true, // beautify: beautify your code for debugging/troubleshooting purposes
            compress: false,
            screwIE8: true,
            sourceMap: true,
            sourceMapIn: '<%=config.js.releaseDir%><%=config.js.releaseFile%>.map',
            report: 'none'
        },
        files: {
            '<%=config.js.releaseDir%><%=config.js.releaseFile%>': ['<%=config.js.releaseDir%><%=config.js.releaseFile%>']
        }
    },
    release: {
        options: {
            mangle: true, // mangle: Turn on or off mangling
            beautify: false, // beautify: beautify your code for debugging/troubleshooting purposes
            compress: {
                sequences: true,
                dead_code: true,
                conditionals: true,
                booleans: true,
                unused: true,
                if_return: true,
                join_vars: true,
                drop_console: true
            },
            screwIE8: true,
            sourceMap: true,
            sourceMapIn: '<%=config.js.releaseDir%><%=config.js.releaseFile%>.map',
            report: 'none'
        },
        files: {
            '<%=config.js.releaseDir%><%=config.js.releaseFile%>': ['<%=config.js.releaseDir%><%=config.js.releaseFile%>']
        }
    }
};
