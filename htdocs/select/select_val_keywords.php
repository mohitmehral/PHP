<?php
	// Variablen zum übergeben:
	// $where_select = "where val_keywords.id_keywords = '$id_keywords'"
	// $where_select = "where val_keywords.keywords = '$keywords'"
	// $where_select = "where val_keywords.id_sector = '$id_sector'"
	// $pos_mes (positive mitteilungen anzeigen?)
	$sql = "select id_keywords, keywords, id_sector " .
		"from val_keywords " . $where_select .
		"order by id_sector, keywords";
	$val_keywords = @mysql_query($sql);
	$val_keywords_num = @mysql_num_rows($val_keywords);
	if (!$val_keywords) {
		sql_error('val_keywords', $sql);
	} else {
		if ($pos_mes) {echo(" ... val_keywords");}
	}
?>
