<?php
/**
 * Elgg comments add form
 *
 * @package Elgg
 * @author Curverider Ltd <info@elgg.com>
 * @link http://elgg.com/
 *
 * @uses $vars['entity']
 */


$user = $_SESSION['guid'] ;
$userEntity = get_entity($_SESSION['user']->guid);
$lastTimeLogin = $userEntity->last_login;
$prevLastTimeLogin = $userEntity->prev_last_login;

$logCount = count_visitor_log($_SESSION['user']->guid, $prevLastTimeLogin, $lastTimeLogin);
$friendLogCount = count_friends_log($_SESSION['user']->guid, $prevLastTimeLogin, $lastTimeLogin);
$commentLogCount = count_new_comments($_SESSION['user']->guid, $prevLastTimeLogin, $lastTimeLogin);

$newVisitorCount = 0;
foreach ($newVisitorCount as $c){
    $newVisitorCount = $c->count;
}

$newFriendCount = 0;
foreach ($friendLogCount as $c){
    $newFriendCount = $c->count;
}
$newCommentCount = 0;
foreach ($commentLogCount as $c){
    $newCommentCount = $c->count;
}

?>

<div>
    <p></p>
    <p>Last Login Time: <?php echo date('d M Y h:i:s A', $lastTimeLogin) ?> </p>
    <p>New Friends Added during Last Login: <?php echo $newFriendCount ?> </p>
    <p>New Comments during Last Login: <?php echo $newCommentCount ?> </p>
    <p>Visitor during Last Login: <?php echo $newVisitorCount ?> </p>
</div>

