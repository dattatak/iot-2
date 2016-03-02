<?php
include "settings-iot.php";

mysql_connect($dbserver,$username,$password);
@mysql_select_db($weatherdatabase) or die( "Unable to select database");
mysql_query("SET NAMES utf8");
mysql_query("SET CHARACTER SET utf8");

$query="SELECT * FROM " . $weathertable . " order by `" . $datetimecolumn . "` desc LIMIT 0,1";
$result=mysql_query($query);

$lasttimestamp = strtotime(mysql_result($result,0,$datetimecolumn));
$nowtimestamp = time();
$lasttimeseconds = $nowtimestamp-$lasttimestamp;

if ($lasttimeseconds >= 5400) {
        echo "<h3>" . floor($lasttimeseconds/60) . " min sedan kontakt med väder-<br>stationen. Livedata visas ej.</h3>";
} else {
        $firstdatetime = date("Y-m-d G:i:s",(time()-(86400+180)));
        $lastdatetime = date("Y-m-d G:i:s",(time()-(86400-180)));
        $query24h="SELECT " . $raincolumn . " FROM `" . $weathertable . "` where `" . $datetimecolumn . "` >= '" . $firstdatetime . "' AND `" . $datetimecolumn . "` <= '" . (mysql_result($result,0,$datetimecolumn)) . "' order by `" . $datetimecolumn . "` asc";
        $result24h=mysql_query($query24h);
        $i=0;
        echo mysql_result($result,$i,$indoortempcolumn) . " " . mysql_result($result,$i,$indoorhumiditycolumn) . " ";
        echo mysql_result($result,$i,$tempcolumn) . " " . mysql_result($result,$i,$humiditycolumn) . " ";
        echo (mysql_result($result,$i,$barometercolumn)+$barometeroffset) . " ";
        echo round(mysql_result($result,$i,$raincolumn)-mysql_result($result24h,0,$raincolumn), 2) . " ";
        echo round(mysql_result($result,$i,$windcolumn)) . " " . round(mysql_result($result,$i,$gustcolumn)) . " ";
        echo mysql_result($result,0,$datetimecolumn);
}
?>
