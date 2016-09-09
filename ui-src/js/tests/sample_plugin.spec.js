/*
 Project: Project Name
 Authors: George Bardis
 */

// Create the tests for the JavaScript functionality

/*
 * Setting the JasminejQuery fixtures path
 */
//jasmine.getFixtures().fixturesPath = 'public_html/js/tests/fixtures';
/*
 * Testing the functionality of the sample DOM manipulation 
 * functionality from a jQuery plugin
 */

describe('Skeleton Project Sample Plugin Tests', function () {
    var elem;
    //var fixture;

    beforeEach(function () {
        //fixture = loadFixtures('index.html');
        elem = $('<h1>Markup Sample</h1>');
    });

    it('should add default classes to the element', function () {
        elem.sample_plugin();
        expect(elem).toHaveClass('default classes');
    });

    it('should add default text to the element', function () {
        elem.sample_plugin();
        expect(elem).toHaveText('default text');
    });

    it('should add add custom classes to the element', function () {
        elem.sample_plugin({'classes': 'my custom classes'});
        expect(elem).toHaveClass('my custom classes');
    });

    it('should add add custom text to the element', function () {
        elem.sample_plugin({'text': 'Hello'});
        expect(elem).toHaveText('Hello');
    });
});