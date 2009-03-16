<?php
	// Variablen zum übergeben:
	// $where_select = "where val_with_or_with_additional_measure.id_with_or_with_additional_measure = '$id_with_or_with_additional_measure'"
	// $where_select = "where val_with_or_with_additional_measure.with_or_with_additional_measure = '$with_or_with_additional_measure'"
	// $pos_mes (positive mitteilungen anzeigen?)
	$sql = "select id_with_or_with_additional_measure, with_or_with_additional_measure, with_or_with_additional_measure_output " .
		"from val_with_or_with_additional_measure " . $where_select;
	$val_with_or_with_additional_measure = @mysql_query($sql);
	$val_with_or_with_additional_measure_num = @mysql_num_rows($val_with_or_with_additional_measure);
	if (!$val_with_or_with_additional_measure) {
		sql_error('val_with_or_with_additional_measure', $sql);
	} else {
		if ($pos_mes) {echo(" ... val_with_or_with_additional_measure");}
	}
?>
