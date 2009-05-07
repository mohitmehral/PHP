<?php
	// Variablen zum übergeben:
	// $where_select = "where val_ghg.id_ghg = '$id_ghg'"
	// $where_select = "where val_ghg.ghg = '$ghg'"
	// $pos_mes (positive mitteilungen anzeigen?)
	$sql = "select id_ghg, ghg, ghg_output " .
		"from val_ghg " . $where_select;
	$val_ghg = @mysql_query($sql);
	$val_ghg_num = @mysql_num_rows($val_ghg);
	if (!$val_ghg) {
		sql_error('val_ghg', $sql);
	} else {
		if ($pos_mes) {echo(" ... val_ghg");}
	}
?>
