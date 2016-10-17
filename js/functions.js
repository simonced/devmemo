/**
 * returns the screen/window/viewport size
 * tested on:
 * - IE 11
 * - Edge
 * - PC Chrome
 * - Android 7 Chrome
 * - iPad (iOS 9)
 * @return array {width, height}
 */
function viewport() {
	// exception
	if(/iPad/.test(navigator.userAgent)) {
		return { width: document.width, height: document.height};
	}

	var e = window, a = 'inner';
	if (!('innerWidth' in window )) {
		a = 'client';
		e = document.documentElement || document.body;
	}
	return { width : e[ a+'Width' ] , height : e[ a+'Height' ] };
}


/**
 * top arrow (scrool to top)
 * depends on:
 * - jQuery
 * The button to be displayed is #TopArrowScrollBtn
 * CSS sample for the button:
 * #TopArrowScrollBtn {
 * 	position: fixed;
 * 	right: 10px;
 * 	bottom: 10px;
 * 	padding: 10px;
 * 	background-color: rgba(0, 0, 0, 0.8);
 * 	border-radius: 5px;
 * 	color: white;
 * 	font-weight: bold;
 * 	text-decoration: none;
 * }
 * usage: 
 * - TopArrow.init();
 * tested on:
 * - IE 9,11
 * - Firefox 49
 * - Edge
 * - PC Chrome 53
 * - iPad (iOS 9)
 * - Android 7 Chrome
 */
var TopArrow = {
	init: function() {
		// arrow itself
		var topArrow = jQuery('<a id="TopArrowScrollBtn" style="display: none;" href="#scroll">^</a>');
		// button event
		topArrow.click(this.doScroll);

		// appending element to the page
		jQuery('body').append(topArrow);

		// scroll event handler
		jQuery(document).scroll(this.checkScroll);
	},


	// jQuery event
	doScroll: function(e_) {
		e_.preventDefault();
		// possible to have different buttons with different actions
		if(jQuery(this).attr('href')==='#scroll') {
			//var offset = (jQuery(document).scrollTop());
			//if(/(IE 9|Trident)/.test(navigator.userAgent)) {
			//	// IE 9,11 only
			//	jQuery('body, html').animate({scrollTop: '-'+offset+'px'});
			//}
			//else if(/Firefox/.test(navigator.userAgent)) {
			//	jQuery('body, html').animate({scrollTop: 0});
			//} else {
			//	// others
			//	//jQuery('body').animate({scrollTop: '-'+offset+'px'});
			//}
			jQuery('body, html').animate({scrollTop: 0});
		}
	},


	// scroll check function
	// jQuery event
	checkScroll: function () {
		var topscroll = jQuery(document).scrollTop();
		if(topscroll>0) {
			// show
			jQuery('#TopArrowScrollBtn').fadeIn('fast');
		}
		else {
			// hide
			jQuery('#TopArrowScrollBtn').fadeOut('fast');
		}
	}
};

