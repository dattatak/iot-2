<?php
include "settings-iot.php";

if (isset($_GET['id'])){
	if (isset($_GET['type'])) {
	  if ($_GET['type'] == "temp") {
			$got = trim(`tdtool --list-sensors | grep id={$_GET['id']}[[:space:]] | awk -F '=' '{print \$10}'`);
		} elseif ($_GET['type'] == "hum") {
			$got = trim(`tdtool --list-sensors | grep id={$_GET['id']}[[:space:]] | awk -F '=' '{print \$12}'`);
			}
		}
	print $final;
	} else {
        print "<pre>" . `tdtool --list-sensors` . "</pre>";
	}
?>
