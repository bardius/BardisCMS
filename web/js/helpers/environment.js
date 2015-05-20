/*	Author:  */

;
(function (CMS) {

    CMS.environment = {
        //mobile or desktop compatible event name, to be used with '.on' function
        TOUCH_DOWN_EVENT_NAME: 'mousedown touchstart',
        TOUCH_UP_EVENT_NAME: 'mouseup touchend',
        TOUCH_MOVE_EVENT_NAME: 'mousemove touchmove',
        TOUCH_DOUBLE_TAB_EVENT_NAME: 'dblclick dbltap',
        isAndroid: function () {
            return navigator.userAgent.match(/Android/i);
        },
        isBlackBerry: function () {
            return navigator.userAgent.match(/BlackBerry/i);
        },
        isIOS: function () {
            return navigator.userAgent.match(/iPhone|iPad|iPod/i);
        },
        isOpera: function () {
            return navigator.userAgent.match(/Opera Mini/i);
        },
        isWindows: function () {
            return navigator.userAgent.match(/IEMobile/i);
        },
        isMobile: function () {

            if (CMS.environment.isAndroid() || CMS.environment.isBlackBerry() || CMS.environment.isIOS() || CMS.environment.isOpera() || CMS.environment.isWindows()) {
                return true;
            }
            return false;
        }
    };

})(window.CMS = window.CMS || {});
