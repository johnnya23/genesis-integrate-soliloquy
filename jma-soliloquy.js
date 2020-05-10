jQuery(document).ready(function($) {
    $window = $(window);

    function fix_soliloquy_elements() {
        window_width = $window.width();
        $jma_header_image_wrap = $('.jma-header-image-wrap');
        available_height = $.isNumeric($('body').data('available_height')) ? $('body').data('available_height') : $jma_header_image_wrap.height();

        //width_val = $('body').hasClass('jma-stack-991') ? 12 : 7;
        if ($('#dont-edit-this-element').css('z-index') > 12) {

            $jma_header_image_wrap.find('.soliloquy-caption').css({
                'width': window_width + 'px',
                'height': available_height
            });
            $jma_header_image_wrap.find('.soliloquy-controls-direction').css({
                'width': window_width + 'px'
            });
            $jma_header_image_wrap.find('.soliloquy-pager').css({
                'bottom': (($jma_header_image_wrap.height() - available_height) / 2 + 10) + 'px'
            });

        } else {
            $jma_header_image_wrap.find('.soliloquy-caption').css({
                'width': '',
                'height': ''
            });

            $jma_header_image_wrap.find('.soliloquy-controls-direction').css('width', '');
            $('.soliloquy-caption').css({
                'width': '',
                'height': ''
            });
            $jma_header_image_wrap.find('.soliloquy-pager').css({
                'bottom': ''
            });
        }
    }

    /* keeps the captions from showing before the slider is repositioned */
    function show_coptions() {
        $('.soliloquy-item').removeClass('jma-dynamic-slide-hidden');
    }

    /* on load we dont run fix elements until the slider nav is loaded  */
    function solIsLoaded() {

        fix_soliloquy_elements();

        //this is what delays caption display
        $.when(fix_soliloquy_elements()).done(function() {
            show_coptions();
        });

    }

    // set up the mutation observer
    var observer = new MutationObserver(function(mutations, bigheaderme) {
        // `mutations` is an array of mutations that occurred
        // `me` is the MutationObserver instance
        var canvas = $('.soliloquy-controls-direction').length;
        if (canvas) {
            solIsLoaded();
            bigheaderme.disconnect(); // stop observing
            return;
        }
    });

    // start observing
    observer.observe(document, {
        childList: true,
        subtree: true
    });

    $window.resize(function() {
        fix_soliloquy_elements();
    });

});