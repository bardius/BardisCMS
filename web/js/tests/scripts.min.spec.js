/*
 Project: BardisCMS
 Authors: George Bardis
 */

// Create the tests for the JavaScript functionality

/*
 * Testing the functionality of the Project welcome
 */
describe('BardisCMS Tests', function () {
    var projectWelcomeTest;

    beforeEach(function () {
        projectWelcomeTest = BARDIS.sampleTest.simpleTest('BardisCMS');
    });

    it('Project should say welcome', function () {
        expect(projectWelcomeTest).toEqual('Skeleton Project is starting. Welcome!');
    });
});