<?php
include "settings-iot.php";

if ($_GET['id']){
		mysql_connect($dbserver,$username,$password);
		@mysql_select_db($database) or die( "Unable to select database");
		mysql_query("SET NAMES utf8");
		mysql_query("SET CHARACTER SET utf8");

                $query = "SELECT data FROM `" . $_GET['id'] . "` order by `key` desc limit 1";
                $result = mysql_query($query);
                print mysql_result($result, 0);
        }
?>
