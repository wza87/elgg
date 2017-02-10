<?php
/**
 * Elgg registration action
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

global $CONFIG;

// Get variables
$ssoType = get_input('sso');
//$userId = get_input('userId');
//$username = $ssoType.$userId;
$token = get_input('token');
$persistent = true;
$friend_guid = (int) get_input('friend_guid',0);
$invitecode = get_input('invitecode');

$fbApiUlr = "https://graph.facebook.com/me?fields=id,name,email&access_token=".$token;
$googleApiUlr = "https://www.googleapis.com/oauth2/v3/tokeninfo?id_token=".$token;

$admin = get_input('admin');
if (is_array($admin)) {
	$admin = $admin[0];
}

if (!$CONFIG->disable_registration) {
// For now, just try and register the user
	try {

	    if (strcmp($ssoType,"facebook") == 0 || strcmp($ssoType,"google") == 0 ){
	        if (strcmp($ssoType,"facebook") == 0){
	           $restUrl = $fbApiUlr;
            } else {
                $restUrl = $googleApiUlr;
            }

            $ch = curl_init();

            curl_setopt($ch,CURLOPT_URL,$restUrl);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

            $output=curl_exec($ch);

            if ($output){
                $json = json_decode($output, true);
            }

            curl_close($ch);

            if ($json){

                if (strcmp($ssoType,"facebook") == 0){
                    $userId = $json["id"];
                } else {
                    $userId = $json["sub"];
                }
                $password = $username."12345678";
                $password2 = $username."12345678";
                $username = $ssoType.$userId;
                $email = $json["email"];
                $name = $json["name"];
                if ($user = get_user_by_username($username)) {
                    //return false;
                    login($user, $persistent);
                    system_message(elgg_echo('loginok'));
                    if (isset($_SESSION['last_forward_from']) && $_SESSION['last_forward_from']) {
                        $forward_url = $_SESSION['last_forward_from'];
                        unset($_SESSION['last_forward_from']);
                        forward($forward_url);
                    } else {
                        if ( (isadminloggedin()) && (!datalist_get('first_admin_login'))) {
                            system_message(elgg_echo('firstadminlogininstructions'));
                            datalist_set('first_admin_login', time());

                            forward('pg/admin/plugins');
                        } else if (get_input('returntoreferer')) {
                            forward($_SERVER['HTTP_REFERER']);
                        } else {
                            forward("pg/dashboard/");
                        }
                    }
                }

                $guid = register_user($username, $password, $name, $email, false, $friend_guid, $invitecode);
                if (((trim($password) != "") && (strcmp($password, $password2) == 0)) && ($guid)) {
                    $new_user = get_entity($guid);
                    if (($guid) && ($admin)) {
                        // Only admins can make someone an admin
                        admin_gatekeeper();
                        $new_user->admin = 'yes';
                    }

                    set_user_validation_status($new_user->getGUID(), TRUE, 'admin_created');

                    system_message(sprintf(elgg_echo("registerok"),$CONFIG->sitename));

                    // Forward on success, assume everything else is an error...
                    login($new_user, $persistent);
                    if (isset($_SESSION['last_forward_from']) && $_SESSION['last_forward_from']) {
                        $forward_url = $_SESSION['last_forward_from'];
                        unset($_SESSION['last_forward_from']);
                        forward($forward_url);
                    } else {
                        if ( (isadminloggedin()) && (!datalist_get('first_admin_login'))) {
                            system_message(elgg_echo('firstadminlogininstructions'));
                            datalist_set('first_admin_login', time());

                            forward('pg/admin/plugins');
                        } else if (get_input('returntoreferer')) {
                            forward($_SERVER['HTTP_REFERER']);
                        } else {
                            forward("pg/dashboard/");
                        }
                    }
                } else {
                    register_error(elgg_echo("registerbad"));
                }
            } else {
                register_error(elgg_echo("registerbad"));
            }

        }  else {
            register_error(elgg_echo("registerbad"));
        }


	} catch (RegistrationException $r) {
		register_error($r->getMessage());
	}
} else {
	register_error(elgg_echo('registerdisabled'));
}

$qs = explode('?',$_SERVER['HTTP_REFERER']);
$qs = $qs[0];
$qs .= "?u=" . urlencode($username) .  "&sso=" . urlencode($ssoType) ."&e=" . urlencode($email) . "&n=" . urlencode($name) . "&friend_guid=" . urlencode($restUrl);

forward($qs);


