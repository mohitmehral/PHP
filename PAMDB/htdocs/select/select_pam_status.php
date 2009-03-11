<?php
	// Variablen zum übergeben:
	// $where_select = "where val_status.id_status = '$id_status'"
	// $where_select = "where val_status.status = '$status'"
	// $pos_mes (positive mitteilungen anzeigen?)
	$sql = "select pam_status.id_status as id_status, status " .
		"from pam_status left join val_status on val_status.id_status = pam_status.id_status " . $where_select;
	$pam_status = @mysql_query($sql);
	$pam_status_num = @mysql_num_rows($pam_status);
	if (!$pam_status) {
		echo("<p>Es gab einen Fehler beim Zugriff auf die Tabelle \"pam_status\".</p><p>$sql</p>");
	} else {
		if ($pos_mes) {echo(" ... pam_status");}
	}
?>