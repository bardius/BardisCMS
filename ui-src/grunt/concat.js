// -----------------------------
// Config Concat
// https://github.com/gruntjs/grunt-contrib-concat
// Concatenates your JS
// -----------------------------

module.exports = {
    js: {
        options: {
            sourceMap: true
        },
        files: {
            '<%=config.js.releaseDir%><%=config.js.releaseFile%>': '<%=config.js.scriptFileList%>'
        }
    }
};
