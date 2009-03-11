<?php
	while (substr($spaces,0,1) == " " or substr($spaces,0,1) == chr(10) or substr($spaces,0,1) == chr(13) or substr($spaces,0,1) == chr(9)) {
		$spaces = substr($spaces,1);
	}
	while (substr($spaces,-1) == " " or substr($spaces,-1) == chr(10) or substr($spaces,-1) == chr(13) or substr($spaces,-1) == chr(9)) {
		$spaces = substr($spaces,0,-1);
	}
	str_replace('ä','&auml;',$spaces);
	str_replace('Ä','&Auml;',$spaces);
	str_replace('ü','&uuml;',$spaces);
	str_replace('Ü','&Uuml;',$spaces);
	str_replace('ö','&ouml;',$spaces);
	str_replace('Ö','&Ouml;',$spaces);
	str_replace('ß','&szlig;',$spaces);
?>

