<?php
	// Variablen zum übergeben:
	// $where_select = "where val_reduces_non_ghg.id_reduces_non_ghg = '$id_reduces_non_ghg'"
	// $where_select = "where val_reduces_non_ghg.reduces_non_ghg = '$reduces_non_ghg'"
	// $pos_mes (positive mitteilungen anzeigen?)
	$sql = "select id_reduces_non_ghg, reduces_non_ghg " .
		"from val_reduces_non_ghg " . $where_select;
	$val_reduces_non_ghg = @mysql_query($sql);
	$val_reduces_non_ghg_num = @mysql_num_rows($val_reduces_non_ghg);
	if (!$val_reduces_non_ghg) {
		echo("<p>Es gab einen Fehler beim Zugriff auf die Tabelle \"val_reduces_non_ghg\".</p><p>$sql</p>");
	} else {
		if ($pos_mes) {echo(" ... val_reduces_non_ghg");}
	}
?>