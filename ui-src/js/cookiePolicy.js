/*
 Project: BardisCMS
 Authors: George Bardis
 */

// Create a closure to maintain scope of the '$' and CMS
(function (CMS, $, window, document, undefined) {
    CMS.cookiePolicy = function () {
        /**
         * Test if user has been to site before and accepted cookies
         * If so, keep message hidden
         * If not, keep visible and allow user to accept
         */
        var $cookiePolicyEl = $('#cookie-acceptance');

        if (CMS.cookies.getItem('cookies-agreed') !== 'true') {
            $cookiePolicyEl.removeClass('is-hidden');

            $cookiePolicyEl.find('.accept-button').on('click', function (e) {
                CMS.cookies.setItem('cookies-agreed', 'true', Infinity, '/');
            });
        }
    };

})(window.CMS = window.CMS || {}, jQuery, window, document);
