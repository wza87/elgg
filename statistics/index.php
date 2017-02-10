<?php
/**
 * Elgg statistics
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

// Get the Elgg engine
require_once(dirname(dirname(__FILE__)) . "/engine/start.php");

// Ensure that only logged-in users can see this page
gatekeeper();

// Set context and title
set_context('statistics');
set_page_owner(get_loggedin_userid());
$title = elgg_echo('statistics');

// wrap intro message in a div
$intro_message = elgg_view('statistics/blurb');

// Try and get the user from the username and set the page body accordingly
$body = elgg_view_layout('one_column', $intro_message);

page_draw($title, $body);