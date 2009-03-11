<?php
	// Variablen zum übergeben:
	// $where_select = "where val_category.id_category = '$id_category'"
	// $where_select = "where val_category.category = '$category'"
	// $where_select = "where val_category.id_sector = '$id_sector'"
	// $pos_mes (positive mitteilungen anzeigen?)
	$sql = "select id_category, category, id_sector " .
		"from val_category " . $where_select .
		"order by category";
	$val_category = @mysql_query($sql);
	$val_category_num = @mysql_num_rows($val_category);
	if (!$val_category) {
		echo("<p>Es gab einen Fehler beim Zugriff auf die Tabelle \"val_category\".</p><p>$sql</p>");
	} else {
		if ($pos_mes) {echo(" ... val_category");}
	}
?>