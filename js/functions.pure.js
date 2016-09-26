/**
 * returns the screen/window/viewport size
 * tested on:
 * - IE 11
 * - Edge
 * - PC Chrome
 * - Android Chrome
 * - iPad Safari
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
