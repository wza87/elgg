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

$fourMonthAgo = strtotime('-4 month');
$futureDate=date('Y-m-d', $fourMonthAgo);

$log = get_visitor_log($_SESSION['user']->guid, $fourMonthAgo);
$friendLog = get_friends_log($_SESSION['user']->guid, $fourMonthAgo);



$midnight =  strtotime('today midnight');



echo $midnight;
echo date('l jS \of F Y h:i:s A', $midnight);

$lastnight = strtotime('-1 day', $midnight);
echo date('l jS \of F Y h:i:s A', $lastnight);
$firstDayInMonth = strtotime("first day of this month midnight");
echo date('l jS \of F Y h:i:s A', $firstDayInMonth);
echo "<br>";
echo date('l jS \of F Y h:i:s A', date('01-m-Y'));

$dayLabel = array();
array_push($dayLabel,date('d M Y', $midnight), date('d M Y', strtotime('-1 day', $midnight)), date('d M Y', strtotime('-2 day', $midnight)), date('d M Y', strtotime('-3 day', $midnight)), date('d M Y', strtotime('-4 day', $midnight)), date('d M Y', strtotime('-5 day', $midnight)), date('d M Y', strtotime('-6 day', $midnight)));
echo json_encode($dayLabel);
echo "<br>";
$weekLabel = array();
array_push($weekLabel,date('d M Y', strtotime("-6 day", $midnight))." - ".date('d M Y', $midnight), date('d M Y', strtotime("-13 day", $midnight))." - ".date('d M Y', strtotime("-6 day", $midnight)), date('d M Y', strtotime("-20 day", $midnight))." - ".date('d M Y', strtotime("-13 day", $midnight)), date('d M Y', strtotime("-21 day", $midnight))." - ".date('d M Y', strtotime("-20 day", $midnight)));
echo json_encode($monthLabel);
echo "<br>";
$monthLabel = array();
$firstMonth = strtotime("first day of this month midnight");
$SecondMonth = strtotime("-1 month", $firstMonth);
$ThirdMonth = strtotime("-2 month", $firstMonth);
array_push($monthLabel,date('M Y', $firstMonth),date('M Y', $SecondMonth),date('M Y', $ThirdMonth));
echo json_encode($monthLabel);
echo "<br>";


$days = array();
$weeks = array();
$month = array();
$dayCountry = get_visitor_country($_SESSION['user']->guid, $midnight);
$weekCountry = get_visitor_country($_SESSION['user']->guid, strtotime("-6 day", $midnight));
$monthCountry = get_visitor_country($_SESSION['user']->guid, $firstMonth);
$friendsDays = array();
$friendsWeeks = array();
$friendsMonth = array();
$mostCommentBlogToday = get_most_comment_blog_by_user($_SESSION['user']->guid, $midnight);
$mostCommentBlogWeek = get_most_comment_blog_by_user($_SESSION['user']->guid, strtotime("-6 day", $midnight));
$mostCommentBlogMonth = get_most_comment_blog_by_user($_SESSION['user']->guid, strtotime("-29 day", $midnight));
$mostCommentBlogToday = array_filter($mostCommentBlogToday);
$mostCommentBlogWeek = array_filter($mostCommentBlogWeek);
$mostCommentBlogMonth = array_filter($mostCommentBlogMonth);

if (!empty($mostCommentBlogToday)) {
	foreach ($mostCommentBlogToday as $c){
		$blogEntity = get_entity($c->guid);
		$mostCommentBlogTodayInfo = "<p>" . elgg_echo('blog') . ": <a href=\"{$blogEntity->getURL()}\">{$blogEntity->title} ({$c->count})</a></p>";
	}
}  else {
	$mostCommentBlogTodayInfo = "<p> No comments yet. </p>";
}

if (!empty($mostCommentBlogWeek)) {
	foreach ($mostCommentBlogWeek as $c){
		$blogEntity = get_entity($c->guid);
		$mostCommentBlogWeekInfo = "<p>" . elgg_echo('blog') . ": <a href=\"{$blogEntity->getURL()}\">{$blogEntity->title} ({$c->count})</a></p>";
	}
}  else {
	$mostCommentBlogWeekInfo = "<p> No comments yet. </p>";
}


