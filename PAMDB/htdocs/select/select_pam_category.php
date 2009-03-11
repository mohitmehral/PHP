<?php
	// Variablen zum übergeben:
	// $where_select = "where val_category.id_category = '$id_category'"
	// $where_select = "where val_category.category = '$category'"
	// $where_select = "where val_category.id_sector = '$id_sector'"
	// $pos_mes (positive mitteilungen anzeigen?)
	$sql = "select pam_category.id_category as id_category, category, id_sector " .
		"from pam_category left join val_category on val_category.id_category = pam_category.id_category " . $where_select .
		"order by category";
	$pam_category = @mysql_query($sql);
	$pam_category_num = @mysql_num_rows($pam_category);
	if (!$pam_category) {
		echo("<p>Es gab einen Fehler beim Zugriff auf die Tabelle \"pam_category\".</p><p>$sql</p>");
	} else {
		if ($pos_mes) {echo(" ... pam_category");}
	}
?>