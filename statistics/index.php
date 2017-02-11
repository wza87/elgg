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
$lastloginStatics = elgg_view('statistics/lastloginStatics');
$part1 = elgg_view('statistics/part1');
$intro_message = elgg_view('statistics/blurb');

$content = elgg_view_layout('two_column_left_sidebar', $lastloginStatics, $part1);


// Try and get the user from the username and set the page body accordingly
$body = $content.elgg_view_layout('one_column', $intro_message);

page_draw($title, $body);