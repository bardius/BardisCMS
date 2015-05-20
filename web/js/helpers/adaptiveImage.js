/*	Adaptive Image method
 :	-------------------------
 :	Borrows heavily from Scott Jehls picturefill.js
 :	https://github.com/scottjehl/picturefill
 :
 :	but is structured differently in HTML and slight tweaks to work a bit 'tidier'
 :	with respect to drupal and the adaptive image module
 :
 :
 :	Function List
 :	1. init
 :	2. adaptiveChecker
 ********************************************************/
;
(function (CMS) {
    CMS.AdaptiveImage = {
        checkForPixelDensity: false,
        pixelDensity: '',
        init: function (w, px) {

            //set whether we bother setting for retina devices
            this.checkForPixelDensity = px;
            if (this.checkForPixelDensity) {
                this.pixelDensity = this.getPixelRatio();
            }

            // Run on resize and domready (w.load as a fallback)
            if (w.addEventListener) {
                w.addEventListener("DOMContentLoaded", function () {
                    CMS.AdaptiveImage.checkAdaptiveElements();
                    // Run once only
                    w.removeEventListener("load", CMS.AdaptiveImage.checkAdaptiveElements, false);
                }, false);
                w.addEventListener("load", CMS.AdaptiveImage.checkAdaptiveElements, false);
            } else if (document.attachEvent) {
                w.attachEvent("onload", function () {
                    CMS.AdaptiveImage.checkAdaptiveElements();
                });
            }

        },
        //finds all div elements on the page with the adaptive-image data attribute set, or can be passed a subset of elements
        checkAdaptiveElements: function (elements) {

            var ps = elements || window.document.getElementsByTagName("div");// list of all div elements on our page

            // Loop through each div which has the data-adaptive attribute and isn't hidden
            for (var i = 0, il = ps.length; i < il; i++) {
                if (ps[ i ].getAttribute("data-adaptive") !== null &&
                        ps[ i ].style.display !== 'none') {

                    this.loadImage(ps[ i ]);
                }
            }
        },
        loadImage: function (element) {

            var selected_breakpoint = 'max', // used to store which breakpoint image to load - reset to max
                    breakpoints = element.getAttribute("data-adaptive-image-breakpoints"); // stores the specified breakpoints to choose between, defined on the data attribute data-adaptive-image-breakpoints

            //if breakpoints exists
            if (breakpoints) {
                //split the values
                breakpoints = breakpoints.split(' ');

                //and loop through the resulting breakpoint array to find the most appropriate breakpoint to use
                for (var j = 0, br = breakpoints.length; j < br; j++) {
                    if (document.documentElement.clientWidth <= Number(breakpoints[j]) &&
                            (selected_breakpoint == 'max' || Number(breakpoints[j]) < Number(selected_breakpoint))) {

                        selected_breakpoint = breakpoints[j];

                    }
                }
            }

            //get the image path for the correct breakpoint
            imgPath = element.getAttribute('data-img-' + selected_breakpoint);

            // Find any existing img element in the adaptive element
            var picImg = element.getElementsByTagName("img")[ 0 ];

            if (imgPath) {
                if (!picImg) {
                    picImg = document.createElement("img");
                    picImg.alt = element.getAttribute("data-alt");
                    element.appendChild(picImg);
                }

                //check whether to add pixelDensity into the filename for retina images
                if (this.checkForPixelDensity === true && this.pixelDensity !== '') {
                    var imgName = imgPath.substring(0, imgPath.lastIndexOf('.')),
                            imgExt = imgPath.substring(imgPath.lastIndexOf('.'), imgPath.length);

                    imgPath = imgName + this.pixelDensity + imgExt;
                }

                //and set the imgPath of the image
                picImg.src = imgPath;
            } else if (picImg) {
                element.removeChild(picImg);
            }
        },
        getPixelRatio: function () {

            if (window.devicePixelRatio > 1) {
                return '-2x';
            }
            return '';
        }
    };
})(window.CMS = window.CMS || {});
