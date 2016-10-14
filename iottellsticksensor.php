<?php
include "settings-iot.php";

if (isset($_GET['id'])){
	if (isset($_GET['type'])) {
	  if ($_GET['type'] == "temp") {
			$final = trim(`cat $myhomedir/tdsensors.txt | grep id={$_GET['id']} | awk -F'[=\\t]' '{print \$10}'`);
		} elseif ($_GET['type'] == "hum") {
			$final = trim(`cat $myhomedir/tdsensors.txt | grep id={$_GET['id']} | awk -F'[=\\t]' '{print \$12}'`);
			}
		}
	print $final;
	} else {
        print "<pre>" . `cat $myhomedir/tdsensors.txt` . "</pre>";
	}
?>
