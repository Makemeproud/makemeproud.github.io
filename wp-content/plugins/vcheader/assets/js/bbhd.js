(function($) {
    'use strict';

    $('document').ready(function() {
        // Megamenu
        $('.bbhd-mega-menu').each(function( index ) {
            var $self = $( this );

            if($self.closest('.bbhd-menu-mobile-wrap').length > 0) {
                return true;
            }

            var $menuParent = $self.closest('li'),
                $header = $self.closest('.bb-header-inside'),
                sw = ($self.outerWidth() /2) - ($menuParent.outerWidth() /2);
            var mrLeft = $menuParent.offset().left - $header.offset().left;

            //check left side
            if(sw < mrLeft) {
                mrLeft = sw;
            }

            // check right side
            var rHeader = $header.offset().left + $header.outerWidth(),
                rMegamenu = $self.offset().left + $self.outerWidth();
            if( (rMegamenu - mrLeft) > rHeader) {
                mrLeft = rMegamenu - rHeader;
            }

            // set pos
            $self.css({marginLeft: - (mrLeft)});
        });


        // Search
        $('.bbhd-btn-search').on('click', function(){
            var $target = $($(this).attr('href'));
            $target.addClass('bbhd-visible');
            return false;
        });
        $('.bbhd-search-close').on('click', function(){
            var $self = $( this );
            var $target = $self.closest('.bbhd-search-box');
            $target.removeClass('bbhd-visible');
            return false;
        });

        // Minicart
        var $bbhd_mini_cart = $('.bbhd-mini-cart');
        $bbhd_mini_cart.on('click', function(e) {
            $(this).closest('.bbhd-mini-cart-wrap').addClass('open');
        });
        $('.bbhd-mini-cart-wrap').on('click', function(e) {
            $(this).addClass('open');
        });

        $(document).on('click', function(e) {
            if ( ( $( e.target ).closest( '.bbhd-mini-cart-wrap' ).length == 0 ) && ( $( e.target ).closest( '.bbhd-mini-cart' ).length == 0 ) ) {
                if ( $('.bbhd-mini-cart-wrap').hasClass( 'open' ) ) {
                    $('.bbhd-mini-cart-wrap').removeClass('open');
                }
            }
            if ( ( $( e.target ).closest( '.bbhd-menu-canvas-wrap.style-dropdown' ).length == 0 ) && ( $( e.target ).closest( '.bbhd-open-menucanvas' ).length == 0 ) ) {
                if ( $('.bbhd-menu-canvas-wrap').hasClass( 'open' ) ) {
                    $('.bbhd-menu-canvas-wrap').removeClass('open');
                }
            }
        });

        // Menu canvas
        $('.bbhd-menu-canvas-wrap .bbhd-open-menucanvas').on('click', function(){
            var $self = $( this );
            $self.closest('.bbhd-menu-canvas-wrap').addClass('open');
            return false;
        });
        $('.bbhd-menu-canvas-wrap .bbhd-close-menucanvas').on('click', function(){
            var $self = $( this );
            $self.closest('.bbhd-menu-canvas-wrap').removeClass('open');
            return false;
        });

        // Menu mobile
        var $bbhdMenu = $('.bbhd-menu-mobile-wrap');

        $bbhdMenu.find('.bb-dropdown-menu-toggle').on('click', function(e) {
            var subMenu;
            if($(this).closest('li').find('.bb-dropdown-menu').length > 0) {
                subMenu = $(this).closest('li').find('.bb-dropdown-menu');
                if (subMenu.css('display') == 'block') {
                    subMenu.css('display', 'block').slideUp().parent().removeClass('expand');
                } else {
                    subMenu.css('display', 'none').slideDown().parent().addClass('expand');
                }
            }
            e.stopPropagation();
        });
        $bbhdMenu.find('.bbhd-close-mm-mobile').on('click', function(e) {
            $bbhdMenu.removeClass('open');
            e.stopPropagation();
        });
        $bbhdMenu.find('.bbhd-open-menu-mobile').on('click', function(e) {
            $(this).closest('.bbhd-menu-mobile-wrap').addClass('open');
            e.stopPropagation();
        });
        $('.bbhd-menu-mobile-wrap .bbhd-header-menuside a').on('click', function(e) {
            if($(this).attr('href').indexOf('#') != -1) {
                $(this).closest('.bbhd-menu-mobile-wrap').removeClass('open');
                e.stopPropagation();
            }
        });

        // Sticky menu
        $('.bbhd-sticky').each(function( index ) {
            var $self = $( this );
            if($('#wpadminbar').length > 0 && $('#wpadminbar').css('position') == 'fixed') {
                $self.sticky({ topSpacing: parseFloat($('html').css('marginTop')) });
            }
            else {
                $self.sticky({ topSpacing: 0 });
            }

            if($self.hasClass('bbhd-hide-on-desktop')) {
                $self.closest('.sticky-wrapper').addClass('bbhd-hide-on-desktop');
            }
            if($self.hasClass('bbhd-hide-on-mobile')) {
                $self.closest('.sticky-wrapper').addClass('bbhd-hide-on-mobile');
            }

            if($self.hasClass('bbhd-overlay')) {
                $self.closest('.sticky-wrapper').addClass('bbhd-overlay');
            }

        });

        // Auto add header
        var $bb_header = $('.bb-auto-add-header');
        if ($bb_header.length > 0) {
            $('body').prepend($bb_header[0]);
        }

    });


})(jQuery);
