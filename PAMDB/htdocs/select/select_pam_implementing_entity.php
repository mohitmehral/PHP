<?php
	// Variablen zum übergeben:
	// $where_select = "where val_implementing_entity.id_implementing_entity = '$id_implementing_entity'"
	// $where_select = "where val_implementing_entity.implementing_entity = '$implementing_entity'"
	// $pos_mes (positive mitteilungen anzeigen?)
	$sql = "select pam_implementing_entity.id_implementing_entity as id_implementing_entity, implementing_entity, specification " .
		"from pam_implementing_entity left join val_implementing_entity on val_implementing_entity.id_implementing_entity = pam_implementing_entity.id_implementing_entity " . $where_select;
	$pam_implementing_entity = @mysql_query($sql);
	$pam_implementing_entity_num = @mysql_num_rows($pam_implementing_entity);
	if (!$pam_implementing_entity) {
		echo("<p>Es gab einen Fehler beim Zugriff auf die Tabelle \"pam_implementing_entity\".</p><p>$sql</p>");
	} else {
		if ($pos_mes) {echo(" ... pam_implementing_entity");}
	}
?>