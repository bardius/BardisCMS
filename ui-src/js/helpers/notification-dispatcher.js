
var notifications = window.notifications || {};

(function ($, f) {

    var events = {
        eventListeners: [],
        addListener: function (type, handler, destroyOnUse) {

            if (!events.listenerExists(type, handler)) {
                events.eventListeners.push({
                    destroyOnUse: destroyOnUse,
                    handler: handler,
                    type: type
                });
            }
        },
        listenerExists: function (type, handler) {
            var listener = {};

            for (var i = 0, n = events.eventListeners.length; i < n; i += 1) {
                listener = events.eventListeners[i];

                if (type === listener.type && handler === listener.handler) {
                    return true;
                }
            }

            return false;
        },
        removeListener: function (type, handler) {
            var listener = {};

            for (var i = 0, n = events.eventListeners.length; i < n; i += 1) {
                listener = events.eventListeners[i];

                if (type === listener.type && handler === listener.handler) {
                    events.eventListeners.splice(i, 1);

                    return;
                }
            }
        },
        sendNotification: function (type, params) {
            var listener = {};
            var handler;

            for (var i = events.eventListeners.length - 1; i >= 0; i -= 1) {
                listener = events.eventListeners[i];

                if (type === listener.type) {
                    handler = listener.handler;

                    if (listener.destroyOnUse) {
                        events.removeListener(listener.type, listener.handler);
                    }

                    handler(params);
                }
            }
        }
    };

    // Public interface
    window.notifications = {
        sendNotification: function (type, params) {
            events.sendNotification(type, params);
        },
        addListener: function (type, handler, destroyOnUse) {
            if (destroyOnUse !== true) {
                destroyOnUse = false;
            }

            events.addListener(type, handler, destroyOnUse);
        },
        removeListener: function (type, handler) {
            events.removeListener(type, handler);
        },
        WINDOW_RESIZE: 'WINDOW_RESIZE'
    };


})(window.CMS = window.CMS || {}, jQuery);