if (!empty($mostCommentBlogMonth)) {
	foreach ($mostCommentBlogMonth as $c){
		$blogEntity = get_entity($c->guid);
		$mostCommentBlogMonthInfo = "<p>" . elgg_echo('blog') . ": <a href=\"{$blogEntity->getURL()}\">{$blogEntity->title} ({$c->count})</a></p>";
	}
}  else {
	$mostCommentBlogMonthInfo = "<p> No comments yet. </p>";
}

echo $mostCommentBlogMonthInfo;


echo json_encode($mostCommentBlogToday);
echo json_encode($mostCommentBlogWeek);
echo json_encode($mostCommentBlogMonth);

echo json_encode($dayCountry);
echo "<br>";

echo json_encode($weekCountry);
echo "<br>";

echo json_encode($monthCountry);
echo "<br>";

foreach ($monthCountry as $c){
	echo $c->string." ".$c->count."".$c->id;
	echo "<br>";
}


echo "test";
foreach ($log as $l)
{
	$time_created = $l->time_created;
	if ($time_created >= $midnight){
		$days[0]++;
		$weeks[0]++;
	} else {
		$datediff = ceil(($midnight - $time_created) / (60 * 60 * 24));
		if ($datediff < 7){
			$days[$datediff]++;
			$weeks[0]++;
		} else if ($datediff < 14){
			$weeks[1]++;
		} else if ($datediff < 21){
			$weeks[2]++;
		} else if ($datediff < 28){
			$weeks[3]++;
		}
	}

	if ($time_created > $firstMonth){
		$month[0]++;
	} else if ($time_created > $SecondMonth){
		$month[1]++;
	} else if ($time_created > $ThirdMonth){
		$month[2]++;
	}

}

foreach ($friendLog as $l)
{
	$time_created = $l->time_created;
	if ($time_created >= $midnight){
		$friendsDays[0]++;
		$friendsWeeks[0]++;
	} else {
		$datediff = ceil(($midnight - $time_created) / (60 * 60 * 24));
		if ($datediff < 7){
			$friendsDays[$datediff]++;
			$friendsWeeks[0]++;
		} else if ($datediff < 14){
			$friendsWeeks[1]++;
		} else if ($datediff < 21){
			$friendsWeeks[2]++;
		} else if ($datediff < 28){
			$friendsWeeks[3]++;
		}
	}

	if ($time_created > $firstMonth){
		$friendsMonth[0]++;
	} else if ($time_created > $SecondMonth){
		$friendsMonth[1]++;
	} else if ($time_created > $ThirdMonth){
		$friendsMonth[2]++;
	}

}

echo "friends list <br>";
echo json_encode($friendsDays);
echo "<br>";

echo json_encode($friendsWeeks);
echo "<br>";

echo json_encode($friendsMonth);
echo "<br>";


?>



<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqPlot/1.0.8/jquery.jqplot.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jqPlot/1.0.8/jquery.jqplot.min.css" />

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqPlot/1.0.8/plugins/jqplot.pieRenderer.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqPlot/1.0.8/plugins/jqplot.dateAxisRenderer.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqPlot/1.0.8/plugins/jqplot.canvasTextRenderer.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqPlot/1.0.8/plugins/jqplot.canvasAxisTickRenderer.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqPlot/1.0.8/plugins/jqplot.categoryAxisRenderer.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqPlot/1.0.8/plugins/jqplot.barRenderer.min.js"></script>
<div id="tabs1">
	<ul>
		<li><a href="#tabs1-1">Days</a></li>
		<li><a href="#tabs1-2">Weeks</a></li>
		<li><a href="#tabs1-3">Months</a></li>
	</ul>
	<div id="tabs1-1">
		<div id="chart1-1" style="height:400px; width:920px;"></div>
	</div>
	<div id="tabs1-2">
		<div id="chart1-2" style="height:400px; width:920px;"></div>
	</div>
	<div id="tabs1-3">
		<div id="chart1-3" style="height:400px; width:920px;"></div>
	</div>
</div>


