<?php
include "settings-iot.php";

if ($_GET['database']){$database=$_GET['database'];}

if ($_GET['id']){
	if ($_GET['set']){
		mysql_connect($dbserver,$username,$password);
		@mysql_select_db($database) or die( "Unable to select database");
		mysql_query("SET NAMES utf8");
		mysql_query("SET CHARACTER SET utf8");

		$query = "SELECT `key` FROM `" . $_GET['id'] . "`";
		print $query;
		$result = mysql_query($query);
		print $result;
		if(empty($result)) {
       	         $query = "CREATE TABLE `". $_GET['id'] ."` (
       	                   `key` int(11) AUTO_INCREMENT,
			   `datetime` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       	                   `data` decimal(15,2) NOT NULL,
       	                   PRIMARY KEY  (`key`)
       	                   ) ENGINE = MYISAM";
			print $query;
			$result = mysql_query($query);
			print $result;
			$query = "INSERT INTO `" . $_GET['id'] . "` (data) VALUES ('" . $_GET['set'] . "')";
	                print $query;
			$result = mysql_query($query);
			print $result;
		} else {
			$query = "INSERT INTO `" . $_GET['id'] . "` (data) SELECT '" . $_GET['set'] . "' from (select data from `" . $_GET['id'] . "` order by `key` desc limit 1 ) as1 where as1.data <> '" .$_GET['set'] . "'";
			print $query;
			$result = mysql_query($query);
			print $result;
		}
	}
}
?>
