<!DOCTYPE html>
<?php
// Get default vars
include "settings-iot.php";

// Do smart stuff with variables so the page won't screw up too much
if ($_GET[database]) {
	if ($_GET[database] != $database) {
		$datacolumn="data";
	}
	$database=$_GET[database];
}

// User wants something, OK then...
if ($_GET[table]) {$table=$_GET[table];}
if ($_GET[datacolumn]) {$datacolumn=$_GET[datacolumn];}

// Try to connect to MySQL... Key word: try...
mysql_connect($dbserver,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");
mysql_query("SET NAMES utf8");
mysql_query("SET CHARACTER SET utf8");

// Now break out to static HTML for a while...
?>
<html>
<head><title><?php echo $pagetitle;?></title>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var continuousData = new google.visualization.DataTable();
        continuousData.addColumn('datetime', 'Time');
        continuousData.addColumn('number', 'Value');

        continuousData.addRows([
<?php
// Fill Google Visualisation's ugly ass array from whatever settings we have so far.

// Latest entry?
$query="SELECT " . $datetimecolumn . " FROM `" . $table . "` order by `" . $datetimecolumn . "` desc LIMIT 0,1";
$result=mysql_query($query);

// Find out what datetime is 24 hours ago, You know, to show 24h graph... duh.
$firstdatetime = date("Y-m-d G:i:s",(time()-(86400+180)));
// Ignore this: $lastdatetime = date("Y-m-d G:i:s",(time()-(86400-180)));

// Get the data already!
$query24h="SELECT " . $datetimecolumn . ",". $datacolumn . " FROM `" . $table . "` where `" . $datetimecolumn . "` >= '" . $firstdatetime . "' AND `" . $datetimecolumn . "` <= '" . (mysql_result($result,0,$datetimecolumn)) . "' order by `" . $datetimecolumn . "` asc";
$result24h=mysql_query($query24h);

// Dump the data out to the JahoowaScript.
$firstrow="1";
while ($row = mysql_fetch_row($result24h)) {
	if ($firstrow == "0") {echo ",\n";}
	if ($firstrow == "1") {$firstrow="0";}
        echo "          [new Date('" . str_replace(" ","T",$row[0]) . "+01:00'), {$row[1]}]";
}

// Escape to HTML again.
?>]);

        var options = {
          title: '<?php echo str_replace("_"," ",$_GET[table]); ?>',
          // curveType: 'function',
          // legend: { position: 'bottom' }, // Removed. I am legend. There is no other.
          legend: 'none',
	  hAxis: {
            format: 'HH:mm',
	    title: 'Time'
	  },
	  vAxis: {
	    title: '<?php echo $datacolumn;?>'
	  }
        };

	var formatter_date = new google.visualization.DateFormat({pattern:"yyyy-MM-dd HH:mm"});
	formatter_date.format(continuousData, 0);

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

        chart.draw(continuousData, options);
      }
    </script>

<style>
html {
    font-family: sans-serif;
    -ms-text-size-adjust: 100%;
    -webkit-text-size-adjust: 100%
}
ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
    background-color: #333;
}

li {
    float: left;
    border-right:1px solid #bbb;
}

li:last-child {
    border-right: none;
}

li a {
    display: block;
    color: white;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
}

li a:hover:not(.active) {
    background-color: #111;
}

.header {
    width: 100%;
    padding: 15px;
    color: white;
    text-align: center;
    font-size: 1.875em;
    background-color: #555;
}

.level2 {
    background-color: #555;
}

.active {
    background-color: #4CAF50;
}
.active2 {
    background-color: #DFCF72;
}
</style>
</head>
<body>
<ul><li class="header"><?php echo $pagetitle;?></li></ul>
<?php
// Back from the dead (client side execution)...

// Create Header containing menu list of tables in selected, or default, database.
$query = "show tables;";
$result = mysql_query($query);

if (!$result) {
	// Idiot
	echo "DB Error, could not list tables\n";
	echo 'MySQL Error: ' . mysql_error();
	exit;
} else {
	// Britney Spears - Do somethin' (2004)
	echo "<ul>";
	while ($row = mysql_fetch_row($result)) {
		echo "<li>";
		if (filter_var($row[0], FILTER_VALIDATE_IP)) {$label=gethostbyaddr($row[0]);} else {$label=$row[0];}
		if ($row[0] == $table) {
			echo "<a class= \"active\" href=\"?database=$database&table={$row[0]}\">" . str_replace("_"," ",$label) . "</a>";
		} else {
			echo "<a href=\"?database=$database&table={$row[0]}\">" . str_replace("_"," ",$label) . "</a>";
		}
		echo "</li>";
	}
	echo "</ul>";
}

// Ok... He has FINALLY selected a table... Make next row of the menu.
if ($_GET[table]) {
	$query = "DESCRIBE `{$table}`;";
	$result = mysql_query($query);
	if (!$result) {
		echo "DB Error, could not list columns\n";
		echo 'MySQL Error: ' . mysql_error();
		exit;
	} else {
		echo "<ul class=\"level2\">";
		while ($row = mysql_fetch_row($result)) {
			if (!(($row[0] == "key") || ($row[0] == "datetime"))) {
				echo "<li>";
				if ($row[0] == $datacolumn) {
					echo "<a class= \"active2\" href=\"?database=$database&table=$table&datacolumn={$row[0]}\">{$row[0]}</a>";
				} else {
					echo "<a href=\"?database=$database&table=$table&datacolumn={$row[0]}\">{$row[0]}</a>";
				}
			echo "</li>";
			}
		}
		echo "</ul>";
	}
}

// Draw and go home.
if (($_GET[table]) && ($datacolumn)) {
	echo '<div id="curve_chart" style="width: 100%; height: 500px"></div>';
}

// The rest is silence...
?>
</body></html>
