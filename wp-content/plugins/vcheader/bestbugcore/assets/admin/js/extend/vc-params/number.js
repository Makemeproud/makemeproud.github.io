(function($) {
	'use strict';
	
	function isInt( n ) {
		return n % 1 === 0;
	}

	$( '.plus, .minus' ).on( 'click', function() {

		// Get values
		var $number = $( this ).closest( '.bb-number' ).find( '.wpb_vc_param_value' ),
			currentVal = parseFloat( $number.val() ),
			max = parseFloat( $number.attr( 'max' ) ),
			min = parseFloat( $number.attr( 'min' ) ),
			step = $number.attr( 'step' );

		// Format values
		if ( !currentVal || currentVal === '' || currentVal === 'NaN' ) {
			currentVal = 0;
		}
		if ( max === '' || max === 'NaN' ) {
			max = '';
		}
		if ( min === '' || min === 'NaN' ) {
			min = 0;
		}
		if ( step === 'any' || step === '' || step === undefined || parseFloat( step ) === 'NaN' ) {
			step = 1;
		}

		// Change the value
		if ( $( this ).is( '.plus' ) ) {

			if ( max && ( max == currentVal || currentVal > max ) ) {
				$number.val( max );
			} else {

				if ( isInt( step ) ) {
					$number.val( currentVal + parseFloat( step ) );
				} else {
					$number.val( (currentVal + parseFloat( step )).toFixed( 1 ) );
				}
			}

		} else {

			if ( min && ( min == currentVal || currentVal < min ) ) {
				$number.val( min );
			} else if ( currentVal > 0 ) {
				if ( isInt( step ) ) {
					$number.val( currentVal - parseFloat( step ) );
				} else {
					$number.val( (currentVal - parseFloat( step )).toFixed( 1 ) );
				}
			}

		}

		// Trigger change event
		$number.trigger( 'change' );
	} );
}(window.jQuery));