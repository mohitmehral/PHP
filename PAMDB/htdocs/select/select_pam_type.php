<?php
	// Variablen zum übergeben:
	// $where_select = "where val_type.id_type = '$id_type'"
	// $where_select = "where val_type.type = '$type'"
	// $pos_mes (positive mitteilungen anzeigen?)
	$sql = "select pam_type.id_type as id_type, type " .
		"from pam_type left join val_type on val_type.id_type = pam_type.id_type " . $where_select;
	$pam_type = @mysql_query($sql);
	$pam_type_num = @mysql_num_rows($pam_type);
	if (!$pam_type) {
		echo("<p>Es gab einen Fehler beim Zugriff auf die Tabelle \"pam_type\".</p><p>$sql</p>");
	} else {
		if ($pos_mes) {echo(" ... pam_type");}
	}
?>