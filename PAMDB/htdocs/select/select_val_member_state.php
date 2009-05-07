<?php
	// Variablen zum übergeben:
	// $where_select = "where val_member_state.id_member_state = '$id_member_state'"
	// $where_select = "where val_member_state.member_state = '$member_state'"
	// $where_select = "where val_member_state.eu_10 = '$eu_10'"
	// $where_select = "where val_member_state.eu_15 = '$eu_15'"
	// $pos_mes (positive mitteilungen anzeigen?)
	$sql = "select id_member_state, member_state, eu_10, eu_15 " .
		"from val_member_state " . $where_select;
	$val_member_state = @mysql_query($sql);
	$val_member_state_num = @mysql_num_rows($val_member_state);
	if (!$val_member_state) {
		sql_error('val_member_state', $sql);
	} else {
		if ($pos_mes) {echo(" ... val_member_state");}
	}
?>
