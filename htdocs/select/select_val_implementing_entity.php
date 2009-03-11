<?php
	// Variablen zum übergeben:
	// $where_select = "where val_implementing_entity.id_implementing_entity = '$id_implementing_entity'"
	// $where_select = "where val_implementing_entity.implementing_entity = '$implementing_entity'"
	// $pos_mes (positive mitteilungen anzeigen?)
	$sql = "select id_implementing_entity, implementing_entity " .
		"from val_implementing_entity " . $where_select;
	$val_implementing_entity = @mysql_query($sql);
	$val_implementing_entity_num = @mysql_num_rows($val_implementing_entity);
	if (!$val_implementing_entity) {
		echo("<p>Es gab einen Fehler beim Zugriff auf die Tabelle \"val_implementing_entity\".</p><p>$sql</p>");
	} else {
		if ($pos_mes) {echo(" ... val_implementing_entity");}
	}
?>