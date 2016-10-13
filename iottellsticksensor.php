<?php
include "settings-iot.php";

if (isset($_GET['id'])){
	if (isset($_GET['type'])) {
	  if ($_GET['type'] == "temp") {
			$final = trim(`tdtool --list-sensors | grep id={$_GET['id']} | awk -F'[=\\t]' '{print \$10}'`);
		} elseif ($_GET['type'] == "hum") {
			$final = trim(`tdtool --list-sensors | grep id={$_GET['id']} | awk -F'[=\\t]' '{print \$12}'`);
			}
		}
	print $final;
	} else {
        print "<pre>" . `tdtool --list-sensors` . "</pre>";
	}
?>
