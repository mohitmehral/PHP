<?php
	// Variablen zum übergeben:
	// $where_select = "where val_related_ccpm.id_related_ccpm = '$id_related_ccpm'"
	// $where_select = "where val_related_ccpm.related_ccpm = '$related_ccpm'"
	// $where_select = "where val_related_ccpm.id_sector = '$id_sector'"
	// $pos_mes (positive mitteilungen anzeigen?)
	$sql = "select id_related_ccpm, related_ccpm, id_sector " .
		"from val_related_ccpm " . $where_select .
		"order by id_sector, related_ccpm";
	$val_related_ccpm = @mysql_query($sql);
	$val_related_ccpm_num = @mysql_num_rows($val_related_ccpm);
	if (!$val_related_ccpm) {
		echo("<p>Es gab einen Fehler beim Zugriff auf die Tabelle \"val_related_ccpm\".</p><p>$sql</p>");
	} else {
		if ($pos_mes) {echo(" ... val_related_ccpm");}
	}
?>