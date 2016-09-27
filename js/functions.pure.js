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
 * - [.page] element to reference top position (if topScroll negative, topArray will be displayed)
 * CSS sample for the button:
 * #scrollBtn {
 *  position: fixed;
 * 	right: 10px;
 * 	bottom: 10px;
 * 	padding: 10px;
 * 	background-color: rgba(0, 0, 0, 0.8);
 *  border-radius: 5px;
 *  color: white;
 *  font-weight: bold;
 * }
 * usage: 
 * - TopArray.init();
 * tested on:
 * - IE 9,11
 * - Edge
 * - PC Chrome
 * - iPad (iOS 9)
 * - Android 7 Chrome
 */
var TopArrow = {
  // REFERENCE ELEMENT to activate the button on scroll
  topMenu: $(".page")[0],

  init: function() {
    if(!this.topMenu) {
      // no scrolling position reference found
      // we stop here to prevent js errors bellow
      return;
    }

    // arrow itself
    var topArrow = $('<a id="scrollBtn" style="display: none;" href="#scroll"><img src="images/scrolltop-icon2.png"/></a>');
    // button event
    topArrow.click(this.doScroll);

    // appending element to the page
    $('body').append(topArrow);

    // scroll event handler
    $(document).scroll(this.checkScroll);
  },


  // jQuery event
  doScroll: function(e_) {
    e_.preventDefault();
    // possible to have different buttons with different actions
    if($(this).attr('href')==='#scroll') {
      var offset = ($(document).scrollTop());
      if(/(IE 9|Trident)/.test(navigator.userAgent)) {
        // IE 9,11 only
        $('body, html').animate({scrollTop: '-'+offset+'px'});
      }
      else {
        // others
        $('body').animate({scrollTop: '-'+offset+'px'});
      }
    }
  },


  // scroll check function
  // jQuery event
  checkScroll: function () {
    var topscroll = TopArrow.topMenu.getBoundingClientRect().top;
    console.log( topscroll );
    if(topscroll<0) {
      // show
      $('#scrollBtn').fadeIn('fast');
    }
    else {
      // hide
      $('#scrollBtn').fadeOut('fast');
    }
  }
};
