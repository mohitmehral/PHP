<?php
	// Variablen zum übergeben:
	// $where_select = "where val_member_state.id_member_state = '$id_member_state'"
	// $where_select = "where val_member_state.member_state = '$member_state'"
	// $where_select = "where val_member_state.eu_10 = '$eu_10'"
	// $where_select = "where val_member_state.eu_15 = '$eu_15'"
	// $pos_mes (positive mitteilungen anzeigen?)
	$sql = "select pam_member_state.id_member_state as id_member_state, member_state, eu_10, eu_15 " .
		"from pam_member_state left join val_member_state on val_member_state.id_member_state = pam_member_state.id_member_state " . $where_select;
	$pam_member_state = @mysql_query($sql);
	$pam_member_state_num = @mysql_num_rows($pam_member_state);
	if (!$pam_member_state) {
		echo("<p>Es gab einen Fehler beim Zugriff auf die Tabelle \"pam_member_state\".</p><p>$sql</p>");
	} else {
		if ($pos_mes) {echo(" ... pam_member_state");}
	}
?>