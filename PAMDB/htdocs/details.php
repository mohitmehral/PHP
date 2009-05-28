<?php
require_once 'support.php';

require_once 'DB.php';
require_once 'Helper.php';
require_once 'Model.php';
require_once 'View.php';
require_once 'Controller.php';

try {
    DB::vInit();
    $ixPam = Controller::ixPamFromRequest();
    $mpPam = Model::mpGetPamDetailsById($ixPam);
    extract($mpPam);

    if ($name_pam) standard_html_header($name_pam);
    else standard_html_header("Detailed Results");
?>
		<h1>
			Detailed Results<?php if ($name_pam) {echo " for ". $name_pam;}?>
		</h1>
		<table class="datatable" style="width:95%">
		<col style="width: 25%"/>
		<col style="width: 75%"/>
			<tr><th scope="row" class="scope-row">Name of policy or measure (or group)</th><td class="details"><?php if ($name_pam) {echo $name_pam;} else {echo "&nbsp;";}?></td></tr>
			<tr><th scope="row" class="scope-row">Internal PaM identifier</th><td class="details"><?php if ($pam_identifier) {echo $pam_identifier;} else {echo "&nbsp;";}?></td></tr>
			<tr><th scope="row" class="scope-row">PaM-No</th><td class="details"><?php if ($pam_no) {echo $pam_no;} else {echo "&nbsp;";}?></td></tr>
			<tr><th scope="row" class="scope-row">Member State</th><td class="details"><?=$member_state?></td></tr>
			<tr><th scope="row" class="scope-row">With or with additional measure</th><td class="details"><?=$with_or_with_additional_measure?></td></tr>
			<tr><th scope="row" class="scope-row">Objective of measure(s)</th><td class="details"><?php if ($objective_of_measure) {echo $objective_of_measure;} else {echo "&nbsp;";}?></td></tr>
			<tr><th scope="row" class="scope-row">Description of policy or measure</th><td class="details"><?php if ($description_pam) {echo $description_pam;} else {echo "&nbsp;";}?></td></tr>
			<tr><th scope="row" class="scope-row">Sector(s) targeted</th><td class="details"><?php
																					if ($pam_sector) {
																						while ($pam_sector_fetch = mysql_fetch_array($pam_sector)) {
																							echo $pam_sector_fetch["sector"] . "<br/>";
																						}
																					} else {
																						echo "&nbsp;";
																					}
																				?></td></tr>
			<tr><th scope="row" class="scope-row">GHG affected</th><td class="details"><?php
																			if ($pam_ghg) {
																				while ($pam_ghg_fetch = mysql_fetch_array($pam_ghg)) {
																					echo $pam_ghg_fetch["ghg_output"]. "<br/>";
																				}
																			} else {
																				echo "&nbsp;";
																			}
																		?></td></tr>
			<tr><th scope="row" class="scope-row">Type of instruments</th><td class="details"><?php
																					if ($pam_type) {
																						while ($pam_type_fetch = mysql_fetch_array($pam_type)) {
																							echo $pam_type_fetch["type"] . "<br/>";
																						}
																					} else {
																						echo "&nbsp;";
																					}
																				?></td></tr>
			<tr><th scope="row" class="scope-row">Status of policy, measure or group</th><td class="details"><?php
																									if ($pam_status) {
																										while ($pam_status_fetch = mysql_fetch_array($pam_status)) {
																											echo $pam_status_fetch["status"] . "<br/>";
																										}
																									} else {
																										echo "&nbsp;";
																									}
																								?></td></tr>
			<tr><th scope="row" class="scope-row">Start year of implementation</th><td class="details"><?php if ($start) {echo $start;} else {echo "&nbsp;";}?></td></tr>
			<tr><th scope="row" class="scope-row">End year of implementation</th><td class="details"><?php if ($ende) {echo $ende;} else {echo "&nbsp;";}?></td></tr>
			<tr><th scope="row" class="scope-row">Implementing entity or entities</th><td class="details"><?php
																								if ($pam_implementing_entity) {
																									while ($pam_implementing_entity_fetch = mysql_fetch_array($pam_implementing_entity)) {
																										$specification = $pam_implementing_entity_fetch["specification"];
																										echo $pam_implementing_entity_fetch["implementing_entity"];
																										if ($specification) {echo "(" . $specification . ")";}
																										echo "<br/>";
																									}
																								} else {
																									echo "&nbsp;";
																								}
																							?></td></tr>
		</table>
		<h2>Estimate of GHG emission reduction effect or sequestration effect in Gg CO<sub>2</sub> eq per year</h2>
		<table class="datatable" style="width:95%">
		<col style="width: 25%"/>
		<col style="width: 75%"/>
			<tr><th th scope="row" class="scope-row subsection">2005</th><td class="details"><?php
																		if (($red_2005_val == $cluster or $red_2005_text == $cluster) and $cluster and $cluster != "") {
																			$sql = "select id, pam_identifier, cluster, red_2005_val, red_2005_text " .
																				"from pam where pam_identifier = '$cluster'";
																			$pam_cluster = @mysql_query($sql);
																			$pam_cluster_num = @mysql_num_rows($pam_cluster);
																			if (!$pam_cluster) {
																				sql_error('pam_cluster', $sql);
																			} else {
																				if ($pos_mes) {echo(" ... pam_cluster");}
																			}
																			$pam_cluster_fetch = mysql_fetch_array($pam_cluster);
																			$red_2005_val = $pam_cluster_fetch['red_2005_val'];
																			$red_2005_text = $pam_cluster_fetch['red_2005_text'];
																			echo "<strong>clustered value</strong><br/>";
																			if ($red_2005_val and $red_2005_text) {
																				echo $red_2005_val . "<br/>" . $red_2005_text;
																			} else {
																				if ($red_2005_val) {echo $red_2005_val;}
																				if ($red_2005_text) {echo $red_2005_text;}
																			}
																			
																			$sql = "select id, pam_identifier, cluster " .
																				"from pam where cluster = '$cluster'";
																			$pam_cluster = @mysql_query($sql);
																			$pam_cluster_num = @mysql_num_rows($pam_cluster);
																			if (!$pam_cluster) {
																				sql_error('pam_cluster', $sql);
																			} else {
																				if ($pos_mes) {echo(" ... pam_cluster");}
																			}
																			$pam_cluster_fetch = mysql_fetch_array($pam_cluster);
																			$id_cluster = $pam_cluster_fetch['id'];
																			$pam_identifier_cluster = $pam_cluster_fetch['pam_identifier'];
																			echo "<br/>cluster contains following PaM: <a href=\"details?id=$id_cluster\">$pam_identifier_cluster</a>";
																			while ($pam_cluster_fetch = mysql_fetch_array($pam_cluster)) {
																				$id_cluster = $pam_cluster_fetch['id'];
																				$pam_identifier_cluster = $pam_cluster_fetch['pam_identifier'];
																				echo ", <a href=\"details?id=$id_cluster\">$pam_identifier_cluster</a>";
																			}
																		} else {
																			if ($red_2005_val and $red_2005_text) {
																				echo $red_2005_val . "<br/>" . $red_2005_text;
																			} else {
																				if ($red_2005_val) {echo $red_2005_val;}
																				if ($red_2005_text) {echo $red_2005_text;}
																			}
																		}
																		if (!$red_2005_val and !$red_2005_text) {echo "&nbsp;";}
																	?></td></tr>
			<tr><th th scope="row" class="scope-row subsection">2010</th><td class="details"><?php
																		if (($red_2010_val == $cluster or $red_2010_text == $cluster) and $cluster and $cluster != "") {
																			$sql = "select id, pam_identifier, cluster, red_2010_val, red_2010_text " .
																				"from pam where pam_identifier = '$cluster'";
																			$pam_cluster = @mysql_query($sql);
																			$pam_cluster_num = @mysql_num_rows($pam_cluster);
																			if (!$pam_cluster) {
																				sql_error('pam_cluster', $sql);
																			} else {
																				if ($pos_mes) {echo(" ... pam_cluster");}
																			}
																			$pam_cluster_fetch = mysql_fetch_array($pam_cluster);
																			$red_2010_val = $pam_cluster_fetch['red_2010_val'];
																			$red_2010_text = $pam_cluster_fetch['red_2010_text'];
																			echo "<strong>clustered value</strong><br/>";
																			if ($red_2010_val and $red_2010_text) {
																				echo $red_2010_val . "<br/>" . $red_2010_text;
																			} else {
																				if ($red_2010_val) {echo $red_2010_val;}
																				if ($red_2010_text) {echo $red_2010_text;}
																			}
																			
																			$sql = "select id, pam_identifier, cluster " .
																				"from pam where cluster = '$cluster'";
																			$pam_cluster = @mysql_query($sql);
																			$pam_cluster_num = @mysql_num_rows($pam_cluster);
																			if (!$pam_cluster) {
																				sql_error('pam_cluster', $sql);
																			} else {
																				if ($pos_mes) {echo(" ... pam_cluster");}
																			}
																			$pam_cluster_fetch = mysql_fetch_array($pam_cluster);
																			$id_cluster = $pam_cluster_fetch['id'];
																			$pam_identifier_cluster = $pam_cluster_fetch['pam_identifier'];
																			echo "<br/>cluster contains following PaM: <a href=\"details?id=$id_cluster\">$pam_identifier_cluster</a>";
																			while ($pam_cluster_fetch = mysql_fetch_array($pam_cluster)) {
																				$id_cluster = $pam_cluster_fetch['id'];
																				$pam_identifier_cluster = $pam_cluster_fetch['pam_identifier'];
																				echo ", <a href=\"details?id=$id_cluster\">$pam_identifier_cluster</a>";
																			}
																		} else {
																			if ($red_2010_val and $red_2010_text) {
																				echo $red_2010_val . "<br/>" . $red_2010_text;
																			} else {
																				if ($red_2010_val) {echo $red_2010_val;}
																				if ($red_2010_text) {echo $red_2010_text;}
																			}
																		}
																		if (!$red_2010_val and !$red_2010_text) {echo "&nbsp;";}
																	?></td></tr>
			<tr><th th scope="row" class="scope-row subsection">2015</th><td class="details"><?php
																		if (($red_2015_val == $cluster or $red_2015_text == $cluster) and $cluster and $cluster != "") {
																			$sql = "select id, pam_identifier, cluster, red_2015_val, red_2015_text " .
																				"from pam where pam_identifier = '$cluster'";
																			$pam_cluster = @mysql_query($sql);
																			$pam_cluster_num = @mysql_num_rows($pam_cluster);
																			if (!$pam_cluster) {
																				sql_error('pam_cluster', $sql);
																			} else {
																				if ($pos_mes) {echo(" ... pam_cluster");}
																			}
																			$pam_cluster_fetch = mysql_fetch_array($pam_cluster);
																			$red_2015_val = $pam_cluster_fetch['red_2015_val'];
																			$red_2015_text = $pam_cluster_fetch['red_2015_text'];
																			echo "<strong>clustered value</strong><br/>";
																			if ($red_2015_val and $red_2015_text) {
																				echo $red_2015_val . "<br/>" . $red_2015_text;
																			} else {
																				if ($red_2015_val) {echo $red_2015_val;}
																				if ($red_2015_text) {echo $red_2015_text;}
																			}
																			
																			$sql = "select id, pam_identifier, cluster " .
																				"from pam where cluster = '$cluster'";
																			$pam_cluster = @mysql_query($sql);
																			$pam_cluster_num = @mysql_num_rows($pam_cluster);
																			if (!$pam_cluster) {
																				sql_error('pam_cluster', $sql);
																			} else {
																				if ($pos_mes) {echo(" ... pam_cluster");}
																			}
																			$pam_cluster_fetch = mysql_fetch_array($pam_cluster);
																			$id_cluster = $pam_cluster_fetch['id'];
																			$pam_identifier_cluster = $pam_cluster_fetch['pam_identifier'];
																			echo "<br/>cluster contains following PaM: <a href=\"details?id=$id_cluster\">$pam_identifier_cluster</a>";
																			while ($pam_cluster_fetch = mysql_fetch_array($pam_cluster)) {
																				$id_cluster = $pam_cluster_fetch['id'];
																				$pam_identifier_cluster = $pam_cluster_fetch['pam_identifier'];
																				echo ", <a href=\"details?id=$id_cluster\">$pam_identifier_cluster</a>";
																			}
																		} else {
																			if ($red_2015_val and $red_2015_text) {
																				echo $red_2015_val . "<br/>" . $red_2015_text;
																			} else {
																				if ($red_2015_val) {echo $red_2015_val;}
																				if ($red_2015_text) {echo $red_2015_text;}
																			}
																		}
																		if (!$red_2015_val and !$red_2015_text) {echo "&nbsp;";}
																	?></td></tr>
			<tr><th th scope="row" class="scope-row subsection">2020</th><td class="details"><?php
																		if (($red_2020_val == $cluster or $red_2020_text == $cluster) and $cluster and $cluster != "") {
																			$sql = "select id, pam_identifier, cluster, red_2020_val, red_2020_text " .
																				"from pam where pam_identifier = '$cluster'";
																			$pam_cluster = @mysql_query($sql);
																			$pam_cluster_num = @mysql_num_rows($pam_cluster);
																			if (!$pam_cluster) {
																				sql_error('pam_cluster', $sql);
																			} else {
																				if ($pos_mes) {echo(" ... pam_cluster");}
																			}
																			$pam_cluster_fetch = mysql_fetch_array($pam_cluster);
																			$red_2020_val = $pam_cluster_fetch['red_2020_val'];
																			$red_2020_text = $pam_cluster_fetch['red_2020_text'];
																			echo "<strong>clustered value</strong><br/>";
																			if ($red_2020_val and $red_2020_text) {
																				echo $red_2020_val . "<br/>" . $red_2020_text;
																			} else {
																				if ($red_2020_val) {echo $red_2020_val;}
																				if ($red_2020_text) {echo $red_2020_text;}
																			}
																			
																			$sql = "select id, pam_identifier, cluster " .
																				"from pam where cluster = '$cluster'";
																			$pam_cluster = @mysql_query($sql);
																			$pam_cluster_num = @mysql_num_rows($pam_cluster);
																			if (!$pam_cluster) {
																				sql_error('pam_cluster', $sql);
																			} else {
																				if ($pos_mes) {echo(" ... pam_cluster");}
																			}
																			$pam_cluster_fetch = mysql_fetch_array($pam_cluster);
																			$id_cluster = $pam_cluster_fetch['id'];
																			$pam_identifier_cluster = $pam_cluster_fetch['pam_identifier'];
																			echo "<br/>cluster contains following PaM: <a href=\"details?id=$id_cluster\">$pam_identifier_cluster</a>";
																			while ($pam_cluster_fetch = mysql_fetch_array($pam_cluster)) {
																				$id_cluster = $pam_cluster_fetch['id'];
																				$pam_identifier_cluster = $pam_cluster_fetch['pam_identifier'];
																				echo ", <a href=\"details?id=$id_cluster\">$pam_identifier_cluster</a>";
																			}
																		} else {
																			if ($red_2020_val and $red_2020_text) {
																				echo $red_2020_val . "<br/>" . $red_2020_text;
																			} else {
																				if ($red_2020_val) {echo $red_2020_val;}
																				if ($red_2020_text) {echo $red_2020_text;}
																			}
																		}
																		if (!$red_2020_val and !$red_2020_text) {echo "&nbsp;";}
																	?></td></tr>
			<tr><th th scope="row" class="scope-row subsection">Cumulative emission reduction 2008- 2012 (Gg CO2eq)</th><td class="details"><?php if ($cumulative_2008_2012) {echo $cumulative_2008_2012;} else {echo "&nbsp;";}?></td></tr>
		</table>
		<h2>Explanation of emission reduction estimate</h2>
		<table class="datatable" style="width:95%">
		<col style="width: 25%"/>
		<col style="width: 75%"/>
			<tr><th th scope="row" class="scope-row subsection">Explanation of the basis for  the mitigation estimates</th><td class="details"><?php if ($explanation_basis_of_mitigation_estimates) {echo $explanation_basis_of_mitigation_estimates;} else {echo "&nbsp;";}?></td></tr>
			<tr><th th scope="row" class="scope-row subsection">Factors resulting in emission reduction </th><td class="details"><?php if ($factors_resulting_in_emission_reduction) {echo $factors_resulting_in_emission_reduction;} else {echo "&nbsp;";}?></td></tr>
			<tr><th th scope="row" class="scope-row subsection">Does the estimate include reductions related to common and coordinated policies and measures?</th><td class="details"><?php if ($include_common_reduction) {echo $include_common_reduction;} else {echo "&nbsp;";}?></td></tr>
			<tr><th th scope="row" class="scope-row subsection">Documentation/ Source of estimation if available</th><td class="details"><?php if ($documention_source) {echo $documention_source;} else {echo "&nbsp;";}?></td></tr>
			<tr><td colspan="2" class="section_head">&nbsp;</td></tr>
			<tr><th scope="row" class="scope-row">Indicators used to monitor progress of implementation  </th><td class="details"><?php if ($indicator_monitor_implementation) {echo $indicator_monitor_implementation;} else {echo "&nbsp;";}?></td></tr>
			<tr><th scope="row" class="scope-row">Common and coordinated policy and measure (CCPM)</th><td class="details"><?php
																												if ($pam_related_ccpm) {
																													while ($pam_related_ccpm_fetch = mysql_fetch_array($pam_related_ccpm)) {
																														echo $pam_related_ccpm_fetch["related_ccpm"] . "<br/>";
																													}
																												} else {
																													echo "&nbsp;";
																												}
																											?></td></tr>
			<tr><th scope="row" class="scope-row">General Comment</th><td class="details"><?php if ($general_comment) {echo $general_comment;} else {echo "&nbsp;";}?></td></tr>
			<tr><th scope="row" class="scope-row">Reference</th><td class="details"><?php if ($reference) {echo $reference;} else {echo "&nbsp;";}?></td></tr>
		</table>
		<h2>Description of mitigation impact of measure on Non-Greenhouse Gases (e.g. Sox, NOx, NMVOC, Particulates)</h2>
		<table class="datatable" style="width:95%">
		<col style="width: 25%"/>
		<col style="width: 75%"/>
			<tr><th th scope="row" class="scope-row subsection">Reduces non Greenhouse Gas Emissions</th><td class="details"><?php
																										if ($pam_reduces_non_ghg) {
																											while ($pam_reduces_non_ghg_fetch = mysql_fetch_array($pam_reduces_non_ghg)) {
																												echo $pam_reduces_non_ghg_fetch["reduces_non_ghg"] . "<br/>";
																											}
																										} else {
																											echo "&nbsp;";
																										}
																									?></td></tr>
			<tr><th th scope="row" class="scope-row subsection">Description of Impact</th><td class="details"><?php if ($description_impact_on_non_ghg) {echo $description_impact_on_non_ghg;} else {echo "&nbsp;";}?></td></tr>
		</table>
		<h2>Information on costs</h2>
		<table class="datatable" style="width:95%">
		<col style="width: 25%"/>
		<col style="width: 75%"/>
			<tr><th th scope="row" class="scope-row subsection">Costs in EURO per tonne CO2eq reduced/ sequestered</th><td class="details"><?php if ($costs_per_tonne) {echo $costs_per_tonne;} else {echo "&nbsp;";}?></td></tr>
			<tr><th th scope="row" class="scope-row subsection">Absolute costs per year in EURO</th><td class="details"><?php if ($costs_per_year) {echo $costs_per_year;} else {echo "&nbsp;";}?></td></tr>
			<tr><th th scope="row" class="scope-row subsection">Description of cost estimates</th><td class="details"><?php if ($costs_description) {echo $costs_description;} else {echo "&nbsp;";}?></td></tr>
			<tr><th th scope="row" class="scope-row subsection">Documentation/ Source of cost estimation</th><td class="details"><?php if ($costs_documention_source) {echo $costs_documention_source;} else {echo "&nbsp;";}?></td></tr>			
		</table>
<?php
} catch (Exception $e) {
    Helper::vSendCrashReport($e);
    View::vRenderErrorMsg($e);
}

standard_html_footer();
?>
