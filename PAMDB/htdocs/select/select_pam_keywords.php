<?php
	// Variablen zum übergeben:
	// $where_select = "where val_keywords.id_keywords = '$id_keywords'"
	// $where_select = "where val_keywords.keywords = '$keywords'"
	// $where_select = "where val_keywords.id_sector = '$id_sector'"
	// $pos_mes (positive mitteilungen anzeigen?)
	$sql = "select pam_keywords.id_keywords as id_keywords, keywords, id_sector " .
		"from pam_keywords left join val_keywords on val_keywords.id_keywords = pam_keywords.id_keywords " . $where_select .
		"order by id_sector, keywords";
	$pam_keywords = @mysql_query($sql);
	$pam_keywords_num = @mysql_num_rows($pam_keywords);
	if (!$pam_keywords) {
		sql_error('pam_keywords', $sql);
	} else {
		if ($pos_mes) {echo(" ... pam_keywords");}
	}
?>
