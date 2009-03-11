<?php
	// Variablen zum übergeben:
	// $where_select = "where val_ghg.id_ghg = '$id_ghg'"
	// $where_select = "where val_ghg.ghg = '$ghg'"
	// $pos_mes (positive mitteilungen anzeigen?)
	$sql = "select id, pam_identifier, cluster, pam_no, name_pam, objective_of_measure, description_pam, start, ende, red_2005_val, red_2005_text, red_2010_val, red_2010_text, red_2015_val, red_2015_text, red_2020_val, red_2020_text, cumulative_2008_2012, explanation_basis_of_mitigation_estimates, factors_resulting_in_emission_reduction, include_common_reduction, documention_source, indicator_monitor_implementation, general_comment, reference, description_impact_on_non_ghg, costs_per_tonne, costs_per_year, costs_description, costs_documention_source, remarks " .
		"from pam " . $where_select;
	$pam = @mysql_query($sql);
	$pam_num = @mysql_num_rows($pam);
	if (!$pam) {
		echo("<p>Es gab einen Fehler beim Zugriff auf die Tabelle \"pam\".</p><p>$sql</p>");
	} else {
		if ($pos_mes) {echo(" ... pam");}
	}
?>