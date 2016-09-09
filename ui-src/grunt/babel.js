// -----------------------------
// Config Babel
// https://github.com/babel/grunt-babel
// Allow ES2015 to be transpiled to Ecma5
// -----------------------------

module.exports = function(grunt) {
    return {
        release: {
            options: {
                //sourceMap: true,
                //inputSourceMap: grunt.file.readJSON('web/js/release/scripts.min.js.map'),
                babelrc: true,
                compact: false,
                presets: ['es2015']
            },
            src: [
                '<%=config.js.releaseDir%><%=config.js.releaseFile%>'
            ],
            dest: '<%=config.js.releaseDir%><%=config.js.releaseFile%>'
        }
    }
};
