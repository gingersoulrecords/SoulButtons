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
// preventDefault for hash links
jQuery(function($){
	jQuery('.soulbuttons.soulbuttons-prevent-default').each(function(){
		jQuery(this).click(function(e){
			e.preventDefault();
		});
	});
	
	jQuery('.soulbuttons.soulbuttons-unwrap').each(function(){
		if(jQuery(this).parent().is('p')){
			jQuery(this).unwrap();
		}
	});
	
});
// initalize scrollTo
jQuery(function($){
	jQuery('.soulbuttons.soulbuttons-scrollto').click(function(e){
		e.preventDefault();
		var el = jQuery(this);
		TweenMax.to( window, el.data('scrollto-speed'), { scrollTo: { y: el.attr( 'href' ), offsetY: el.data('scrollto-offset'), autoKill:false }, ease:Power4.easeOut } );
	})
});

// initalize target effects for buttons
jQuery(function(){
	
	function getUrlVars()
	{
	    var vars = [], hash;
	    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
	    for(var i = 0; i < hashes.length; i++)
	    {
	        hash = hashes[i].split('=');
	        vars.push(hash[0]);
	        vars[hash[0]] = hash[1];
	    }
	    return vars;
	}
	
	if( getUrlVars() == 'fl_builder'){
		return false;
	}
	
	if( jQuery('html').hasClass( 'fl-builder-edit' ) ) {
		return false;
	}
	
	
	
	
	if( 'undefined' === typeof( TweenMax ) ) {
		return false;
	}

	window.tweenspeed = .25;
	var SoulButtonTargetEffects = {
		'fadeInFromCenter' : {

			'start': function( target ){

				//if there aren't any overlays
				if ( 1 > jQuery('#soulbuttons-backdrop').size() ) {

					//append the overlay to the body
					//alert(jQuery('html').attr('class'));
					
					jQuery('body').append('<div id="soulbuttons-backdrop"></div>');

					//set overlay CSS
					TweenMax.set('#soulbuttons-backdrop',{
						position:'fixed',
						top:0,
						left:0,
						right:0,
						bottom:0,
						zIndex:992,
						backgroundColor:'rgba(0,0,0,0.75)',
						autoAlpha:0
					});

				}

				jQuery('body').append(jQuery(target));
				jQuery('<div class="closebutton">×</div>').prependTo(jQuery(target).find('.ss-container')).click(function(){
					jQuery('#soulbuttons-backdrop').click();
				});
				// set target CSS
				TweenMax.set(target,{
					position:'fixed',
					top:'50%',
					left: '50%',
					x:'-50%',
					y:'-50%',
					zIndex:992,
					autoAlpha:0, 
					//scale:.7
				});
			},

			//on click
			'click' : function( target, trigger ){
				jQuery(target).addClass('soulbuttons-open');
				jQuery('body').addClass('soulbuttons-modal-open');
				//fade the target and overlay in
				TweenMax.to('#soulbuttons-backdrop',.25,{
					autoAlpha:1, 
				});
				TweenMax.to(target,window.tweenspeed,{
					autoAlpha:1, 
					//scale:1
				});
				
				
				

				//make clicks on the overlay close everything
				jQuery('#soulbuttons-backdrop').click(function(){
				jQuery(target).removeClass('soulbuttons-open');
				jQuery('body').removeClass('soulbuttons-modal-open');
					TweenMax.to(jQuery(this),.25,{
						autoAlpha:0
					});
					TweenMax.to(target,window.tweenspeed,{
						autoAlpha:0, 
						//scale:.7
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
						zIndex:992,
						backgroundColor:'rgba(0,0,0,0.5)',
						autoAlpha:0
					});

				}



				jQuery('body').append(jQuery(target));
				
				jQuery('<div class="closebutton">×</div>').prependTo(jQuery(target).find('.ss-container')).click(function(){
					jQuery('#soulbuttons-backdrop').click();
				});
								// set target CSS
				TweenMax.set(target,{
					position:'fixed',
					top:0,
					right:0,
					x:'100vw',
					zIndex:992,
					autoAlpha:0,
					width:'50%'
				});
			},

			//on click
			'click' : function( target, trigger ){
				jQuery(target).addClass('soulbuttons-open');
				jQuery('body').addClass('soulbuttons-modal-open');
				var soulbuttonstl = new TimelineMax();
				//fade the target and overlay in
				soulbuttonstl
				.to('#soulbuttons-backdrop',.25,{
					autoAlpha:1
				})
				.to(target,window.tweenspeed,{
					autoAlpha:1,
					x:'0vw'
				},'0')
				;

				//make clicks on the overlay close everything
				jQuery('#soulbuttons-backdrop').click(function(){
					soulbuttonstl.reverse();
					jQuery(target).removeClass('soulbuttons-open');
					jQuery('body').removeClass('soulbuttons-modal-open');

				});
			}
		},

/*
		'pushOverFromRight' : {

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
				jQuery('body').append(jQuery('.oncanvas'));

				// set target CSS
				TweenMax.set(target,{
					position:'fixed',
					top:0,
					left:'100%',
					zIndex:100,
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
				.to(window.elementsToShift,window.tweenspeed,{
					x:'-50%'
				},'0')
				.to(target,window.tweenspeed,{
					left:'auto',
					right:0
				},'0');

				//make clicks on the overlay close everything
				jQuery('#soulbuttons-backdrop').click(function(){
					soulbuttonstl.reverse();

				});
			}
		}
*/
	};

	//if(jQuery('.fl-builder-edit').length === 0){
		
		jQuery('.soulbuttons[data-target]').each(function(){
			var target = jQuery(this).data('target');
			var effect = jQuery(this).data('effect');
			
			
			SoulButtonTargetEffects[effect].start( target );
			
			
			if ( jQuery(this).data('open') ) {
				SoulButtonTargetEffects[effect].click( target, this );
			}
			jQuery(this).click(function(){
				var target = jQuery(this).data('target');
				var effect = jQuery(this).data('effect');
				SoulButtonTargetEffects[effect].click( target, this );
			});
		});
	
	//}
	
	
	
	
});
