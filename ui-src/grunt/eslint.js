// -----------------------------
// Config eslint
// https://github.com/sindresorhus/grunt-eslint
// Check eslint rules on the js files
// Manage the options inside .eslintrc file
// -----------------------------

module.exports = {
    target: [
        'gruntFile.js',
        '<%=config.js.scriptFileList%>'
    ],
    options: {
        configFile: '.eslintrc',
        reset: true,
        format: 'stylish' //https://github.com/eslint/eslint/tree/master/lib/formatters
    }
};
