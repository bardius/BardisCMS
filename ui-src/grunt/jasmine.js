// -----------------------------
// Config Jasmine
// https://github.com/gruntjs/grunt-contrib-jasmine
// Run Jasmine unit tests
// -----------------------------

module.exports = {
    tests: {
        src: '<%=config.js.releaseDir%>/*.js',
        options: {
            vendor: [
                'web/bower_components/jquery/jquery.min.js',
                'web/bower_components/jasmine-jquery/lib/jasmine-jquery.js'
            ],
            specs: '<%= config.jstests.path %>/*.spec.js'
        }
    }
};
