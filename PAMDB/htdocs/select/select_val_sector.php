<?php
	// Variablen zum übergeben:
	// $where_select = "where val_sector.id_sector = '$id_sector'"
	// $where_select = "where val_sector.sector = '$sector'"
	// $pos_mes (positive mitteilungen anzeigen?)
	$sql = "select id_sector, sector " .
		"from val_sector " . $where_select;
	$val_sector = @mysql_query($sql);
	$val_sector_num = @mysql_num_rows($val_sector);
	if (!$val_sector) {
		echo("<p>Es gab einen Fehler beim Zugriff auf die Tabelle \"val_sector\".</p><p>$sql</p>");
	} else {
		if ($pos_mes) {echo("<p>val_sector</p><p>$sql</p>");}
	}
?>