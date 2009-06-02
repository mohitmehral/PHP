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
                $rgFilter = Model::rgGetEu15StateIds();
            } else if (in_array('2', $rgFilter)) {
                $rgFilter = Model::rgGetEu10StateIds();
            }
            $dim = new Dimension('pam_member_state', 'id');
            $dim->vSetFilter('id_member_state', $rgFilter);
            $q->vAddDimension($dim);
        }
    }

	$valves = array("sector","ghg","type","status","related_ccpm","with_or_with_additional_measure");
	
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
    $rgPams = DB::rgSelectRows($sql);
    for ($c = 0, $cMax = count($rgPams); $c < $cMax; $c++) {
        // TODO: This issues two extra SQL queries per row
        //       which is quite inefficient, but "everything is
        //       fast for small n", so we don't bother for now.
        //
        //       The proper way to do it would be to get the
        //       additional info by joining in more tables to
        //       the star query, but that means to line up the
        //       Select and StarQuery classes better beforehand.
        $rgPams[$c] = Model::mpGetPamSummaryById($rgPams[$c]['id']);
    }

    if (null != ($fnComp = Controller::fnGetSortFunc())) {
        usort($rgPams, $fnComp);
    }

    View::vRenderSearchResults($rgPams);
    exit(0);
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
			?>
<?php
} catch (Exception $e) {
    Helper::vSendCrashReport($e);
    View::vRenderErrorMsg($e);
}
standard_html_footer();
?>
