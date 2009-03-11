<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
  include('conx/db_conx_open.php');
//  $pos_mes = TRUE;
// getting Identifier from database with the user defined filter
	
	unset($where_select);
	$sql = "SELECT id FROM pam WHERE name_pam is not NULL ";
	
	$id_member_state = $_GET['id_member_state'];
	if ($id_member_state) {
		if (is_array($id_member_state)) {
			if (!in_array("select_all", $id_member_state)) {
				if (in_array("1", $id_member_state) or in_array("2", $id_member_state)) {
					$where_select = $where_select . "AND id IN (SELECT id FROM pam_member_state LEFT JOIN val_member_state ON val_member_state.id_member_state = pam_member_state.id_member_state WHERE ";
					foreach ($id_member_state as $value) {
						if ($value == "1") {
							$where_select = $where_select . "eu_15 = '1' OR ";
						} else {
							if ($value == "2") {
								$where_select = $where_select . "eu_10 = '1' OR ";
							} else {
								$where_select = $where_select . "pam_member_state.id_member_state = '$value' OR ";
							}
						}
					}
					$where_select = substr($where_select, 0, -4) . ") ";
				} else {
					$where_select = $where_select . "AND id IN (SELECT id FROM pam_member_state WHERE ";
					foreach ($id_member_state as $value) {
						$where_select = $where_select . "id_member_state = '$value' OR ";
					}
					$where_select = substr($where_select, 0, -4) . ") ";
				}
			}
		} else {
			if ($id_member_state != "select_all") {
				if ($id_member_state == "1" or $id_member_state == "2") {
					$where_select = $where_select . "AND id IN (SELECT id FROM pam_member_state LEFT JOIN val_member_state ON val_member_state.id_member_state = pam_member_state.id_member_state WHERE ";
					if ($id_member_state == "1") {
						$where_select = $where_select . "eu_15 = '1') ";
					} else {
						$where_select = $where_select . "eu_10 = '1') ";
					}
				} else {
					$where_select = $where_select . "AND id IN (SELECT id FROM pam_member_state WHERE ";
					$where_select = $where_select . "id_member_state = '$id_member_state') ";
				}
			}
		}
	}
	
	$valves = array("sector","ghg","type","status","category","keywords","related_ccpm","related_ccpm","with_or_with_additional_measure");
	
	foreach($valves as $valve) {
		$val_id = "id_" . $valve;
		$$val_id = $_GET[$val_id];
		$val_pam = "pam_" . $valve;
		if ($$val_id) {
			if (is_array($$val_id)) {
				if (in_array("no_value", $$val_id)) {
					$where_select = $where_select . "AND (id NOT IN (SELECT id FROM $val_pam) OR ";
					if (!in_array("select_all", $$val_id)) {
						$where_select_1 = "id IN (SELECT id FROM $val_pam WHERE ";
						foreach ($$val_id as $value) {
							if ($value != "no_value") {
								$where_select_1 = $where_select_1 . "$val_id = '$value' OR ";
							}
						}
						if ($where_select_1 != "id IN (SELECT id FROM $val_pam WHERE ") {
							$where_select_1 = substr($where_select_1, 0, -4) . ")) ";
							$where_select = $where_select . $where_select_1;
						} else {
							$where_select = substr($where_select, 0, -4) . ") ";
						}
					} else {
						$where_select = substr($where_select, 0, -4) . ") ";
					}
				} else {
					if (!in_array("select_all", $$val_id)) {
						$where_select = $where_select . "AND id IN (SELECT id FROM $val_pam WHERE ";
						foreach ($$val_id as $value) {
							$where_select = $where_select . "$val_id = '$value' OR ";
						}
						$where_select = substr($where_select, 0, -4) . ") ";
					}
				}
			} else {
				if ($$val_id != "select_all") {
					if ($$val_id == "no_value") {
						$where_select = $where_select . "AND id NOT IN (SELECT id FROM $val_pam) ";
					}
					$where_select = $where_select . "AND id IN (SELECT id FROM $val_pam WHERE ";
					$where_select = $where_select . "$val_id = '$$val_id') ";
				}
			}
		}
	}
	
	$any_word = $_GET['any_word'];
	if ($any_word) {
		$where_select = $where_select . "AND MATCH (name_pam, objective_of_measure, description_pam, explanation_basis_of_mitigation_estimates, factors_resulting_in_emission_reduction, documention_source, indicator_monitor_implementation, general_comment, reference, description_impact_on_non_ghg, costs_description, costs_documention_source) AGAINST ('*" . $any_word . "*' IN BOOLEAN MODE) ";
	}

	if ($where_select) {
		$sql = $sql . $where_select;
	}
	
	$identifier = @mysql_query($sql);
	$identifier_num = @mysql_num_rows($identifier);
	if (!$identifier) {
		echo("<p>Es gab einen Fehler beim Zugriff auf die Datenbank.</p><p>$sql</p>");
	} else {
		if ($pos_mes) {echo("<p>identifier</p><p>$sql</p>");}
	}
	
	unset($ary_id);
	
	while ($identifier_fetch = mysql_fetch_array($identifier)) {
		$ary_id[] = $identifier_fetch['id'];
	}

