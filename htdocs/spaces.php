<?php
	while (substr($spaces,0,1) == " " or substr($spaces,0,1) == chr(10) or substr($spaces,0,1) == chr(13) or substr($spaces,0,1) == chr(9)) {
		$spaces = substr($spaces,1);
	}
	while (substr($spaces,-1) == " " or substr($spaces,-1) == chr(10) or substr($spaces,-1) == chr(13) or substr($spaces,-1) == chr(9)) {
		$spaces = substr($spaces,0,-1);
	}
	str_replace('�','&auml;',$spaces);
	str_replace('�','&Auml;',$spaces);
	str_replace('�','&uuml;',$spaces);
	str_replace('�','&Uuml;',$spaces);
	str_replace('�','&ouml;',$spaces);
	str_replace('�','&Ouml;',$spaces);
	str_replace('�','&szlig;',$spaces);
?>

