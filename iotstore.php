<?php
include "settings-iot.php";

if (isset($_GET['database'])){$database=$_GET['database'];}

if (isset($_GET['id'])){
	if (isset($_GET['set'])){
		$con = mysqli_connect($dbserver,$username,$password,$database);
		mysqli_query($con, "SET NAMES utf8");
		mysqli_query($con, "SET CHARACTER SET utf8");

		$query = "SELECT `key` FROM `" . $_GET['id'] . "`";
		print $query;
		$result = mysqli_query($con, $query);
		# print $result;
		if(empty($result)) {
       	         $query = "CREATE TABLE `". $_GET['id'] ."` (
       	                   `key` int(11) AUTO_INCREMENT,
			   `datetime` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       	                   `data` decimal(15,2) NOT NULL,
       	                   PRIMARY KEY  (`key`)
       	                   ) ENGINE = MYISAM";
			print $query;
			$result = mysqli_query($con, $query);
			# print $result;
			$query = "INSERT INTO `" . $_GET['id'] . "` (data) VALUES ('" . $_GET['set'] . "')";
	                print $query;
			$result = mysqli_query($con, $query);
			# print $result;
		} else {
			$query = "INSERT INTO `" . $_GET['id'] . "` (data) SELECT '" . $_GET['set'] . "' from (select datetime,data from `" . $_GET['id'] . "` order by `key` desc limit 1 ) as1 where as1.data <> '" .$_GET['set'] . "' OR as1.datetime < DATE_SUB(NOW(),INTERVAL 1 HOUR)";
			print $query;
			$result = mysqli_query($con, $query);
			# print $result;
		}
	}
}
?>
