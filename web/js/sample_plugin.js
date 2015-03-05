/*
 Plugin: Plugin Name
 Authors: George Bardis
 */

/**
 * A jQuery plugin to add a class name and some text to selected elements
 * 
 * Usage:
 * elem = $('.my-element').sample_plugin({ 'classes': 'my-custom-class', 'text': 'Sample' });
 */
(function ($) {
    var defaults = {
        'classes': 'default classes',
        'text': 'default text'
    };

    $.fn.sample_plugin = function (options) {
        options = $.extend(true, {}, defaults, options);

        return this.each(function () {
            $(this).addClass(options.classes).text(options.text);
        });
    };
})(jQuery);