// getting the fields defined in value/table.php for each Identifier which came out of the user defined filter
	
	if ($identifier_num) {
		unset($name_pam, $red_2005_val, $red_2005_text, $red_2010_val, $red_2010_text, $red_2020_val, $red_2020_text, $costs_per_tonne);
		reset($ary_id);
		
		foreach ($ary_id as $id) {
			$sql = "SELECT id, pam_identifier, cluster, name_pam, red_2005_val, red_2005_text, red_2010_val, red_2010_text, red_2020_val, red_2020_text, costs_per_tonne FROM pam WHERE id = '$id'";
			
			$data = @mysql_query($sql);
			$data_num = @mysql_num_rows($data);
			if (!$data) {
				echo("<p>Es gab einen Fehler beim Zugriff auf die Datenbank.</p><p>$sql</p>");
			} else {
				if ($pos_mes) {echo("<p>data</p><p>$sql</p>");}
			}
			
			$data_fetch = mysql_fetch_array($data);
			
			$name_pam[$id] = $data_fetch['name_pam'];
			$pam_identifier[$id] = $data_fetch['pam_identifier'];
			$cluster[$id] = $data_fetch['cluster'];
//			$red_2005_val[$id] = $data_fetch['red_2005_val'];
			$red_2005_val[$id] = number_format($data_fetch['red_2005_val'], 0, '.', ',');
			$red_2005_text[$id] = $data_fetch['red_2005_text'];
//			$red_2010_val[$id] = $data_fetch['red_2010_val'];
			$red_2010_val[$id] = number_format($data_fetch['red_2010_val'], 0, '.', ',');
			$red_2010_text[$id] = $data_fetch['red_2010_text'];
//			$red_2020_val[$id] = $data_fetch['red_2020_val'];
			$red_2020_val[$id] = number_format($data_fetch['red_2020_val'], 0, '.', ',');
			$red_2020_text[$id] = $data_fetch['red_2020_text'];
			$costs_per_tonne[$id] = $data_fetch['costs_per_tonne'];
		}
		
		$valves = array("member_state","sector","ghg","type","status","category","keywords","related_ccpm","with_or_with_additional_measure");
	
//		foreach($valves as $valve) {
//			$val_id = "id_" . $valve;
//			if ($valve == "ghg" or $valve == "with_or_with_additional_measure") {
//				$val_output = $valve . "_output";
//			} else {
//				$val_output = $valve;
//			}
//			$val_pam = "pam_" . $valve;
//			$val_val = "val_" . $valve;
//		
//			unset($$valve);
//			reset($ary_id);
//		
//			foreach ($ary_id as $id) {
//				$sql = "SELECT $val_output FROM $val_val JOIN $val_pam ON " . $val_val . "." . $val_id . " = " . $val_pam . "." . $val_id . " WHERE id = '$id' ORDER BY $val_output";
//				$data = @mysql_query($sql);
//				$data_num = @mysql_num_rows($data);
//				if (!$data) {
//					echo("<p>Es gab einen Fehler beim Zugriff auf die Datenbank.</p><p>$sql $data $data_num</p>");
//				} else {
//					if ($pos_mes) {echo("<p>$val_output</p><p>$sql</p>");}
//				}
//				
//				if ($data_num) {
//					while ($data_fetch = mysql_fetch_array($data)) {
//						${$val_output}[$id] = ${$val_output}[$id] . $data_fetch[$val_output] . "<br>";
//					}
//					${$val_output}[$id] = substr(${$val_output}[$id], 0, -4);
//				}
//			}
//		}
	}
