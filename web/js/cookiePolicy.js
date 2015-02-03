/*  Author:  */

;
(function (BARDIS, $) {
    BARDIS.cookiePolicy = function () {
        console.debug('BARDIS.cookiePolicy is running');
        /**
         * Test if user has been to site before and accepted cookies
         * If so, keep message hidden
         * If not, keep visible and allow user to accept
         */

        var $cookiePolicyEl = $('.cookiePolicy');

        if (BARDIS.cookies.getItem('cookies-agreed') !== 'true') {
            $cookiePolicyEl.removeClass('is-hidden');

            $('.agreeBtn').on('click', function (event) {
                event.preventDefault();
                BARDIS.cookies.setItem('cookies-agreed', 'true');
                $cookiePolicyEl.remove();
            });
        }
    };
})(window.BARDIS = window.BARDIS || {}, jQuery);
