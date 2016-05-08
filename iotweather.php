<?php
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

$con = mysql_connect($dbserver,$username,$password,$weatherdatabase);
mysqli_query($con, "SET NAMES utf8");
mysqli_query($con, "SET CHARACTER SET utf8");

$query="SELECT * FROM " . $weathertable . " order by `" . $datetimecolumn . "` desc LIMIT 0,1";
$result=mysqli_query($con, $query);

$lasttimestamp = strtotime(mysqli_result($result,0,$datetimecolumn));
$nowtimestamp = time();
$lasttimeseconds = $nowtimestamp-$lasttimestamp;

if ($lasttimeseconds >= 5400) {
        echo "<h3>" . floor($lasttimeseconds/60) . " min sedan kontakt med väder-<br>stationen. Livedata visas ej.</h3>";
} else {
        $firstdatetime = date("Y-m-d G:i:s",(time()-(86400+180)));
        $lastdatetime = date("Y-m-d G:i:s",(time()-(86400-180)));
        $query24h="SELECT " . $raincolumn . " FROM `" . $weathertable . "` where `" . $datetimecolumn . "` >= '" . $firstdatetime . "' AND `" . $datetimecolumn . "` <= '" . (mysql_result($result,0,$datetimecolumn)) . "' order by `" . $datetimecolumn . "` asc";
        $result24h=mysqli_query($con, $query24h);
        $i=0;
        echo mysqli_result($result,$i,$indoortempcolumn) . " " . mysqli_result($result,$i,$indoorhumiditycolumn) . " ";
        echo mysqli_result($result,$i,$tempcolumn) . " " . mysqli_result($result,$i,$humiditycolumn) . " ";
        echo (mysqli_result($result,$i,$barometercolumn)+$barometeroffset) . " ";
        echo round(mysqli_result($result,$i,$raincolumn)-mysqli_result($result24h,0,$raincolumn), 2) . " ";
        echo round(mysqli_result($result,$i,$windcolumn)) . " " . round(mysqli_result($result,$i,$gustcolumn)) . " ";
        echo mysqli_result($result,0,$datetimecolumn);
}
?>