<div id="tabs4">
	<ul>
		<li><a href="#tabs4-1">Today</a></li>
		<li><a href="#tabs4-2">This Week</a></li>
		<li><a href="#tabs4-3">This Month</a></li>
	</ul>
	<div id="tabs4-1">
		<div id="chart4-1" style="height:400px; width:920px;"></div>
	</div>
	<div id="tabs4-2">
		<div id="chart4-2" style="height:400px; width:920px;"></div>
	</div>
	<div id="tabs4-3">
		<div id="chart4-3" style="height:400px; width:920px;"></div>
	</div>
</div>


<div id="tabs2">
	<ul>
		<li><a href="#tabs2-1">Today</a></li>
		<li><a href="#tabs2-2">Last 7 days</a></li>
		<li><a href="#tabs2-3">Last 30 days</a></li>
	</ul>
	<div id="tabs2-1">
		<div id="chart2-1" style="height:400px; width:920px;"><?php echo $mostCommentBlogTodayInfo ?></div>
	</div>
	<div id="tabs2-2">
		<div id="chart2-2" style="height:400px; width:920px;"><?php echo $mostCommentBlogWeekInfo ?></div>
	</div>
	<div id="tabs2-3">
		<div id="chart2-3" style="height:400px; width:920px;"><?php echo $mostCommentBlogMonthInfo ?></div>
	</div>
</div>

<div id="tabs3">
	<ul>
		<li><a href="#tabs3-1">Days</a></li>
		<li><a href="#tabs3-2">Weeks</a></li>
		<li><a href="#tabs3-3">Months</a></li>
	</ul>
	<div id="tabs3-1">
		<div id="chart3-1" style="height:400px; width:920px;"></div>
	</div>
	<div id="tabs3-2">
		<div id="chart3-2" style="height:400px; width:920px;"></div>
	</div>
	<div id="tabs3-3">
		<div id="chart3-3" style="height:400px; width:920px;"></div>
	</div>
</div>


<style>
	#one_column {
		background-color: rgba(0, 0, 0, 0);
	}
	.ui-tabs {
		width: 100%;
		margin: 2em auto;
	}
	.ui-tabs-nav{
		font-size: 12px;
	}
	.ui-tabs-panel{
		font-size: 14px;
	}
	.jqplot-target {
		font-size: 18px;
	}
	ol.description {
		list-style-position: inside;
		font-size:15px;
		margin:1.5em auto;
		padding:0 15px;
		width:600px;
	}


</style>

