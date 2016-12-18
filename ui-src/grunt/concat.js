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
    },
    vendorjs: {
        options: {
            sourceMap: true
        },
        files: {
            '<%=config.js.releaseDir%><%=config.js.vendorReleaseFile%>': '<%=config.js.vendorScriptFileList%>'
        }
    }
};
