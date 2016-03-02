<?php
include "settings-iot.php";

if (isset($_GET['id'])){
	$got = trim(`cat $myhomedir/tddevices.txt | grep id={$_GET['id']}[[:space:]] | awk -F '=' '{print \$NF}'`);
	if ($_GET['type'] == "dimmer") {
		if ($got == 'ON') {$final = '100';}
		if ($got == 'OFF') {$final = '0';}
		if ((floatval($got) > 0) && (floatval($got) < 255)) {$final = round(floatval($got)/255*100);}
		} else {
		if ($got == 'ON') {$final = 'on';}
                if ($got == 'OFF') {$final = 'off';}
		}
	if (isset($_GET['set'])) {
		$setmode = $_GET['set'];
		if ($_GET['set'] == 'ON') {$setmode = '100';}
                if ($_GET['set'] == 'OFF') {$setmode = '0';}
		if (floatval($setmode) < 2) {
			 $final = `tdtool --off {$_GET['id']}; tdtool --off {$_GET['id']};`;
			} elseif (floatval($setmode) > 98) {
			 $final = `tdtool --on {$_GET['id']}; tdtool --on {$_GET['id']};`;
			} else {
				$value = round(floatval($_GET['set'])/100*255);
				$final = `tdtool --dimlevel $value --dim {$_GET['id']}; tdtool --dimlevel $value --dim {$_GET['id']};`;
			}
		system("tdtool --list-devices > $myhomedir/tddevices.txt");
		}
	print $final;
	} else {
        print "<pre>" . `cat $myhomedir/tddevices.txt` . "</pre>";
	}
?>
