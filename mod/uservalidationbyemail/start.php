<?php
/**
 * Email user validation plugin.
 * Non-admin or admin created accounts are invalid until their email address is confirmed.
 *
 * @package ElggUserValidationByEmail
 * @author Curverider Ltd
 * @link http://elgg.com/
 */

function uservalidationbyemail_init() {
    global $CONFIG;

    // Register page handler to validate users
    // This isn't an action because security is handled by the validation codes.
    register_page_handler('uservalidationbyemail', 'uservalidationbyemail_page_handler');

    // Register hook listening to new users.
 //   register_elgg_event_handler('validate', 'user', 'uservalidationbyemail_email_validation');
}

/**
 * Get security token, forward to action.
 *
 * @param unknown_type $page
 * @return unknown_type
 */
function uservalidationbyemail_page_handler($page) {
    forward();
}

/**
 * Request email validation.
 */
function uservalidationbyemail_email_validation($event, $object_type, $object) {
    if (($object) && ($object instanceof ElggUser)) {
        uservalidationbyemail_request_validation($object->guid);
    }

    return true;
}

/**
 * Generate an email activation code.
 *
 * @param int $user_guid The guid of the user
 * @param string $email_address Email address
 * @return string
 */
function uservalidationbyemail_generate_code($user_guid, $email_address) {
    global $CONFIG;

    // Note I bind to site URL, this is important on multisite!
    return md5($user_guid . $email_address . $CONFIG->site->url . get_site_secret());
}

/**
 * Request user validation email.
 * Send email out to the address and request a confirmation.
 *
 * @param int $user_guid The user
 * @return mixed
 */
function uservalidationbyemail_request_validation($user_guid) {
    global $CONFIG;

    $user_guid = (int)$user_guid;
    $user = get_entity($user_guid);

    if (($user) && ($user instanceof ElggUser)) {
        // Work out validate link
        $code = uservalidationbyemail_generate_code($user_guid, $user->email);
        $link = "{$CONFIG->site->url}pg/uservalidationbyemail/confirm?u=$user_guid&c=$code";

        // Send validation email
        $result = notify_user($user->guid, $CONFIG->site->guid, sprintf(elgg_echo('email:validate:subject'), $user->username), sprintf(elgg_echo('email:validate:body'), $user->name, $link), NULL, 'email');
        if ($result) {
            system_message(elgg_echo('uservalidationbyemail:registerok'));
        }

        return $result;
    }

    return FALSE;
}

/**
 * Validate a user
 *
 * @param unknown_type $user_guid
 * @param unknown_type $code
 * @return unknown
 */
function uservalidationbyemail_validate_email($user_guid, $code) {
    $user = get_entity($user_guid);

    if ($code == uservalidationbyemail_generate_code($user_guid, $user->email)) {
        return set_user_validation_status($user_guid, true, 'email');
    }

    return false;
}

// Initialise
register_elgg_event_handler('init', 'system', 'uservalidationbyemail_init');