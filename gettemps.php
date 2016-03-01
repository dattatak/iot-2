<?php
include "settings.php";

mysql_connect($dbserver,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");
mysql_query("SET NAMES utf8");
mysql_query("SET CHARACTER SET utf8");

$query="SELECT * FROM " . $table . " order by `" . $datetimecolumn . "` desc LIMIT 0,1";
$result=mysql_query($query);

$lasttimestamp = strtotime(mysql_result($result,0,$datetimecolumn));
$nowtimestamp = time();
$lasttimeseconds = $nowtimestamp-$lasttimestamp;

if ($lasttimeseconds >= 5400) {
        echo "<h3>" . floor($lasttimeseconds/60) . " min sedan kontakt med väder-<br>stationen. Livedata visas ej.</h3>";
} else {
        $firstdatetime = date("Y-m-d G:i:s",(time()-(86400+180)));
        $lastdatetime = date("Y-m-d G:i:s",(time()-(86400-180)));
        $query24h="SELECT " . $raincolumn . " FROM `" . $table . "` where `" . $datetimecolumn . "` >= '" . $firstdatetime . "' AND `" . $datetimecolumn . "` <= '" . (mysql_result($result,0,$datetimecolumn)) . "' order by `" . $datetimecolumn . "` asc";
        $result24h=mysql_query($query24h);

        //$num=mysql_numrows($result);
	$i=0;
	//$num24h=mysql_numrows($result24h);
        //$i24h=0;

        //$intempmax=-10000;$intempmin=10000;$outtempmax=-10000;$outtempmin=10000;
        //$inhummax=-10000;$inhummin=10000;$outhummax=-10000;$outhummin=10000;
        //$outwindavgmax=-10000;$outwindgustmax=-10000;
        //while ($i24h < $num24h) {
        //        if (mysql_result($result24h,$i24h,$indoortempcolumn) > $intempmax) {$intempmax = (mysql_result($result24h,$i24h,$indoortempcolumn));}
        //        if (mysql_result($result24h,$i24h,$indoortempcolumn) < $intempmin) {$intempmin = (mysql_result($result24h,$i24h,$indoortempcolumn));}
        //        if (mysql_result($result24h,$i24h,$tempcolumn) > $outtempmax) {$outtempmax = (mysql_result($result24h,$i24h,$tempcolumn));}
        //        if (mysql_result($result24h,$i24h,$tempcolumn) < $outtempmin) {$outtempmin = (mysql_result($result24h,$i24h,$tempcolumn));}
        //        if (mysql_result($result24h,$i24h,$indoorhumiditycolumn) > $inhummax) {$inhummax = (mysql_result($result24h,$i24h,$indoorhumiditycolumn));}
        //        if (mysql_result($result24h,$i24h,$indoorhumiditycolumn) < $inhummin) {$inhummin = (mysql_result($result24h,$i24h,$indoorhumiditycolumn));}
        //        if (mysql_result($result24h,$i24h,$humiditycolumn) > $outhummax) {$outhummax = (mysql_result($result24h,$i24h,$humiditycolumn));}
        //        if (mysql_result($result24h,$i24h,$humiditycolumn) < $outhummin) {$outhummin = (mysql_result($result24h,$i24h,$humiditycolumn));}
        //        if (mysql_result($result24h,$i24h,$windcolumn) > $outwindavgmax) {$outwindavgmax = (mysql_result($result24h,$i24h,$windcolumn));}
        //        if (mysql_result($result24h,$i24h,$gustcolumn) > $outwindgustmax) {$outwindgustmax = (mysql_result($result24h,$i24h,$gustcolumn));}

        //$i24h++;
        //}
        //while ($i < $num) {
	echo mysql_result($result,$i,$indoortempcolumn) . " " . mysql_result($result,$i,$indoorhumiditycolumn) . " ";
	echo mysql_result($result,$i,$tempcolumn) . " " . mysql_result($result,$i,$humiditycolumn) . " ";
	echo (mysql_result($result,$i,$barometercolumn)+$barometeroffset) . " ";
	echo round(mysql_result($result,$i,$raincolumn)-mysql_result($result24h,0,$raincolumn), 2) . " ";
	echo round(mysql_result($result,$i,$windcolumn)) . " " . round(mysql_result($result,$i,$gustcolumn)) . " ";
	echo mysql_result($result,0,$datetimecolumn);
	//$i++;
	//}
}
?>
