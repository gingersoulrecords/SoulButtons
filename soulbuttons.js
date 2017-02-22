// initalize tracking of buttons
jQuery(function($){
	$('.soulbuttons.soulbuttons-track').on( 'click', function(e) {
		// detect if ga is present
    if ( typeof( ga ) == 'undefined' && typeof( __gaTracker ) == 'undefined' ) {
      return true;
    }
		// choose between ga and __gaTracker
    var tracker_var = 'ga';
    if ( typeof( __gaTracker ) !== 'undefined' ) {
      tracker_var = '__gaTracker';
    }
		// get button name
		var button =  $(this).attr('data-ga');
		// send event to GAs
    window[tracker_var]( 'send', 'event', 'button', button );
	});
});
