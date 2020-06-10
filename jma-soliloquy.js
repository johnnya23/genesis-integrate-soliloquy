jQuery(document).ready(function($) {

    $window = $(window);

    if (($('.jma-gbs-mobile-panel').css('display') == 'none') && ($window.width() > $window.height())) {
        $('.inner-visual').removeClass('normal-width');
    }
    var featured_display_ratio = 0;
    if ($('.jma-sol-featured-display').length) {
        var $featured_display = $('.jma-sol-featured-display');
        featured_display_orig_height = parseInt($featured_display.css('height'), 10);
        featured_display_ratio = $featured_display.height() / $featured_display.width();
    }

    function fix_soliloquy_elements() {
        //width_val = $('body').hasClass('jma-stack-991') ? 12 : 7;

        if (($('.jma-gbs-mobile-panel').css('display') == 'none') && ($window.width() > $window.height())) {
            $('.inner-visual').removeClass('normal-width');
            $('.inner-visual').css('max-height', featured_display_orig_height + 'px');
            if (featured_display_ratio) {
                $target = $featured_display.closest('.inner-visual');
                target_ratio = $target.height() / $target.width();
                if (target_ratio < featured_display_ratio) {
                    //target is taller than image use 100% width and left
                    //top and bottom overflow and adjust dots
                    $featured_display.css({
                        'width': '100%',
                        'height': ($target.width() * featured_display_ratio) + 'px'
                    });
                    bottom = (((($target.width() * featured_display_ratio) - $target.height()) / 2) + 20);
                    $featured_display.find('.soliloquy-pager').css('bottom', bottom + 'px');
                    $featured_display.find('.soliloquy-controls-direction').css({
                        'width': '100%'
                    });
                    $featured_display.find('.soliloquy-caption').css({
                        'width': '80%',
                        'height': ($target.height() - 40) + 'px'
                    });
                } else {
                    //target is wider than image use 100% height and left
                    //sides overflow and adjust arrows
                    $featured_display.css({
                        'height': $target.height() + 'px',
                        'width': $target.height() * (1 / featured_display_ratio) + 'px'
                    });
                    $featured_display.find('.soliloquy-controls-direction').css({
                        'width': $target.width() + 'px'
                    });
                    $featured_display.find('.soliloquy-caption').css({
                        'width': ($target.width() - 120) + 'px',
                        'height': ($target.height() - 40) + 'px'
                    });
                }
            }
        } else {
            $('.inner-visual').addClass('normal-width');
        }
    }

    /* on load we dont run fix elements until the slider nav is loaded  */
    function solIsLoaded() {

        fix_soliloquy_elements();

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

    $window.resize(function(e) {
        if ($('.jma-sol-featured-display').length)
            fix_soliloquy_elements();
    });

});