<script>
	$(document).ready(function(){

		var dayLabel = <?php echo json_encode($dayLabel) ?>;
		var days = <?php echo json_encode($days) ?>;

		var times = 7;
		var bar1 = [];
		var showEmptyMsg = true;
		for(var i=0; i < times; i++){
			var value = days[i];
			if (value === undefined){
				value = 0;
			} else {
				showEmptyMsg = false;
			}
			bar1.push([dayLabel[i], value])
		}




//		var bar1 = [['Apples',11],['Oranges',7],['Pears',3],['Bananas',9],['Lemons',5]];
		var data1 = [3,1,2,3,5,4];
		var data2 = [4,3,3,4,5,6];
		var data3 = [9,10,8,7,4,6];
		var data4 = [9,8,7,12,9,10];
		var pie1 = [
			['Black', 212],['White', 140], ['Red', 131],['Blue', 510]
		];

		if (!showEmptyMsg){
			$.jqplot('chart1-1', [bar1],{
				title: 'Number of people visiting your page',
				series:[{renderer:$.jqplot.BarRenderer}],
				axesDefaults: {
					tickRenderer: $.jqplot.CanvasAxisTickRenderer ,
					tickOptions: {
						angle: -30,
						fontSize: '10pt'
					}
				},
				axes: {
					xaxis: {
						renderer: $.jqplot.CategoryAxisRenderer
					}
				}
			});
		} else {
			$("#chart1-1").text("No Visitors Yet.")
		}

		var weekLabel = <?php echo json_encode($weekLabel) ?>;
		var weeks = <?php echo json_encode($weeks) ?>;
		times = 4;
		showEmptyMsg = true;
		var bar2 = [];
		for(i=0; i < times; i++){
			value = weeks[i];
			if (value === undefined){
				value = 0;
			} else {
				showEmptyMsg = false;
			}
			bar2.push([weekLabel[i], value])
		}

		if (!showEmptyMsg){
			$.jqplot('chart1-2', [bar2],{
				title: 'Number of people visiting your page',
				series:[{renderer:$.jqplot.BarRenderer}],
				axesDefaults: {
					tickRenderer: $.jqplot.CanvasAxisTickRenderer ,
					tickOptions: {
						angle: -30,
						fontSize: '10pt'
					}
				},
				axes: {
					xaxis: {
						renderer: $.jqplot.CategoryAxisRenderer
					}
				}
			});
		} else {
			$("#chart1-2").text("No Visitors Yet.")
		}

		var monthLabel = <?php echo json_encode($monthLabel) ?>;
		var months = <?php echo json_encode($month) ?>;
		times = 3;
		showEmptyMsg = true;
		var bar3 = [];
		for(i=0; i < times; i++){
			value = months[i];
			if (value === undefined){
				value = 0;
			} else {
				showEmptyMsg = false;
			}
			bar3.push([monthLabel[i], value])
		}

		if (!showEmptyMsg){
			$.jqplot('chart1-3', [bar3],{
				title: 'Number of people visiting your page',
				series:[{renderer:$.jqplot.BarRenderer}],
				axesDefaults: {
					tickRenderer: $.jqplot.CanvasAxisTickRenderer ,
					tickOptions: {
						angle: -30,
						fontSize: '10pt'
					}
				},
				axes: {
					xaxis: {
						renderer: $.jqplot.CategoryAxisRenderer
					}
				}
			});
		} else {
			$("#chart1-3").text("No Visitors Yet.")
		}




//		$.jqplot('chart2-1', [bar1],{
//			title: 'The most commented postings',
//			series:[{renderer:$.jqplot.BarRenderer}],
//			axesDefaults: {
//				tickRenderer: $.jqplot.CanvasAxisTickRenderer ,
//				tickOptions: {
//					angle: -30,
//					fontSize: '10pt'
//				}
//			},
//			axes: {
//				xaxis: {
//					renderer: $.jqplot.CategoryAxisRenderer
//				}
//			}
//		});
//
//		$.jqplot('chart2-2', [data1,data2,data3,data4],{
//			title: 'The most commented postings'
//		});
//
//		$.jqplot('chart2-3', [bar1],{
//			title: 'The most commented postings',
//			seriesDefaults: {
//				renderer: jQuery.jqplot.PieRenderer,
//				rendererOptions: {
//					showDataLabels: true,
//					dataLabels: 'value',
//					sliceMargin: 6,
//					lineWidth: 5
//				}
//			},
//			legend: {
//				show: true,
//				location: 'e',
//				renderer: $.jqplot.EnhancedPieLegendRenderer  ,
//				rendererOptions: {
//					numberColumns: 1
//				},
//				marginRight: '17%'
//			}
//		});


		var friendsDays = <?php echo json_encode($friendsDays) ?>;

		times = 7;
		showEmptyMsg = true;
		var bar4 = [];
		for(i=0; i < times; i++){
			value = friendsDays[i];
			if (value === undefined){
				value = 0;
			} else {
				showEmptyMsg = false;
			}
			bar4.push([dayLabel[i], value])
		}

		if (!showEmptyMsg){
			$.jqplot('chart3-1', [bar4],{
				title: 'Number of friends added',
				series:[{renderer:$.jqplot.BarRenderer}],
				axesDefaults: {
					tickRenderer: $.jqplot.CanvasAxisTickRenderer ,
					tickOptions: {
						angle: -30,
						fontSize: '10pt'
					}
				},
				axes: {
					xaxis: {
						renderer: $.jqplot.CategoryAxisRenderer
					}
				}
			});
		} else {
			$("#chart3-1").text("No Friends Added.")
		}

		var friendsWeeks = <?php echo json_encode($friendsWeeks) ?>;
		times = 4;
		showEmptyMsg = true;
		var bar5 = [];
		for(i=0; i < times; i++){
			value = friendsWeeks[i];
			if (value === undefined){
				value = 0;
			} else {
				showEmptyMsg = false;
			}
			bar5.push([weekLabel[i], value])
		}

		if (!showEmptyMsg){
			$.jqplot('chart3-2', [bar5],{
				title: 'Number of friends added',
				series:[{renderer:$.jqplot.BarRenderer}],
				axesDefaults: {
					tickRenderer: $.jqplot.CanvasAxisTickRenderer ,
					tickOptions: {
						angle: -30,
						fontSize: '10pt'
					}
				},
				axes: {
					xaxis: {
						renderer: $.jqplot.CategoryAxisRenderer
					}
				}
			});
		} else {
			$("#chart3-2").text("No Friends Added.")
		}

		var friendsMonth = <?php echo json_encode($friendsMonth) ?>;
		times = 3;
		showEmptyMsg = true;
		var bar6 = [];
		for(i=0; i < times; i++){
			value = friendsMonth[i];
			if (value === undefined){
				value = 0;
			} else {
				showEmptyMsg = false;
			}
			bar6.push([monthLabel[i], value])
		}

		if (!showEmptyMsg){
			$.jqplot('chart3-3', [bar6],{
				title: 'Number of friends added',
				series:[{renderer:$.jqplot.BarRenderer}],
				axesDefaults: {
					tickRenderer: $.jqplot.CanvasAxisTickRenderer ,
					tickOptions: {
						angle: -30,
						fontSize: '10pt'
					}
				},
				axes: {
					xaxis: {
						renderer: $.jqplot.CategoryAxisRenderer
					}
				}
			});
		} else {
			$("#chart3-3").text("No Friends Added.")
		}


		var dayCountry = <?php echo json_encode($dayCountry) ?>;
		var weekCountry = <?php echo json_encode($weekCountry) ?>;
		var monthCountry = <?php echo json_encode($monthCountry) ?>;
		var pie1 = [];
		var pie2 = [];
		var pie3 = [];
		for(i=0; i < dayCountry.length; i++){
			var country = dayCountry[i];
			pie1.push([country.string, country.count])
		}

		for(i=0; i < weekCountry.length; i++){
			var country = weekCountry[i];
			pie2.push([country.string, country.count])
		}

		for(i=0; i < monthCountry.length; i++){
			var country = monthCountry[i];
			pie3.push([country.string, country.count])
		}

		if (pie1 && pie1.length > 0){
			$.jqplot('chart4-1', [pie1],{
				title: 'Visitor\'s country',
				seriesDefaults: {
					renderer: jQuery.jqplot.PieRenderer,
					rendererOptions: {
						showDataLabels: true,
						dataLabels: 'value',
						sliceMargin: 6,
						lineWidth: 5
					}
				},
				legend: {
					show: true,
					location: 'e',
					renderer: $.jqplot.EnhancedPieLegendRenderer  ,
					rendererOptions: {
						numberColumns: 1
					},
					marginRight: '17%'
				}
			});
		} else {
			$("#chart4-1").text("No Visitors Yet.")
		}

		if (pie2 && pie2.length > 0){
			$.jqplot('chart4-2', [pie2],{
				title: 'Visitor\'s country',
				seriesDefaults: {
					renderer: jQuery.jqplot.PieRenderer,
					rendererOptions: {
						showDataLabels: true,
						dataLabels: 'value',
						sliceMargin: 6,
						lineWidth: 5
					}
				},
				legend: {
					show: true,
					location: 'e',
					renderer: $.jqplot.EnhancedPieLegendRenderer  ,
					rendererOptions: {
						numberColumns: 1
					},
					marginRight: '17%'
				}
			});
		} else {
			$("#chart4-2").text("No Visitors Yet.")
		}

		if (pie3 && pie3.length > 0){
			$.jqplot('chart4-3', [pie3],{
				title: 'Visitor\'s country',
				seriesDefaults: {
					renderer: jQuery.jqplot.PieRenderer,
					rendererOptions: {
						showDataLabels: true,
						dataLabels: 'value',
						sliceMargin: 6,
						lineWidth: 5
					}
				},
				legend: {
					show: true,
					location: 'e',
					renderer: $.jqplot.EnhancedPieLegendRenderer  ,
					rendererOptions: {
						numberColumns: 1
					},
					marginRight: '17%'
				}
			});
		} else {
			$("#chart4-3").text("No Visitors Yet.")
		}

		$("#tabs1").tabs();
		$("#tabs2").tabs();
		$("#tabs3").tabs();
		$("#tabs4").tabs();


	});
</script>
