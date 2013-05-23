var _Ix = 1;

jQuery(function () {
    $("#slider").chopSlider({
        /* Slide Element */
        slide: ".slide",
        /* Controlers */
        nextTrigger: "a#slide-next",
        prevTrigger: "a#slide-prev",
        hideTriggers: true,
        sliderPagination: ".slider-pagination",
        /* Captions */
        useCaptions: true,
        everyCaptionIn: ".sl-descr",
        showCaptionIn: ".caption",
        captionTransform: "scale(0) translate(-600px,0px) rotate(45deg)",
        /* Autoplay */
        autoplay: true,
        autoplayDelay: 4000,
        /* Default Parameters */
        t2D: csTransitions['vertical'][0],
        t3D: false,
        /* For Mobile Devices */
        mobile: csTransitions['mobile']['random'],
        /* For Old and IE Browsers */
        noCSS3: csTransitions['noCSS3']['random'],
        onStart: function () { /* Do Something*/

            },
            onEnd: function () { /* Do Something*/ 
            
            if (_Ix == 1) {
                $.chopSlider.redefine({ t2D: csTransitions['multi'][3], t3D: false }); 
                _Ix += 1;
            }
            else if (_Ix == 2) {
                $.chopSlider.redefine({ t2D: csTransitions['multi'][22], t3D: false }); 
                _Ix += 1;
            }
            else if (_Ix == 3) {
                $.chopSlider.redefine({ t2D: csTransitions['vertical'][13], t3D: false });
                _Ix += 1;
            }
            else if (_Ix == 4) {
            $.chopSlider.redefine({ t2D: csTransitions['vertical'][15], t3D: false });
            _Ix = 1;
            }

            }
    })

})

