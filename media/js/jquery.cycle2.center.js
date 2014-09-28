/*! center plugin for Cycle2;  version: 20130731 */
(function($) {
"use strict";

$.extend($.fn.cycle.defaults, {
    centerHorz: false,
    centerVert: false
});

$(document).on( 'cycle-pre-initialize', function( e, opts ) {
    if ( !opts.centerHorz && !opts.centerVert )
        return;

    // throttle resize event
    var timeout, timeout2;

    $(window).on( 'resize orientationchange', resize );
    
    opts.container.on( 'cycle-destroyed', destroy );

    opts.container.on( 'cycle-initialized cycle-slide-added cycle-slide-removed', function( e, opts, slideOpts, slide ) {
        resize();
    });

    adjustActive();

    function resize() {
        clearTimeout( timeout );
        timeout = setTimeout( adjustActive, 50 );
    }

    function destroy( e, opts ) {
        clearTimeout( timeout );
        clearTimeout( timeout2 );
        $( window ).off( 'resize orientationchange', resize );
    }

    function adjustAll() {
        opts.slides.each( adjustSlide ); 
    }

    function adjustActive() {
        /*jshint validthis: true */
        adjustSlide.apply( opts.container.find( '.' + opts.slideActiveClass ) );
        clearTimeout( timeout2 );
        timeout2 = setTimeout( adjustAll, 50 );
    }

    function adjustSlide() {
        /*jshint validthis: true */
        var slide = $(this);
        var contW = opts.container.width();
        var contH = opts.container.height();
        slide.css({'width': '100%', 'height':'auto'});
        var w = slide.width();
        var h = slide.height();
        var newwidth = 100;
        
        if (opts.centerHorz && h < contH) {
            newwidth = parseInt((parseFloat(contH) / parseFloat(h)) * 1000) / 10;
            if (isNaN(newwidth)) {
                setTimeout(function() { adjustAll(); }, 250);
            }
            slide.css({'width': newwidth + '%', 'height':'100%'});
            var w = slide.width();
            var h = slide.height();
        }

        if (opts.centerHorz)
            slide.css( 'marginLeft', (contW - w) / 2 );
        if (opts.centerVert)
            slide.css( 'marginTop', (contH - h) / 2 );
    }
});

})(jQuery);
