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
$midnight =  strtotime('today midnight');
$mostCommentBlogToday = get_most_comment_blog_by_user($_SESSION['user']->guid, $midnight);
$mostCommentBlogWeek = get_most_comment_blog_by_user($_SESSION['user']->guid, strtotime("-6 day", $midnight));
$mostCommentBlogMonth = get_most_comment_blog_by_user($_SESSION['user']->guid, strtotime("-29 day", $midnight));
$mostCommentBlogToday = array_filter($mostCommentBlogToday);
$mostCommentBlogWeek = array_filter($mostCommentBlogWeek);
$mostCommentBlogMonth = array_filter($mostCommentBlogMonth);

$mostCommentBlogTodayInfo = "";
if (!empty($mostCommentBlogToday)) {
	foreach ($mostCommentBlogToday as $c){
		$blogEntity = get_entity($c->guid);
		$mostCommentBlogTodayInfo = $mostCommentBlogTodayInfo."<div class=\"search_listing\"><div class=\"search_listing_info\"><p>" . " <a href=\"{$blogEntity->getURL()}\">{$blogEntity->title} ({$c->count})</a></p></div></div>";
	}
}  else {
	$mostCommentBlogTodayInfo = $mostCommentBlogTodayInfo."<p> No comments yet. </p>";
}

$mostCommentBlogWeekInfo = "";
if (!empty($mostCommentBlogWeek)) {
	foreach ($mostCommentBlogWeek as $c){
		$blogEntity = get_entity($c->guid);
		$mostCommentBlogWeekInfo = $mostCommentBlogWeekInfo."<div class=\"search_listing\"><div class=\"search_listing_info\"><p>" ." <a href=\"{$blogEntity->getURL()}\">{$blogEntity->title} ({$c->count})</a></p></div></div>";
	}
}  else {
	$mostCommentBlogWeekInfo = $mostCommentBlogWeekInfo."<p> No comments yet. </p>";
}


$mostCommentBlogMonthInfo = "";
if (!empty($mostCommentBlogMonth)) {
	foreach ($mostCommentBlogMonth as $c){
		$blogEntity = get_entity($c->guid);
		$mostCommentBlogMonthInfo = $mostCommentBlogMonthInfo."<div class=\"search_listing\"><div class=\"search_listing_info\"><p>" . " <a href=\"{$blogEntity->getURL()}\">{$blogEntity->title} ({$c->count})</a></p></div></div>";
	}
}  else {
	$mostCommentBlogMonthInfo = $mostCommentBlogMonthInfo."<p> No comments yet. </p>";
}
?>





<div id="content_area_user_title"><h2>The most commented postings</h2></div>
<div id="tabs2" class="contentWrapper members" style="width: 708px !important; ">
	<ul>
		<li><a href="#tabs2-1">Today</a></li>
		<li><a href="#tabs2-2">Last 7 days</a></li>
		<li><a href="#tabs2-3">Last 30 days</a></li>
	</ul>
	<div id="tabs2-1">
		<?php echo $mostCommentBlogTodayInfo ?>
	</div>
	<div id="tabs2-2">
		<?php echo $mostCommentBlogWeekInfo ?>
	</div>
	<div id="tabs2-3">
		<?php echo $mostCommentBlogMonthInfo ?>
	</div>
</div>



<style>
	#tabs2.ui-tabs .ui-tabs-panel{
		padding: 1em 0em !important;
	}
	#tabs2 .search_listing_info {
		margin-left: 0px !important;
	}

</style>

<script>
	$(document).ready(function(){
		$("#tabs2").tabs();
	});
</script>
