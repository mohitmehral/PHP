<?php
$pos_mes = FALSE;
include('conx/db_conx_open.php');
require_once 'support.php';
standard_html_header("Search Results")
?>
<?php
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
//						${$val_output}[$id] = ${$val_output}[$id] . $data_fetch[$val_output] . "<br/>";
//					}
//					${$val_output}[$id] = substr(${$val_output}[$id], 0, -5);
//				}
//			}
//		}
	}
?>
		<h1>
			Search Results
		</h1>
		<table class="sortable">
			<?php
				if ($identifier_num) { ?>
		  <thead>
			<tr>
			  <th scope="col" rowspan="2"><a href="output?sort=member_state<?php build_sortqs()?>">Member<br/>State</a></th>
			  <th scope="col" rowspan="2"><a href="output?sort=sector<?php build_sortqs()?>">Sector</a></th>
			  <th scope="col" rowspan="2">Projection<br />Scenario</th>
			  <th scope="col" rowspan="2">Name</th>
			  <th scope="col" rowspan="2">Type</th>
			  <th scope="col" rowspan="2">GHG</th>
			  <th scope="col" rowspan="2">Status</th>
			  <th scope="col" colspan="3"><nobr>Absolute Reduction</nobr><br/><nobr>[kt CO<sub>2</sub> eq. p.a.]</nobr></th>
			  <th scope="col" rowspan="2"><a href="output?sort=costs_per_tonne<?php build_sortqs()?>">Costs<br/>[EUR/t]</a></th>
			</tr>
			<tr>
			  <th scope="col">2005</th>
			  <th scope="col"><a href="output?sort=red_2010_val<?php build_sortqs()?>">2010</a></th>
			  <th scope="col">2020</th>
			</tr>
		  </thead>
			<?php } ?>
		  <tbody>
			<?php
				if ($identifier_num) {
					if ($warning_len) {echo "<p><span class=\"red\">One of your words in the 'Any Word' field was too short. The minimum length is 4 characters. It is ignored in the shown results.</span></p>";}
					$sort = $_GET['sort'];
					if ($sort) {
						$valve_name = $sort;
					} else {
						$valve_name = "pam_identifier";	
					}
					asort($$valve_name);
					reset($$valve_name);
					
					foreach ($$valve_name as $key => $value) {
						if ($green == "zebraodd") {
							$green = "zebraeven";
						} else {
							$green = "zebraodd";
						}
						echo "<tr class=\"$green\">
							<td>
							  $member_state[$key]
							</td>
							<td>
							  $sector[$key]
							</td>
							<td>
							  $with_or_with_additional_measure_output[$key]
							</td>
							<td>
							  <a href=\"details?id=$key\">$name_pam[$key]</a>
							</td>";
//							<td>
//							  $category[$key]
//							</td>
							echo "<td>
							  $type[$key]
							</td>
							<td>
							  $ghg_output[$key]
							</td>
							<td>
							  $status[$key]
							</td>
							<td class=\"number\">";
								if ($red_2005_val[$key] and $red_2005_text[$key]) {
									echo "$red_2005_val[$key]<br/><a href=\"details?id=$key\">more</a>";
								} else {
									if ($red_2005_val[$key]) {
										echo "$red_2005_val[$key]";
									} else {
										if ($red_2005_text[$key]) {
											if ($red_2005_text[$key] == $cluster[$key]) {
												echo "Cluster value";
											} else {
												echo "<a href=\"details?id=$key\">details</a>";
											}
										}
									}
								}
							echo "</td>
							<td class=\"number\">";
								if ($red_2010_val[$key] and $red_2010_text[$key]) {
									echo "$red_2010_val[$key]<br/><a href=\"details?id=$key\">more</a>";
								} else {
									if ($red_2010_val[$key]) {
										echo "$red_2010_val[$key]";
									} else {
										if ($red_2010_text[$key]) {
											if ($red_2010_text[$key] == $cluster[$key]) {
												echo "Cluster value";
											} else {
												echo "<a href=\"details?id=$key\">details</a>";
											}
										}
									}
								}
							echo "</td>
							<td class=\"number\">";
								if ($red_2020_val[$key] and $red_2020_text[$key]) {
									echo "$red_2020_val[$key]<br/><a href=\"details?id=$key\">more</a>";
								} else {
									if ($red_2020_val[$key]) {
										echo "$red_2020_val[$key]";
									} else {
										if ($red_2020_text[$key]) {
											if ($red_2020_text[$key] == $cluster[$key]) {
												echo "Cluster value";
											} else {
												echo "<a href=\"details?id=$key\">details</a>";
											}
										}
									}
								}
							echo "</td>
							<td>
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
							if ($green == "zebraodd") {
								$green = "zebraeven";
							} else {
								$green = "zebraodd";
							}
							echo "<tr class=\"$green\">
								<td>
								  &nbsp;
								</td>
								<td>
								  &nbsp;
								</td>
								<td>
								  &nbsp;
								</td>
								<td>
								  &nbsp;
								</td>
								<td>
								  &nbsp;
								</td>
								<td>
								  &nbsp;
								</td>
								<td>
								  &nbsp;
								</td>
								<td>
								  &nbsp;
								</td>
								<td>
								  &nbsp;
								</td>
								<td>
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
											$member_state = $member_state . $data_fetch['member_state'] . "<br/>";
										}
										$member_state = substr($member_state, 0, -5);
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
											$sector = $sector . $data_fetch['sector'] . "<br/>";
										}
										$sector = substr($sector, 0, -5);
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
											$type = $type . $data_fetch['type'] . "<br/>";
										}
										$type = substr($type, 0, -5);
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
											$ghg_output = $ghg_output . $data_fetch['ghg_output'] . "<br/>";
										}
										$ghg_output = substr($ghg_output, 0, -5);
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
											$status = $status . $data_fetch['status'] . "<br/>";
										}
										$status = substr($status, 0, -5);
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
											$name_pam = $name_pam . $data_fetch['name_pam'] . "<br/>";
										}
										$name_pam = substr($name_pam, 0, -5);
									}
									
									if ($green == "zebraodd") {
										$green = "zebraeven";
									} else {
										$green = "zebraodd";
									}
									echo "<tr class=\"$green\">
										<td>
										  $member_state
										</td>
										<td>
										  $sector
										</td>
										<td>
										  &nbsp;
										</td>
										<td>
										  Combined emission reduction of<br/>$name_pam
										</td>
										<td>
										  $type
										</td>
										<td>
										  $ghg_output
										</td>
										<td>
										  $status
										</td>
										<td class=\"number\">";
											if ($red_2005_val and $red_2005_text) {
												echo "$red_2005_val<br/>$red_2005_text";
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
										<td class=\"number\">";
											if ($red_2010_val and $red_2010_text) {
												echo "$red_2010_val<br/>$red_2010_text";
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
										<td class=\"number\">";
											if ($red_2020_val and $red_2020_text) {
												echo "$red_2020_val<br/>$red_2020_text";
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
					if ($warning_len) {echo "<p><span class=\"red\">One of your words in the 'Any Word' field was too short. The minimum length is 4 characters. It is ignored in the shown results.</span></p>";}
					echo "<p><span class=\"red\">Your search didn't deliver any results.</span></p>";
				}
			?>
		  </tbody>
		</table>
<?php standard_html_footer() ?>
