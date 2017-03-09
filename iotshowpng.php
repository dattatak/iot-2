<!DOCTYPE html>
<?php
// Get default vars
include "settings-iot.php";

function mysqli_result($res,$row=0,$col=0){
    $numrows = mysqli_num_rows($res);
    if ($numrows && $row <= ($numrows-1) && $row >=0){
        mysqli_data_seek($res,$row);
        $resrow = (is_numeric($col)) ? mysqli_fetch_row($res) : mysqli_fetch_assoc($res);
        if (isset($resrow[$col])){
            return $resrow[$col];
        }
    }
    return false;
}

// Do smart stuff with variables so the page won't screw up too much
if (isset($_GET['database'])) {
	if ($_GET['database'] != $database) {
		$datacolumn="data";
	}
	$database=$_GET['database'];
}

// User wants something, OK then...
if (isset($_GET['table'])) {$table=$_GET['table'];}
if (isset($_GET['datacolumn'])) {$datacolumn=$_GET['datacolumn'];}

// Try to connect to MySQL... Key word: try...
$con = mysqli_connect($dbserver,$username,$password,$database);
mysqli_query($con, "SET NAMES utf8");
mysqli_query($con, "SET CHARACTER SET utf8");

// Now break out to static HTML for a while...
?>
<html>
<head><title><?php echo $pagetitle;?></title>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
			google.charts.load('44', {'packages':['corechart']});
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
$result=mysqli_query($con, $query);

// Find out what datetime is 24 hours ago, You know, to show 24h graph... duh.
$firstdatetime = date("Y-m-d G:i:s",(time()-(86400+180)));
// Ignore this: $lastdatetime = date("Y-m-d G:i:s",(time()-(86400-180)));

// Get the data already!
$query24h="SELECT " . $datetimecolumn . ",". $datacolumn . " FROM `" . $table . "` where `" . $datetimecolumn . "` >= '" . $firstdatetime . "' AND `" . $datetimecolumn . "` <= '" . (mysqli_result($result,0,$datetimecolumn)) . "' order by `" . $datetimecolumn . "` asc";
$result24h=mysqli_query($con, $query24h);

// Dump the data out to the JahoowaScript.
$firstrow="1";
while ($row = mysqli_fetch_row($result24h)) {
	if ($firstrow == "0") {echo ",\n";}
	if ($firstrow == "1") {$firstrow="0";}
  echo "          [new Date('" . str_replace(" ","T",$row[0]) . "+01:00'), {$row[1]}]";
}

// Escape to HTML again.
?>]);

        var options = {
          title: '<?php echo str_replace("_"," ",$_GET['table']); ?>',
          // curveType: 'function', // Removed. Too much display voodoo messes up the precision.
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
				var chart_div = document.getElementById('curve_chart');
				var chart = new google.visualization.LineChart(chart_div);
				//chart.draw(continuousData, options);
      				google.visualization.events.addListener(chart, 'ready', function () {
        		       	  chart_div.innerHTML = '<img src="' + chart.getImageURI() + '">';
                                  console.log(chart_div.innerHTML);
				  //var imgUri = chart.getImageURI();
				  //document.write(imgUri);
			        });
				chart.draw(continuousData, options);
			}
</script>

</head>
<body>
<script>
  document.write('<a href="' + document.referrer + '">');
</script>
<div id="curve_chart" style="width: 100%; height: 600px;"></div>
</a>
</body></html>
