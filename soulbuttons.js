// initalize tracking of buttons
jQuery(function($){
	$('.soulbuttons.soulbuttons-track').click( function(e) {
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

// initalize scrollTo
jQuery(function($){
	jQuery('.soulbuttons.soulbuttons-scrollto').click(function(e){
		var el = jQuery(this);
		console.log( el.attr( 'href' ) );
		if ( /^#/.test( el.attr( 'href' ) ) === true ) {
			e.preventDefault();
			TweenMax.to( window, el.data('scrollto'), { scrollTo: el.attr( 'href' ) } );
		}
	})
});

// initalize target effects for buttons
jQuery(function(){
	if( jQuery('html').hasClass( 'fl-builder-edit' ) ) {
		return false;
	}
	if( 'undefined' === typeof( TweenMax ) ) {
		return false;
	}

	window.tweenspeed = .4;
	var SoulButtonTargetEffects = {
		'fadeInFromCenter' : {

			'start': function( target ){

				//if there aren't any overlays
				if ( 1 > jQuery('#soulbuttons-backdrop').size() ) {

					//append the overlay to the body
					jQuery('body').append('<div id="soulbuttons-backdrop"></div>');

					//set overlay CSS
					TweenMax.set('#soulbuttons-backdrop',{
						position:'fixed',
						top:0,
						left:0,
						right:0,
						bottom:0,
						zIndex:99,
						backgroundColor:'rgba(0,0,0,0.5)',
						autoAlpha:0
					});

				}

				jQuery('body').append(jQuery(target));
				// set target CSS
				TweenMax.set(target,{
					position:'fixed',
					top:'50%',
					left: '50%',
					x:'-50%',
					y:'-50%',
					zIndex:100,
					autoAlpha:0
				});
			},

			//on click
			'click' : function( target, trigger ){

				//fade the target and overlay in
				TweenMax.allTo(['#soulbuttons-backdrop',target],window.tweenspeed,{
					autoAlpha:1
				});

				//make clicks on the overlay close everything
				jQuery('#soulbuttons-backdrop').click(function(){
					TweenMax.allTo([jQuery(this),target],window.tweenspeed,{
						autoAlpha:0
					});
				});
			}
		},


		'slideOverFromRight' : {

			'start': function( target ){

				//if there aren't any overlays
				if ( 1 > jQuery('#soulbuttons-backdrop').size() ) {

					//append the overlay to the body
					jQuery('body').append('<div id="soulbuttons-backdrop"></div>');

					//set overlay CSS
					TweenMax.set('#soulbuttons-backdrop',{
						position:'fixed',
						top:0,
						left:0,
						right:0,
						bottom:0,
						zIndex:99,
						backgroundColor:'rgba(0,0,0,0.5)',
						autoAlpha:0
					});

				}



				jQuery('body').append(jQuery(target));

				// set target CSS
				TweenMax.set(target,{
					position:'fixed',
					top:0,
					right:0,
					x:'100vw',
					zIndex:100,
					autoAlpha:0,
					width:'50%'
				});
			},

			//on click
			'click' : function( target, trigger ){

				var soulbuttonstl = new TimelineMax();
				//fade the target and overlay in
				soulbuttonstl
				.to('#soulbuttons-backdrop',window.tweenspeed,{
					autoAlpha:1
				})
				.to(target,window.tweenspeed,{
					autoAlpha:1,
					x:'0vw'
				},'0');

				//make clicks on the overlay close everything
				jQuery('#suoulbuttons-backdrop').click(function(){
					soulnavtl.reverse();
/*
					TweenMax.allTo([jQuery(this),target],1,{
						autoAlpha:0
					});
*/
				});
			}
		}
	};

	jQuery('.soulbuttons[data-target]').each(function(){
		var target = jQuery(this).data('target');
		var effect = jQuery(this).data('effect');
		SoulButtonTargetEffects[effect].start( target );
		jQuery(this).click(function(){
			var target = jQuery(this).data('target');
			var effect = jQuery(this).data('effect');
			SoulButtonTargetEffects[effect].click( target, this );
		});
	});
});
