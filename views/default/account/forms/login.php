<?php
/**
 * Elgg login form
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

global $CONFIG;

$ts = time();

$token = generate_action_token($ts);

$form_body = "<p class=\"loginbox\"><label>" . elgg_echo('username') . "<br />" . elgg_view('input/text', array('internalname' => 'username', 'class' => 'login-textarea')) . "</label>";
$form_body .= "<br />";
$form_body .= "<label>" . elgg_echo('password') . "<br />" . elgg_view('input/password', array('internalname' => 'password', 'class' => 'login-textarea')) . "</label><br />";

$form_body .= elgg_view('login/extend');

$form_body .= elgg_view('input/submit', array('value' => elgg_echo('login'))) . " <div id=\"persistent_login\"><label><input type=\"checkbox\" name=\"persistent\" value=\"true\" />".elgg_echo('user:persistent')."</label></div></p>";

$form_body .= "<hr>";
$form_body .= "<p class=\"loginbox\"> ";
$form_body .= "<fb:login-button scope=\"public_profile,email\" onlogin=\"checkLoginState();\"></fb:login-button>";
//$form_body .= "<div class=\"g-signin2\" data-onsuccess=\"onSignIn\"></div>";
$form_body .= "<a href=\"javascript:void(0)\" id=\"customBtn\"> Sign in with Gmail</a>";
$form_body .= "</p><br />";

$form_body .= "<p class=\"loginbox\">";
$form_body .= (!isset($CONFIG->disable_registration) || !($CONFIG->disable_registration)) ? "<a href=\"{$vars['url']}pg/register/\">" . elgg_echo('register') . "</a> | " : "";
$form_body .= "<a href=\"{$vars['url']}account/forgotten_password.php\">" . elgg_echo('user:password:lost') . "</a></p>";

$login_url = $vars['url'];
if ((isset($CONFIG->https_login)) && ($CONFIG->https_login)) {
	$login_url = str_replace("http", "https", $vars['url']);
}
?>

<div id="login-box">
	<h2><?php echo elgg_echo('login'); ?></h2>
	<?php
	echo elgg_view('input/form', array('body' => $form_body, 'action' => "{$login_url}action/login"));
	?>
</div>
<script src="https://apis.google.com/js/api:client.js"></script>
<script type="text/javascript">

	var auth2; // The Sign-In object.
	var googleUser; // The current user.

	/**
	 * Calls startAuth after Sign in V2 finishes setting up.
	 */
	var startApp = function() {
		gapi.load('auth2', initSigninV2);
	};

	/**
	 * Initializes Signin v2 and sets up listeners.
	 */
	var initSigninV2 = function() {
		auth2 = gapi.auth2.init({
			client_id: '560019197607-ep3h9j922fptkd59pq7auqmonlkcus25.apps.googleusercontent.com',
			scope: 'profile'
		});

		attachSignin(document.getElementById('customBtn'));

	};

	/**
	 * Listener method for sign-out live value.
	 *
	 * @param {boolean} val the updated signed out state.
	 */

	function attachSignin(element) {
		console.log(element.id);
		auth2.attachClickHandler(element, {},
			function(googleUser) {
				onSignIn(googleUser);
			}, function(error) {
				alert(JSON.stringify(error, undefined, 2));
			});
	}

	function onSignIn(googleUser) {
		var profile = googleUser.getBasicProfile();
		console.log('ID: ' + profile.getId()); // Do not send to your backend! Use an ID token instead.
		console.log('Name: ' + profile.getName());
		console.log('Image URL: ' + profile.getImageUrl());
		console.log('Email: ' + profile.getEmail()); // This is null if the 'email' scope is not present.
		var id_token = googleUser.getAuthResponse().id_token;
		document.location.href = "<?php echo $vars['url'] ?>action/fbregister?__elgg_token=<?php echo $token ?>&__elgg_ts=<?php echo $ts ?>&sso=google&token=" + encodeURI(id_token);
	}

	startApp();

	function statusChangeCallback(response) {
		console.log('statusChangeCallback');
		console.log(response);
		if (response.status === 'connected') {
			// Logged into your app and Facebook.
			document.location.href = "<?php echo $vars['url'] ?>action/fbregister?__elgg_token=<?php echo $token ?>&__elgg_ts=<?php echo $ts ?>&sso=facebook&token=" + encodeURI(response.authResponse.accessToken);
		} else if (response.status === 'not_authorized') {
			// The person is logged into Facebook, but not your app.
			alert("You are not not_authorized");
		} else {
			alert("You are not not_authorized");
		}
	}

	function checkLoginState() {
		FB.getLoginStatus(function(response) {
			statusChangeCallback(response);
		});
	}

	window.fbAsyncInit = function() {
		FB.init({
			appId      : '1188331961286547',
			cookie     : true,  // enable cookies to allow the server to access
			// the session
			xfbml      : true,  // parse social plugins on this page
			version    : 'v2.8' // use graph api version 2.8
		});

	};

	// Load the SDK asynchronously
	(function(d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) return;
		js = d.createElement(s); js.id = id;
		js.src = "//connect.facebook.net/en_US/sdk.js";
		fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));




	$(document).ready(function() { $('input[name=username]').focus(); });
</script>