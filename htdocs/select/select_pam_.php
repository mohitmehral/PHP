<?php
	$sql = "select pam_.id_ as id_, " .
		"from pam_ left join val_ on val_.id_ = pam_.id_ " . $where_select
		
	$pam_ = @mysql_query($sql);
	$pam__num = @mysql_num_rows($pam_);
	if (!$pam_) {
		sql_error('pam_', $sql);
	} else {
		if ($pos_mes) {echo(" ... pam_");}
	}
?>
