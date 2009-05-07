<?php
	// Variablen zum übergeben:
	// $where_select = "where val_reduces_non_ghg.id_reduces_non_ghg = '$id_reduces_non_ghg'"
	// $where_select = "where val_reduces_non_ghg.reduces_non_ghg = '$reduces_non_ghg'"
	// $pos_mes (positive mitteilungen anzeigen?)
	$sql = "select pam_reduces_non_ghg.id_reduces_non_ghg as id_reduces_non_ghg, reduces_non_ghg " .
		"from pam_reduces_non_ghg left join val_reduces_non_ghg on val_reduces_non_ghg.id_reduces_non_ghg = pam_reduces_non_ghg.id_reduces_non_ghg " . $where_select;
	$pam_reduces_non_ghg = @mysql_query($sql);
	$pam_reduces_non_ghg_num = @mysql_num_rows($pam_reduces_non_ghg);
	if (!$pam_reduces_non_ghg) {
		sql_error('pam_reduces_non_ghg', $sql);
	} else {
		if ($pos_mes) {echo(" ... pam_reduces_non_ghg");}
	}
?>
