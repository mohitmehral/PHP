<?php
require_once 'support.php';
standard_html_header("Search Results");

require_once 'config.inc.php';
require_once 'DB.php';
require_once 'Model.php';
require_once 'View.php';
require_once 'Controller.php';

require_once 'StarQuery.php';
require_once 'WhereClause.php';
require_once 'Dimension.php';
require_once 'FulltextMatch.php';

try {
    DB::vInit();
?>
<?php
// getting Identifier from database with the user defined filter
	
	unset($where_select);
	$sql = "SELECT id FROM pam WHERE name_pam is not NULL ";

    $q = new StarQuery('pam', 'id');
    $whrT = new WhereClause();
    $whrT->vNotNull('name_pam');
    $q->vAddWhere($whrT);
	
    if (null != ($rgFilter = Controller::rgFilterFromRequest('id_member_state'))) {
        if (!in_array('select_all', $rgFilter)) {
            if (in_array('1', $rgFilter)) {
                //$rgFilter = Model::rgGetEu15StateIds();
            } else if (in_array('2', $rgFilter)) {
                //$rgFilter = Model::rgGetEu10StateIds();
            }
            $dim = new Dimension('pam_member_state', 'id');
            $dim->vSetFilter('id_member_state', $rgFilter);
            $q->vAddDimension($dim);
        }
    }

	$valves = array("sector","ghg","type","status","category","keywords","related_ccpm","related_ccpm","with_or_with_additional_measure");
	
	foreach($valves as $valve) {
		$val_id = "id_" . $valve;
		$val_pam = "pam_" . $valve;
        
        if (null != ($rgFilter = Controller::rgFilterFromRequest($val_id))) {
            if (in_array('no_value', $rgFilter) && in_array('select_all', $rgFilter)) {
                // allowing all dimension values plus NULL is equivalent
                // to ignoring the dimension table completely.
                continue;
            }
            $dim = new Dimension($val_pam, 'id');
            if (in_array('no_value', $rgFilter)) {
                $dim->vAllowNull();
            }
            if (!in_array('select_all', $rgFilter)) {
                $dim->vSetFilter($val_id, $rgFilter);
            }
            $q->vAddDimension($dim);
        }
    }

    if (!empty($_GET['any_word'])) {
        $whrMatch = new FulltextMatch($_GET['any_word'], Model::rgGetPamTextFields());
        $q->vAddWhere($whrMatch);
    }

    $sql = $q->sqlRender(array('id'));
    View::vRenderInfoBox($sql);
    
    $identifier_num = 0;
    foreach (DB::rgSelectRows($sql) as $mpRow) {
        $identifier_num++;
        $id = $mpRow['id'];
        $data_fetch = Model::mpGetPamById($id);
        
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
//					sql_error($val_val, $sql);
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
							<td class=\"number\">
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
								sql_error('pam', $sql);
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
										sql_error('val_member_state', $sql);
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
										sql_error('val_sector', $sql);
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
										sql_error('val_type', $sql);
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
										sql_error('ghg_outut', $sql);
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
										sql_error('val_status', $sql);
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
										sql_error('pam', $sql);
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
<?php
} catch (Exception $e) {
    Helper::vSendCrashReport($e);
    View::vRenderErrorMsg($e);
}
standard_html_footer();
?>
