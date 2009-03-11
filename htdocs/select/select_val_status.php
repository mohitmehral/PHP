<?php
	// Variablen zum übergeben:
	// $where_select = "where val_status.id_status = '$id_status'"
	// $where_select = "where val_status.status = '$status'"
	// $pos_mes (positive mitteilungen anzeigen?)
	$sql = "select id_status, status " .
		"from val_status " . $where_select;
	$val_status = @mysql_query($sql);
	$val_status_num = @mysql_num_rows($val_status);
	if (!$val_status) {
		echo("<p>Es gab einen Fehler beim Zugriff auf die Tabelle \"val_status\".</p><p>$sql</p>");
	} else {
		if ($pos_mes) {echo(" ... val_status");}
	}
?>