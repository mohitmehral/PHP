<?php
function select_pam_category($where_select) {
	// Variables to pass:
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
		sql_error('pam_category', $sql);
	} else {
		if ($pos_mes) {echo(" ... pam_category");}
	}
        return $pam_category;
}


function select_pam_ghg($where_select) {
	// Variables to pass:
	// $where_select = "where val_ghg.id_ghg = '$id_ghg'"
	// $where_select = "where val_ghg.ghg = '$ghg'"
	// $pos_mes (positive mitteilungen anzeigen?)
	$sql = "select pam_ghg.id_ghg as id_ghg, ghg, ghg_output " .
		"from pam_ghg left join val_ghg on val_ghg.id_ghg = pam_ghg.id_ghg " . $where_select;
	$pam_ghg = @mysql_query($sql);
	$pam_ghg_num = @mysql_num_rows($pam_ghg);
	if (!$pam_ghg) {
		sql_error('pam_ghg', $sql);
	} else {
		if ($pos_mes) {echo(" ... pam_ghg");}
	}
        return $pam_ghg;
}


function select_pam_implementing_entity($where_select) {
	// Variables to pass:
	// $where_select = "where val_implementing_entity.id_implementing_entity = '$id_implementing_entity'"
	// $where_select = "where val_implementing_entity.implementing_entity = '$implementing_entity'"
	// $pos_mes (positive mitteilungen anzeigen?)
	$sql = "select pam_implementing_entity.id_implementing_entity as id_implementing_entity, implementing_entity, specification " .
		"from pam_implementing_entity left join val_implementing_entity on val_implementing_entity.id_implementing_entity = pam_implementing_entity.id_implementing_entity " . $where_select;
	$pam_implementing_entity = @mysql_query($sql);
	$pam_implementing_entity_num = @mysql_num_rows($pam_implementing_entity);
	if (!$pam_implementing_entity) {
		sql_error('pam_implementing_entity', $sql);
	} else {
		if ($pos_mes) {echo(" ... pam_implementing_entity");}
	}
        return $pam_implementing_entity;
}


function select_pam_keywords($where_select) {
	// Variables to pass:
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
        return $pam_keywords;
}


function select_pam_member_state($where_select) {
	// Variables to pass:
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
		sql_error('pam_member_state', $sql);
	} else {
		if ($pos_mes) {echo(" ... pam_member_state");}
	}
        return $pam_member_state;
}


function select_pam_($where_select) {
	$sql = "select pam_.id_ as id_, " .
		"from pam_ left join val_ on val_.id_ = pam_.id_ " . $where_select
		
	$pam_ = @mysql_query($sql);
	$pam__num = @mysql_num_rows($pam_);
	if (!$pam_) {
		sql_error('pam_', $sql);
	} else {
		if ($pos_mes) {echo(" ... pam_");}
	}
        return $pam_;
}


function select_pam($where_select) {
	// Variables to pass:
	// $where_select = "where val_ghg.id_ghg = '$id_ghg'"
	// $where_select = "where val_ghg.ghg = '$ghg'"
	// $pos_mes (positive mitteilungen anzeigen?)
	$sql = "select id, pam_identifier, cluster, pam_no, name_pam, objective_of_measure, description_pam, start, ende, red_2005_val, red_2005_text, red_2010_val, red_2010_text, red_2015_val, red_2015_text, red_2020_val, red_2020_text, cumulative_2008_2012, explanation_basis_of_mitigation_estimates, factors_resulting_in_emission_reduction, include_common_reduction, documention_source, indicator_monitor_implementation, general_comment, reference, description_impact_on_non_ghg, costs_per_tonne, costs_per_year, costs_description, costs_documention_source, remarks " .
		"from pam " . $where_select;
	$pam = @mysql_query($sql);
	$pam_num = @mysql_num_rows($pam);
	if (!$pam) {
		sql_error('pam', $sql);
	} else {
		if ($pos_mes) {echo(" ... pam");}
	}
        return $pam;
}


function select_pam_reduces_non_ghg($where_select) {
	// Variables to pass:
	// $where_select = "where val_reduces_non_ghg.id_reduces_non_ghg = '$id_reduces_non_ghg'"
	// $where_select = "where val_reduces_non_ghg.reduces_non_ghg = '$reduces_non_ghg'"
	// $pos_mes (positive mitteilungen anzeigen?)
	$sql = "select pam_reduces_non_ghg.id_reduces_non_ghg as id_reduces_non_ghg, reduces_non_ghg " .
		"from pam_reduces_non_ghg left join val_reduces_non_ghg on val_reduces_non_ghg.id_reduces_non_ghg = pam_reduces_non_ghg.id_reduces_non_ghg " . $where_select;
	$pam_reduces_non_ghg = @mysql_query($sql);
	$pam_reduces_non_ghg_num = @mysql_num_rows($pam_reduces_non_ghg);
	if (!$pam_reduces_non_ghg) {
		sql_error('pam_reduces_non_ghg', $sql);
	} else {
		if ($pos_mes) {echo(" ... pam_reduces_non_ghg");}
	}
        return $pam_reduces_non_ghg;
}


