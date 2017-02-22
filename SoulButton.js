// remap ga to __gaTracker
// __gaTracker( function() {
//   window.ga = __gaTracker;
// } );

// initalize tracking of buttons
jQuery(function($){
	$('.soulbutton.soulbutton-track').on( 'click', function(e) {
		if (typeof( ga ) !== 'undefined') {
			var button =  $(this).attr('data-ga');
      ga( 'send', 'event', 'button', button );
		}
	});
});
