<?php
	// Variablen zum übergeben:
	// $where_select = "where val_with_or_with_additional_measure.id_with_or_with_additional_measure = '$id_with_or_with_additional_measure'"
	// $where_select = "where val_with_or_with_additional_measure.with_or_with_additional_measure = '$with_or_with_additional_measure'"
	// $pos_mes (positive mitteilungen anzeigen?)
	$sql = "select pam_with_or_with_additional_measure.id_with_or_with_additional_measure as id_with_or_with_additional_measure, with_or_with_additional_measure " .
		"from pam_with_or_with_additional_measure left join val_with_or_with_additional_measure on  val_with_or_with_additional_measure.id_with_or_with_additional_measure = pam_with_or_with_additional_measure.id_with_or_with_additional_measure " . $where_select;
	$pam_with_or_with_additional_measure = @mysql_query($sql);
	$pam_with_or_with_additional_measure_num = @mysql_num_rows($pam_with_or_with_additional_measure);
	if (!$pam_with_or_with_additional_measure) {
		sql_error('pam_with_or_with_additional_measure', $sql);
	} else {
		if ($pos_mes) {echo(" ... pam_with_or_with_additional_measure");}
	}
?>
