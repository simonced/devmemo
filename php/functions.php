/**
 * http identification, like htaccess
 */
function http_login($login_, $mdp_) {
	if ($_SERVER['PHP_AUTH_USER']!="$login_" || $_SERVER['PHP_AUTH_PW']!="$mdp_") {
		header('WWW-Authenticate: Basic realm="Identification"');
		header('HTTP/1.0 401 Unauthorized');
		echo "You need to login to access the current website.";
		exit;
	}
}
