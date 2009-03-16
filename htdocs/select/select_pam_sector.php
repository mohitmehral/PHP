<?php
	// Variablen zum übergeben:
	// $where_select = "where val_sector.id_sector = '$id_sector'"
	// $where_select = "where val_sector.sector = '$sector'"
	// $pos_mes (positive mitteilungen anzeigen?)
	$sql = "select pam_sector.id_sector as id_sector, sector " .
		"from pam_sector left join val_sector on val_sector.id_sector = pam_sector.id_sector " . $where_select;
	$pam_sector = @mysql_query($sql);
	$pam_sector_num = @mysql_num_rows($pam_sector);
	if (!$pam_sector) {
		sql_error('pam_sector', $sql);
	} else {
		if ($pos_mes) {echo(" ... pam_sector");}
	}
?>
