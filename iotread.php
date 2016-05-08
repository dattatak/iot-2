<?php
include "settings-iot.php";

if ($_GET['id']){
		$con = mysqli_connect($dbserver,$username,$password,$database);
		mysqli_query($con, "SET NAMES utf8");
		mysqli_query($con, "SET CHARACTER SET utf8");

                $query = "SELECT data FROM `" . $_GET['id'] . "` order by `key` desc limit 1";
                $result = mysqli_query($con, $query);
								$value = mysqli_fetch_assoc($result);
                print $value['data'];
        }
?>
