// initalize tracking of buttons
jQuery(function($){
	$('.soulbutton.soulbutton-track').on( 'click', function(e) {
    if ( typeof( ga ) == 'undefined' && typeof( __gaTracker ) == 'undefined' ) {
      return true;
    }
    var tracker_var = 'ga';
    if ( typeof( __gaTracker ) !== 'undefined' ) {
      tracker_var = '__gaTracker';
    }
		var button =  $(this).attr('data-ga');
    window[tracker_var]( 'send', 'event', 'button', button );
	});
});
