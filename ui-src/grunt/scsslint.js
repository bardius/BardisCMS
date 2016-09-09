// -----------------------------
// Config SCSSLint
// https://github.com/ahmednuaman/grunt-scss-lint
// https://github.com/brigade/scss-lint/blob/master/lib/scss_lint/linter/README.md
// Check scss lint rules on the scss files
// -----------------------------

module.exports = {
    allFiles: [
        '<%= config.scss.path %>/_styles.scss'
    ],
    options: {
        bundleExec: false,
        //reporterOutput: 'scss-lint-report.xml',
        //reporterOutputFormat: 'xml'.
        config: '.scss-lint.yml',
        force: true,
        colorizeOutput: true
    }
};
