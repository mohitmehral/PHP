<?php
	// Variablen zum übergeben:
	// $where_select = "where val_ghg.id_ghg = '$id_ghg'"
	// $where_select = "where val_ghg.ghg = '$ghg'"
	// $pos_mes (positive mitteilungen anzeigen?)
	$sql = "select pam_ghg.id_ghg as id_ghg, ghg, ghg_output " .
		"from pam_ghg left join val_ghg on val_ghg.id_ghg = pam_ghg.id_ghg " . $where_select;
	$pam_ghg = @mysql_query($sql);
	$pam_ghg_num = @mysql_num_rows($pam_ghg);
	if (!$pam_ghg) {
		sql_error('pam_ghg', $sql);
	} else {
		if ($pos_mes) {echo(" ... pam_ghg");}
	}
?>
