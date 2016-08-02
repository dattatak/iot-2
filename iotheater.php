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

$con = mysqli_connect($dbserver,$username,$password,$heaterdatabase);
mysqli_query($con, "SET NAMES utf8");
mysqli_query($con, "SET CHARACTER SET utf8");

$query="SELECT " . $heatercolumn . " FROM " . $heatertable . " order by `" . $datetimecolumn . "` desc LIMIT 0,1";
$result=mysqli_query($con, $query);

$lasttimestamp = strtotime(mysqli_result($result,0,$datetimecolumn));
$nowtimestamp = time();
$lasttimeseconds = $nowtimestamp-$lasttimestamp;

if ($lasttimeseconds >= 5400) {
        echo "nul";
} else {
        echo mysqli_result($result,0,$heatercolumn);
}
?>