function select_pam_related_ccpm($where_select) {
	// Variables to pass:
	// $where_select = "where val_related_ccpm.id_related_ccpm = '$id_related_ccpm'"
	// $where_select = "where val_related_ccpm.related_ccpm = '$related_ccpm'"
	// $where_select = "where val_related_ccpm.id_sector = '$id_sector'"
	// $pos_mes (positive mitteilungen anzeigen?)
	$sql = "select pam_related_ccpm.id_related_ccpm as id_related_ccpm, related_ccpm, id_sector " .
		"from pam_related_ccpm left join val_related_ccpm on val_related_ccpm.id_related_ccpm = pam_related_ccpm.id_related_ccpm " . $where_select .
		"order by id_sector, related_ccpm";
	$pam_related_ccpm = @mysql_query($sql);
	$pam_related_ccpm_num = @mysql_num_rows($pam_related_ccpm);
	if (!$pam_related_ccpm) {
		sql_error('pam_related_ccpm', $sql);
	} else {
		if ($pos_mes) {echo(" ... pam_related_ccpm");}
	}
        return $pam_related_ccpm;
}


function select_pam_sector($where_select) {
	// Variables to pass:
	// $where_select = "where val_sector.id_sector = '$id_sector'"
	// $where_select = "where val_sector.sector = '$sector'"
	// $pos_mes (positive mitteilungen anzeigen?)
	$sql = "select pam_sector.id_sector as id_sector, sector " .
		"from pam_sector left join val_sector on val_sector.id_sector = pam_sector.id_sector " . $where_select;
	$pam_sector = @mysql_query($sql);
	$pam_sector_num = @mysql_num_rows($pam_sector);
	if (!$pam_sector) {
		sql_error('pam_sector', $sql);
	} else {
		if ($pos_mes) {echo(" ... pam_sector");}
	}
        return $pam_sector;
}


function select_pam_status($where_select) {
	// Variables to pass:
	// $where_select = "where val_status.id_status = '$id_status'"
	// $where_select = "where val_status.status = '$status'"
	// $pos_mes (positive mitteilungen anzeigen?)
	$sql = "select pam_status.id_status as id_status, status " .
		"from pam_status left join val_status on val_status.id_status = pam_status.id_status " . $where_select;
	$pam_status = @mysql_query($sql);
	$pam_status_num = @mysql_num_rows($pam_status);
	if (!$pam_status) {
		sql_error('pam_status', $sql);
	} else {
		if ($pos_mes) {echo(" ... pam_status");}
	}
        return $pam_status;
}


function select_pam_type($where_select) {
	// Variables to pass:
	// $where_select = "where val_type.id_type = '$id_type'"
	// $where_select = "where val_type.type = '$type'"
	// $pos_mes (positive mitteilungen anzeigen?)
	$sql = "select pam_type.id_type as id_type, type " .
		"from pam_type left join val_type on val_type.id_type = pam_type.id_type " . $where_select;
	$pam_type = @mysql_query($sql);
	$pam_type_num = @mysql_num_rows($pam_type);
	if (!$pam_type) {
		sql_error('pam_type', $sql);
	} else {
		if ($pos_mes) {echo(" ... pam_type");}
	}
        return $pam_type;
}


function select_pam_with_or_with_additional_measure($where_select) {
	// Variables to pass:
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
        return $pam_with_or_with_additional_measure;
}


function select_val_category($where_select) {
	// Variables to pass:
	// $where_select = "where val_category.id_category = '$id_category'"
	// $where_select = "where val_category.category = '$category'"
	// $where_select = "where val_category.id_sector = '$id_sector'"
	// $pos_mes (positive mitteilungen anzeigen?)
	$sql = "select id_category, category, id_sector " .
		"from val_category " . $where_select .
		"order by category";
	$val_category = @mysql_query($sql);
	$val_category_num = @mysql_num_rows($val_category);
	if (!$val_category) {
		sql_error('val_category', $sql);
	} else {
		if ($pos_mes) {echo(" ... val_category");}
	}
        return $val_category;
}


function select_val_ghg($where_select) {
	// Variables to pass:
	// $where_select = "where val_ghg.id_ghg = '$id_ghg'"
	// $where_select = "where val_ghg.ghg = '$ghg'"
	// $pos_mes (positive mitteilungen anzeigen?)
	$sql = "select id_ghg, ghg, ghg_output " .
		"from val_ghg " . $where_select;
	$val_ghg = @mysql_query($sql);
	$val_ghg_num = @mysql_num_rows($val_ghg);
	if (!$val_ghg) {
		sql_error('val_ghg', $sql);
	} else {
		if ($pos_mes) {echo(" ... val_ghg");}
	}
        return $val_ghg;
}


