// -----------------------------
// Config JSHint
// https://github.com/gruntjs/grunt-contrib-jshint
// Check jshint rules on the js files
// Manage the options inside .jshintrc file
// -----------------------------

module.exports = {
    files: [
        'gruntFile.js',
        '<%=config.js.scriptFileList%>',
        '!web/bower_components/**/*.js'
    ],
    options: {
        jshintrc: '.jshintrc'
    }
};
