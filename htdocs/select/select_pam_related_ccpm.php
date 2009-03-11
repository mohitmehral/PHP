<?php
	// Variablen zum übergeben:
	// $where_select = "where val_related_ccpm.id_related_ccpm = '$id_related_ccpm'"
	// $where_select = "where val_related_ccpm.related_ccpm = '$related_ccpm'"
	// $where_select = "where val_related_ccpm.id_sector = '$id_sector'"
	// $pos_mes (positive mitteilungen anzeigen?)
	$sql = "select pam_related_ccpm.id_related_ccpm as id_related_ccpm, related_ccpm, id_sector " .
		"from pam_related_ccpm left join val_related_ccpm on val_related_ccpm.id_related_ccpm = pam_related_ccpm.id_related_ccpm " . $where_select .
		"order by id_sector, related_ccpm";
	$pam_related_ccpm = @mysql_query($sql);
	$pam_related_ccpm_num = @mysql_num_rows($pam_related_ccpm);
	if (!$pam_related_ccpm) {
		echo("<p>Es gab einen Fehler beim Zugriff auf die Tabelle \"pam_related_ccpm\".</p><p>$sql</p>");
	} else {
		if ($pos_mes) {echo(" ... pam_related_ccpm");}
	}
?>