function select_val_implementing_entity($where_select) {
	// Variables to pass:
	// $where_select = "where val_implementing_entity.id_implementing_entity = '$id_implementing_entity'"
	// $where_select = "where val_implementing_entity.implementing_entity = '$implementing_entity'"
	// $pos_mes (positive mitteilungen anzeigen?)
	$sql = "select id_implementing_entity, implementing_entity " .
		"from val_implementing_entity " . $where_select;
	$val_implementing_entity = @mysql_query($sql);
	$val_implementing_entity_num = @mysql_num_rows($val_implementing_entity);
	if (!$val_implementing_entity) {
		sql_error('val_implementing_entity', $sql);
	} else {
		if ($pos_mes) {echo(" ... val_implementing_entity");}
	}
        return $val_implementing_entity;
}


function select_val_keywords($where_select) {
	// Variables to pass:
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
        return $val_keywords;
}


function select_val_member_state($where_select) {
	// Variables to pass:
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
        return $val_member_state;
}


function select_val_reduces_non_ghg($where_select) {
	// Variables to pass:
	// $where_select = "where val_reduces_non_ghg.id_reduces_non_ghg = '$id_reduces_non_ghg'"
	// $where_select = "where val_reduces_non_ghg.reduces_non_ghg = '$reduces_non_ghg'"
	// $pos_mes (positive mitteilungen anzeigen?)
	$sql = "select id_reduces_non_ghg, reduces_non_ghg " .
		"from val_reduces_non_ghg " . $where_select;
	$val_reduces_non_ghg = @mysql_query($sql);
	$val_reduces_non_ghg_num = @mysql_num_rows($val_reduces_non_ghg);
	if (!$val_reduces_non_ghg) {
		sql_error('val_reduces_non_ghg', $sql);
	} else {
		if ($pos_mes) {echo(" ... val_reduces_non_ghg");}
	}
        return $val_reduces_non_ghg;
}


function select_val_related_ccpm($where_select) {
	// Variables to pass:
	// $where_select = "where val_related_ccpm.id_related_ccpm = '$id_related_ccpm'"
	// $where_select = "where val_related_ccpm.related_ccpm = '$related_ccpm'"
	// $where_select = "where val_related_ccpm.id_sector = '$id_sector'"
	// $pos_mes (positive mitteilungen anzeigen?)
	$sql = "select id_related_ccpm, related_ccpm, id_sector " .
		"from val_related_ccpm " . $where_select .
		"order by id_sector, related_ccpm";
	$val_related_ccpm = @mysql_query($sql);
	$val_related_ccpm_num = @mysql_num_rows($val_related_ccpm);
	if (!$val_related_ccpm) {
		sql_error('val_related_ccpm', $sql);
	} else {
		if ($pos_mes) {echo(" ... val_related_ccpm");}
	}
        return $val_related_ccpm;
}


function select_val_sector($where_select) {
	// Variables to pass:
	// $where_select = "where val_sector.id_sector = '$id_sector'"
	// $where_select = "where val_sector.sector = '$sector'"
	// $pos_mes (positive mitteilungen anzeigen?)
	$sql = "select id_sector, sector " .
		"from val_sector " . $where_select;
	$val_sector = @mysql_query($sql);
	$val_sector_num = @mysql_num_rows($val_sector);
	if (!$val_sector) {
		sql_error('val_sector', $sql);
	} else {
		if ($pos_mes) {echo("<p>val_sector</p><p>$sql</p>");}
	}
        return $val_sector;
}


function select_val_status($where_select) {
	// Variables to pass:
	// $where_select = "where val_status.id_status = '$id_status'"
	// $where_select = "where val_status.status = '$status'"
	// $pos_mes (positive mitteilungen anzeigen?)
	$sql = "select id_status, status " .
		"from val_status " . $where_select;
	$val_status = @mysql_query($sql);
	$val_status_num = @mysql_num_rows($val_status);
	if (!$val_status) {
		sql_error('val_status', $sql);
	} else {
		if ($pos_mes) {echo(" ... val_status");}
	}
        return $val_status;
}


function select_val_type($where_select) {
	// Variables to pass:
	// $where_select = "where val_type.id_type = '$id_type'"
	// $where_select = "where val_type.type = '$type'"
	// $pos_mes (positive mitteilungen anzeigen?)
	$sql = "select id_type, type " .
		"from val_type " . $where_select;
	$val_type = @mysql_query($sql);
	$val_type_num = @mysql_num_rows($val_type);
	if (!$val_type) {
		sql_error('val_type', $sql);
	} else {
		if ($pos_mes) {echo(" ... val_type");}
	}
        return $val_type;
}


function select_val_with_or_with_additional_measure($where_select) {
	// Variables to pass:
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
        return $val_with_or_with_additional_measure;
}


?>