?>
<html>
	<head>
		<title>European Climate Change Programme (ECCP) - Database on Policies and Measures in Europe</title>
		<link href="frm.css" rel="stylesheet" type="text/css">
	</head>
	<body>
		<table>
		  <tr>
			<td><img src="images/eccp.jpg" alt="ECCP"></td>
			<td style="width:100%">&nbsp;</td>
			<td><img src="images/oi.jpg" alt="OEko-Institut e.V."></td>
		  </tr>
		</table>
		<p class="head_green"> European Climate Change Programme (ECCP)</p>
		<p class="head_red"> Database on Policies and Measures in Europe</p>
		<hr class="green">
		<p class="head_green">Search Results&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="index.php">normal search mode</a> | <a href="sector.php">expert search mode</a></p>
		<table>
		  <thead>
			<?php
				if ($identifier_num) {include('header.php');}
			?>
		  </thead>
		  <tbody>
			<?php
				if ($identifier_num) {
					if ($warning_len) {echo "<p><font class=\"red\">One of your words in the 'Any Word' field was too short. The minimum length is 4 characters. It is ignored in the shown results.</font></p>";}
					$sort = $_GET['sort'];
					if ($sort) {
						$valve_name = $sort;
					} else {
						$valve_name = "pam_identifier";	
					}
					asort($$valve_name);
					reset($$valve_name);
					
					foreach ($$valve_name as $key => $value) {
						if ($green == "#eeFFdd") {
							$green = "whith";
						} else {
							$green = "#eeFFdd";
						}
						echo "<tr style=\"background-color:$green\">
							<td class=\"output\">
							  $member_state[$key]
							</td>
							<td class=\"output\">
							  $sector[$key]
							</td>
							<td class=\"output\">
							  $with_or_with_additional_measure_output[$key]
							</td>
							<td class=\"output\">
							  <a href=\"details.php?id=$key\" target=\"_blank\">$name_pam[$key]</a>
							</td>";
//							<td class=\"output\">
//							  $category[$key]
//							</td>
							echo "<td class=\"output\">
							  $type[$key]
							</td>
							<td class=\"output\">
							  $ghg_output[$key]
							</td>
							<td class=\"output\">
							  $status[$key]
							</td>
							<td class=\"output\" style=\"text-align:right\">";
								if ($red_2005_val[$key] and $red_2005_text[$key]) {
									echo "$red_2005_val[$key]<br><a href=\"details.php?id=$key\" target=\"_blank\">more</a>";
								} else {
									if ($red_2005_val[$key]) {
										echo "$red_2005_val[$key]";
									} else {
										if ($red_2005_text[$key]) {
											if ($red_2005_text[$key] == $cluster[$key]) {
												echo "Cluster value";
											} else {
												echo "<a href=\"details.php?id=$key\" target=\"_blank\">details</a>";
											}
										}
									}
								}
							echo "</td>
							<td class=\"output\" style=\"text-align:right\">";
								if ($red_2010_val[$key] and $red_2010_text[$key]) {
									echo "$red_2010_val[$key]<br><a href=\"details.php?id=$key\" target=\"_blank\">more</a>";
								} else {
									if ($red_2010_val[$key]) {
										echo "$red_2010_val[$key]";
									} else {
										if ($red_2010_text[$key]) {
											if ($red_2010_text[$key] == $cluster[$key]) {
												echo "Cluster value";
											} else {
												echo "<a href=\"details.php?id=$key\" target=\"_blank\">details</a>";
											}
										}
									}
								}
							echo "</td>
							<td class=\"output\" style=\"text-align:right\">";
								if ($red_2020_val[$key] and $red_2020_text[$key]) {
									echo "$red_2020_val[$key]<br><a href=\"details.php?id=$key\" target=\"_blank\">more</a>";
								} else {
									if ($red_2020_val[$key]) {
										echo "$red_2020_val[$key]";
									} else {
										if ($red_2020_text[$key]) {
											if ($red_2020_text[$key] == $cluster[$key]) {
												echo "Cluster value";
											} else {
												echo "<a href=\"details.php?id=$key\" target=\"_blank\">details</a>";
											}
										}
									}
								}
							echo "</td>
							<td class=\"output\">
							  $costs_per_tonne[$key]
							</td>
						</tr>";
					}						
					$cluster = array_unique($cluster);
					if (in_array('', $cluster)) {
						unset($$val_id[array_search('', $cluster)]);
					}
					if (in_array(NULL, $cluster)) {
						unset($$val_id[array_search(NULL, $cluster)]);
					}
					if (in_array(' ', $cluster)) {
						unset($$val_id[array_search(' ', $cluster)]);
					}
					reset($cluster);
					
					if (count($cluster)) {					
						for ($i = 0; $i < 0; $i++) {
							if ($green == "#eeFFdd") {
								$green = "whith";
							} else {
								$green = "#eeFFdd";
							}
							echo "<tr style=\"background-color:$green\">
								<td class=\"output\">
								  &nbsp;
								</td>
								<td class=\"output\">
								  &nbsp;
								</td>
								<td class=\"output\">
								  &nbsp;
								</td>
								<td class=\"output\">
								  &nbsp;
								</td>
								<td class=\"output\">
								  &nbsp;
								</td>
								<td class=\"output\">
								  &nbsp;
								</td>
								<td class=\"output\">
								  &nbsp;
								</td>
								<td class=\"output\">
								  &nbsp;
								</td>
								<td class=\"output\">
								  &nbsp;
								</td>
								<td class=\"output\">
								  &nbsp;
								</td>
								<td>
									&nbsp;
								</td>
							</tr>";
						}
						
						foreach ($cluster as $pam_identifier) {
							unset($red_2005_val, $red_2005_text, $red_2010_val, $red_2010_text, $red_2020_val, $red_2020_text, $member_state, $sector, $name_pam, $type, $ghg_output, $status);
							$sql = "SELECT red_2005_val, red_2005_text, red_2010_val, red_2010_text, red_2020_val, red_2020_text FROM pam WHERE pam_identifier = '$pam_identifier'";
							$clusters = @mysql_query($sql);
							$clusters_num = @mysql_num_rows($clusters);
							if (!$clusters) {
								echo("<p>Es gab einen Fehler beim Zugriff auf die Datenbank.</p><p>$sql</p>");
							} else {
								if ($pos_mes) {echo("<p>clusters</p><p>$sql</p>");}
							}
							
							if ($clusters_num) {
								while ($clusters_fetch = mysql_fetch_array($clusters)) {
									$red_2005_val = $clusters_fetch['red_2005_val'];
									$red_2005_text = $clusters_fetch['red_2005_text'];
									$red_2010_val = $clusters_fetch['red_2010_val'];
									$red_2010_text = $clusters_fetch['red_2010_text'];
									$red_2020_val = $clusters_fetch['red_2020_val'];
									$red_2020_text = $clusters_fetch['red_2020_text'];
		
									$sql = "SELECT member_state FROM val_member_state JOIN pam_member_state ON val_member_state.id_member_state = pam_member_state.id_member_state WHERE id IN (SELECT id FROM pam WHERE cluster = '$pam_identifier') GROUP BY member_state ORDER BY member_state";
									$data = @mysql_query($sql);
									$data_num = @mysql_num_rows($data);
									if (!$data) {
										echo("<p>Es gab einen Fehler beim Zugriff auf die Datenbank.</p><p>$sql</p>");
									} else {
										if ($pos_mes) {echo("<p>member_state</p><p>$sql</p>");}
									}
									if ($data_num) {
										while ($data_fetch = mysql_fetch_array($data)) {
											$member_state = $member_state . $data_fetch['member_state'] . "<br>";
										}
										$member_state = substr($member_state, 0, -4);
									}
		
									$sql = "SELECT sector FROM val_sector JOIN pam_sector ON val_sector.id_sector = pam_sector.id_sector WHERE id IN (SELECT id FROM pam WHERE cluster = '$pam_identifier') GROUP BY sector ORDER BY sector";
									$data = @mysql_query($sql);
									$data_num = @mysql_num_rows($data);
									if (!$data) {
										echo("<p>Es gab einen Fehler beim Zugriff auf die Datenbank.</p><p>$sql</p>");
									} else {
										if ($pos_mes) {echo("<p>sector</p><p>$sql</p>");}
									}
									if ($data_num) {
										while ($data_fetch = mysql_fetch_array($data)) {
											$sector = $sector . $data_fetch['sector'] . "<br>";
										}
										$sector = substr($sector, 0, -4);
									}
		
									$sql = "SELECT type FROM val_type JOIN pam_type ON val_type.id_type = pam_type.id_type WHERE id IN (SELECT id FROM pam WHERE cluster = '$pam_identifier') GROUP BY type ORDER BY type";
									$data = @mysql_query($sql);
									$data_num = @mysql_num_rows($data);
									if (!$data) {
										echo("<p>Es gab einen Fehler beim Zugriff auf die Datenbank.</p><p>$sql</p>");
									} else {
										if ($pos_mes) {echo("<p>type</p><p>$sql</p>");}
									}
									if ($data_num) {
										while ($data_fetch = mysql_fetch_array($data)) {
											$type = $type . $data_fetch['type'] . "<br>";
										}
										$type = substr($type, 0, -4);
									}
		
									$sql = "SELECT ghg_output FROM val_ghg JOIN pam_ghg ON val_ghg.id_ghg = pam_ghg.id_ghg WHERE id IN (SELECT id FROM pam WHERE cluster = '$pam_identifier') GROUP BY ghg ORDER BY ghg";
									$data = @mysql_query($sql);

									$data_num = @mysql_num_rows($data);
									if (!$data) {
										echo("<p>Es gab einen Fehler beim Zugriff auf die Datenbank.</p><p>$sql</p>");
									} else {
										if ($pos_mes) {echo("<p>ghg</p><p>$sql</p>");}
									}
									if ($data_num) {
										while ($data_fetch = mysql_fetch_array($data)) {
											$ghg_output = $ghg_output . $data_fetch['ghg_output'] . "<br>";
										}
										$ghg_output = substr($ghg_output, 0, -4);
									}
		
									$sql = "SELECT status FROM val_status JOIN pam_status ON val_status.id_status = pam_status.id_status WHERE id IN (SELECT id FROM pam WHERE cluster = '$pam_identifier') GROUP BY status ORDER BY status";
									$data = @mysql_query($sql);
									$data_num = @mysql_num_rows($data);
									if (!$data) {
										echo("<p>Es gab einen Fehler beim Zugriff auf die Datenbank.</p><p>$sql</p>");
									} else {
										if ($pos_mes) {echo("<p>status</p><p>$sql</p>");}
									}
									if ($data_num) {
										while ($data_fetch = mysql_fetch_array($data)) {
											$status = $status . $data_fetch['status'] . "<br>";
										}
										$status = substr($status, 0, -4);
									}
		
									$sql = "SELECT pam_identifier as name_pam FROM pam WHERE cluster = '$pam_identifier' and pam_identifier != '$pam_identifier' GROUP BY pam_identifier ORDER BY pam_identifier";
									$data = @mysql_query($sql);
									$data_num = @mysql_num_rows($data);
									if (!$data) {
										echo("<p>Es gab einen Fehler beim Zugriff auf die Datenbank.</p><p>$sql</p>");
									} else {
										if ($pos_mes) {echo("<p>pam_identifier</p><p>$sql</p>");}
									}
									if ($data_num) {
										while ($data_fetch = mysql_fetch_array($data)) {
											$name_pam = $name_pam . $data_fetch['name_pam'] . "<br>";
										}
										$name_pam = substr($name_pam, 0, -4);
									}
									
									if ($green == "#eeFFdd") {
										$green = "whith";
									} else {
										$green = "#eeFFdd";
									}
									echo "<tr style=\"background-color:$green\">
										<td class=\"output\">
										  $member_state
										</td>
										<td class=\"output\">
										  $sector
										</td>
										<td class=\"output\">
										  &nbsp;
										</td>
										<td class=\"output\">
										  Combined emission reduction of<br>$name_pam
										</td>
										<td class=\"output\">
										  $type
										</td>
										<td class=\"output\">
										  $ghg_output
										</td>
										<td class=\"output\">
										  $status
										</td>
										<td class=\"output\" style=\"text-align:right\">";
											if ($red_2005_val and $red_2005_text) {
												echo "$red_2005_val<br>$red_2005_text";
											} else {
												if ($red_2005_val) {
													echo "$red_2005_val";
												} else {
													if ($red_2005_text) {
														echo "$red_2005_text";
													} else {
														echo "&nbsp;";
													}
												}
											}
										echo "</td>
										<td class=\"output\" style=\"text-align:right\">";
											if ($red_2010_val and $red_2010_text) {
												echo "$red_2010_val<br>$red_2010_text";
											} else {
												if ($red_2010_val) {
													echo "$red_2010_val";
												} else {
													if ($red_2010_text) {
														echo "$red_2010_text";
													} else {
														echo "&nbsp;";
													}
												}
											}
										echo "</td>
										<td class=\"output\" style=\"text-align:right\">";
											if ($red_2020_val and $red_2020_text) {
												echo "$red_2020_val<br>$red_2020_text";
											} else {
												if ($red_2020_val) {
													echo "$red_2020_val";
												} else {
													if ($red_2020_text) {
														echo "$red_2020_text";
													} else {
														echo "&nbsp;";
													}
												}
											}
										echo "</td>
										<td>
											&nbsp;
										</td>
									</tr>";
								}
							}
						}
					}
				} else {
					if ($warning_len) {echo "<p><font class=\"red\">One of your words in the 'Any Word' field was too short. The minimum length is 4 characters. It is ignored in the shown results.</font></p>";}
					echo "<p><font class=\"red\">Your search didn't deliver any results.</font></p>";
				}
			?>
		  </tbody>
		</table>
	</body>
</html>
