<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
  include('conx/db_conx_open.php');
	
	$id = $_GET['id'];
	$where_select = "where id = '$id' ";
	include('select/select_pam.php');
	$pam_fetch = mysql_fetch_array($pam);
	include('fetch/fetch_pam.php');
//	include('select/select_pam_category.php');
	include('select/select_pam_ghg.php');
	include('select/select_pam_implementing_entity.php');
//	include('select/select_pam_keywords.php');
	include('select/select_pam_member_state.php');
	include('select/select_pam_reduces_non_ghg.php');
	include('select/select_pam_related_ccpm.php');
	include('select/select_pam_sector.php');
	include('select/select_pam_status.php');
	include('select/select_pam_type.php');
	include('select/select_pam_with_or_with_additional_measure.php');
?>
<html>
	<head>
		<title>
			European Climate Change Programme (ECCP) - Database on Policies and Measures in Europe
		</title>
		<link href="frm.css" rel="stylesheet" type="text/css">
	</head>
	<body>
		<table>
			<tr>
				<td>
					<img src="images/eccp.jpg" alt="ECCP">
				</td>
				<td style="width:100%">&nbsp;
					
				</td>
				<td>
					<img src="images/oi.jpg" alt="OEko-Institut e.V.">
				</td>
			</tr>
		</table>
		<p class="head_green">
			European Climate Change Programme (ECCP)
		</p>
		<p class="head_red">
			Database on Policies and Measures in Europe
		</p>
		<hr class="green"/>
		<p class="head_green">
			Detailed Results
		</p>
		<table>
			<tr><td class="section">Name of policy or measure (or group)</td><td class="details"><?php if ($name_pam) {echo $name_pam;} else {echo "&nbsp;";}?></td></tr>
			<tr><td class="section">Internal PaM identifier</td><td class="details"><?php if ($pam_identifier) {echo $pam_identifier;} else {echo "&nbsp;";}?></td></tr>
			<tr><td class="section">PaM-No</td><td class="details"><?php if ($pam_no) {echo $pam_no;} else {echo "&nbsp;";}?></td></tr>
			<tr><td class="section">Member State</td><td class="details"><?php
																			if ($pam_member_state) {
																				while ($pam_member_state_fetch = mysql_fetch_array($pam_member_state)) {
																					include('fetch/fetch_pam_member_state.php');
																					echo $member_state . "<br/>";
																				}
																			} else {
																				echo "&nbsp;";
																			}
																		?></td></tr>
			<tr><td class="section">With or with additional measure</td><td class="details"><?php
																								if ($pam_with_or_with_additional_measure) {
																									while ($pam_with_or_with_additional_measure_fetch = mysql_fetch_array($pam_with_or_with_additional_measure)) {
																										include('fetch/fetch_pam_with_or_with_additional_measure.php');
																										echo $with_or_with_additional_measure . "<br/>";
																									}
																								} else {
																									echo "&nbsp;";
																								}
																							?></td></tr>
			<tr><td class="section">Objective of measure(s)</td><td class="details"><?php if ($objective_of_measure) {echo $objective_of_measure;} else {echo "&nbsp;";}?></td></tr>
			<tr><td class="section">Description of policy or measure</td><td class="details"><?php if ($description_pam) {echo $description_pam;} else {echo "&nbsp;";}?></td></tr>
			<tr><td class="section">Sector(s) targeted</td><td class="details"><?php
																					if ($pam_sector) {
																						while ($pam_sector_fetch = mysql_fetch_array($pam_sector)) {
																							include('fetch/fetch_pam_sector.php');
																							echo $sector . "<br/>";
																						}
																					} else {
																						echo "&nbsp;";
																					}
																				?></td></tr>
			<tr><td class="section">GHG affected</td><td class="details"><?php
																			if ($pam_ghg) {
																				while ($pam_ghg_fetch = mysql_fetch_array($pam_ghg)) {
																					include('fetch/fetch_pam_ghg.php');
																					echo $ghg_output . "<br/>";
																				}
																			} else {
																				echo "&nbsp;";
																			}
																		?></td></tr>
			<tr><td class="section">Type of instruments</td><td class="details"><?php
																					if ($pam_type) {
																						while ($pam_type_fetch = mysql_fetch_array($pam_type)) {
																							include('fetch/fetch_pam_type.php');
																							echo $type . "<br/>";
																						}
																					} else {
																						echo "&nbsp;";
																					}
																				?></td></tr>
			<tr><td class="section">Status of policy, measure or group</td><td class="details"><?php
																									if ($pam_status) {
																										while ($pam_status_fetch = mysql_fetch_array($pam_status)) {
																											include('fetch/fetch_pam_status.php');
																											echo $status . "<br/>";
																										}
																									} else {
																										echo "&nbsp;";
																									}
																								?></td></tr>
			<tr><td class="section">Start year of implementation</td><td class="details"><?php if ($start) {echo $start;} else {echo "&nbsp;";}?></td></tr>
			<tr><td class="section">End year of implementation</td><td class="details"><?php if ($ende) {echo $ende;} else {echo "&nbsp;";}?></td></tr>
			<tr><td class="section">Implementing entity or entities</td><td class="details"><?php
																								if ($pam_implementing_entity) {
																									while ($pam_implementing_entity_fetch = mysql_fetch_array($pam_implementing_entity)) {
																										include('fetch/fetch_pam_implementing_entity.php');
																										echo $implementing_entity;
																										if ($specification) {echo "(" . $specification . ")";}
																										echo "<br/>";
																									}
																								} else {
																									echo "&nbsp;";
																								}
																							?></td></tr>
			<tr><td colspan="2" class="section_head">Estimate of GHG emission reduction effect or sequestration effect in Gg CO<sub>2</sub> eq per year</td></tr>
			<tr><td class="subsection">2005</td><td class="details"><?php
																		if (($red_2005_val == $cluster or $red_2005_text == $cluster) and $cluster and $cluster != "") {
																			$sql = "select id, pam_identifier, cluster, red_2005_val, red_2005_text " .
																				"from pam where pam_identifier = '$cluster'";
																			$pam_cluster = @mysql_query($sql);
																			$pam_cluster_num = @mysql_num_rows($pam_cluster);
																			if (!$pam_cluster) {
																				echo("<p>Es gab einen Fehler beim Zugriff auf die Tabelle \"pam_cluster\".</p><p>$sql</p>");
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
																				echo("<p>Es gab einen Fehler beim Zugriff auf die Tabelle \"pam_cluster\".</p><p>$sql</p>");
																			} else {
																				if ($pos_mes) {echo(" ... pam_cluster");}
																			}
																			$pam_cluster_fetch = mysql_fetch_array($pam_cluster);
																			$id_cluster = $pam_cluster_fetch['id'];
																			$pam_identifier_cluster = $pam_cluster_fetch['pam_identifier'];
																			echo "<br/>cluster contains following PaM: <a href=\"details.php?id=$key\" target=\"_blank\">$pam_identifier_cluster</a>";
																			while ($pam_cluster_fetch = mysql_fetch_array($pam_cluster)) {
																				$id_cluster = $pam_cluster_fetch['id'];
																				$pam_identifier_cluster = $pam_cluster_fetch['pam_identifier'];
																				echo ", <a href=\"details.php?id_cluster=$key\" target=\"_blank\">$pam_identifier_cluster</a>";
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
			<tr><td class="subsection">2010</td><td class="details"><?php
																		if (($red_2010_val == $cluster or $red_2010_text == $cluster) and $cluster and $cluster != "") {
																			$sql = "select id, pam_identifier, cluster, red_2010_val, red_2010_text " .
																				"from pam where pam_identifier = '$cluster'";
																			$pam_cluster = @mysql_query($sql);
																			$pam_cluster_num = @mysql_num_rows($pam_cluster);
																			if (!$pam_cluster) {
																				echo("<p>Es gab einen Fehler beim Zugriff auf die Tabelle \"pam_cluster\".</p><p>$sql</p>");
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
																				echo("<p>Es gab einen Fehler beim Zugriff auf die Tabelle \"pam_cluster\".</p><p>$sql</p>");
																			} else {
																				if ($pos_mes) {echo(" ... pam_cluster");}
																			}
																			$pam_cluster_fetch = mysql_fetch_array($pam_cluster);
																			$id_cluster = $pam_cluster_fetch['id'];
																			$pam_identifier_cluster = $pam_cluster_fetch['pam_identifier'];
																			echo "<br/>cluster contains following PaM: <a href=\"details.php?id=$key\" target=\"_blank\">$pam_identifier_cluster</a>";
																			while ($pam_cluster_fetch = mysql_fetch_array($pam_cluster)) {
																				$id_cluster = $pam_cluster_fetch['id'];
																				$pam_identifier_cluster = $pam_cluster_fetch['pam_identifier'];
																				echo ", <a href=\"details.php?id_cluster=$key\" target=\"_blank\">$pam_identifier_cluster</a>";
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
			<tr><td class="subsection">2015</td><td class="details"><?php
																		if (($red_2015_val == $cluster or $red_2015_text == $cluster) and $cluster and $cluster != "") {
																			$sql = "select id, pam_identifier, cluster, red_2015_val, red_2015_text " .
																				"from pam where pam_identifier = '$cluster'";
																			$pam_cluster = @mysql_query($sql);
																			$pam_cluster_num = @mysql_num_rows($pam_cluster);
																			if (!$pam_cluster) {
																				echo("<p>Es gab einen Fehler beim Zugriff auf die Tabelle \"pam_cluster\".</p><p>$sql</p>");
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
																				echo("<p>Es gab einen Fehler beim Zugriff auf die Tabelle \"pam_cluster\".</p><p>$sql</p>");
																			} else {
																				if ($pos_mes) {echo(" ... pam_cluster");}
																			}
																			$pam_cluster_fetch = mysql_fetch_array($pam_cluster);
																			$id_cluster = $pam_cluster_fetch['id'];
																			$pam_identifier_cluster = $pam_cluster_fetch['pam_identifier'];
																			echo "<br/>cluster contains following PaM: <a href=\"details.php?id=$key\" target=\"_blank\">$pam_identifier_cluster</a>";
																			while ($pam_cluster_fetch = mysql_fetch_array($pam_cluster)) {
																				$id_cluster = $pam_cluster_fetch['id'];
																				$pam_identifier_cluster = $pam_cluster_fetch['pam_identifier'];
																				echo ", <a href=\"details.php?id_cluster=$key\" target=\"_blank\">$pam_identifier_cluster</a>";
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
			<tr><td class="subsection">2020</td><td class="details"><?php
																		if (($red_2020_val == $cluster or $red_2020_text == $cluster) and $cluster and $cluster != "") {
																			$sql = "select id, pam_identifier, cluster, red_2020_val, red_2020_text " .
																				"from pam where pam_identifier = '$cluster'";
																			$pam_cluster = @mysql_query($sql);
																			$pam_cluster_num = @mysql_num_rows($pam_cluster);
																			if (!$pam_cluster) {
																				echo("<p>Es gab einen Fehler beim Zugriff auf die Tabelle \"pam_cluster\".</p><p>$sql</p>");
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
																				echo("<p>Es gab einen Fehler beim Zugriff auf die Tabelle \"pam_cluster\".</p><p>$sql</p>");
																			} else {
																				if ($pos_mes) {echo(" ... pam_cluster");}
																			}
																			$pam_cluster_fetch = mysql_fetch_array($pam_cluster);
																			$id_cluster = $pam_cluster_fetch['id'];
																			$pam_identifier_cluster = $pam_cluster_fetch['pam_identifier'];
																			echo "<br/>cluster contains following PaM: <a href=\"details.php?id=$key\" target=\"_blank\">$pam_identifier_cluster</a>";
																			while ($pam_cluster_fetch = mysql_fetch_array($pam_cluster)) {
																				$id_cluster = $pam_cluster_fetch['id'];
																				$pam_identifier_cluster = $pam_cluster_fetch['pam_identifier'];
																				echo ", <a href=\"details.php?id_cluster=$key\" target=\"_blank\">$pam_identifier_cluster</a>";
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
			<tr><td class="subsection">Cumulative emission reduction 2008- 2012 (Gg CO2eq)</td><td class="details"><?php if ($cumulative_2008_2012) {echo $cumulative_2008_2012;} else {echo "&nbsp;";}?></td></tr>
			<tr><td colspan="2" class="section_head">Explanation of emission reduction estimate</td></tr>
			<tr><td class="subsection">Explanation of the basis for  the mitigation estimates</td><td class="details"><?php if ($explanation_basis_of_mitigation_estimates) {echo $explanation_basis_of_mitigation_estimates;} else {echo "&nbsp;";}?></td></tr>
			<tr><td class="subsection">Factors resulting in emission reduction </td><td class="details"><?php if ($factors_resulting_in_emission_reduction) {echo $factors_resulting_in_emission_reduction;} else {echo "&nbsp;";}?></td></tr>
			<tr><td class="subsection">Does the estimate include reductions related to common and coordinated policies and measures?</td><td class="details"><?php if ($include_common_reduction) {echo $include_common_reduction;} else {echo "&nbsp;";}?></td></tr>
			<tr><td class="subsection">Documentation/ Source of estimation if available</td><td class="details"><?php if ($documention_source) {echo $documention_source;} else {echo "&nbsp;";}?></td></tr>
			<tr><td colspan="2" class="section_head">&nbsp;</td></tr>
			<tr><td class="section">Indicators used to monitor progress of implementation  </td><td class="details"><?php if ($indicator_monitor_implementation) {echo $indicator_monitor_implementation;} else {echo "&nbsp;";}?></td></tr>
			<tr><td class="section">Common and coordinated policy and measure (CCPM)</td><td class="details"><?php
																												if ($pam_related_ccpm) {
																													while ($pam_related_ccpm_fetch = mysql_fetch_array($pam_related_ccpm)) {
																														include('fetch/fetch_pam_related_ccpm.php');
																														echo $related_ccpm . "<br/>";
																													}
																												} else {
																													echo "&nbsp;";
																												}
																											?></td></tr>
			<tr><td class="section">General Comment</td><td class="details"><?php if ($general_comment) {echo $general_comment;} else {echo "&nbsp;";}?></td></tr>
			<tr><td class="section">Reference</td><td class="details"><?php if ($reference) {echo $reference;} else {echo "&nbsp;";}?></td></tr>
			<tr><td colspan="2" class="section_head">Description of mitigation impact of measure on Non-Greenhouse Gases (e.g. Sox, NOx, NMVOC, Particulates)</td></tr>
			<tr><td class="subsection">Reduces non Greenhouse Gas Emissions</td><td class="details"><?php
																										if ($pam_reduces_non_ghg) {
																											while ($pam_reduces_non_ghg_fetch = mysql_fetch_array($pam_reduces_non_ghg)) {
																												include('fetch/fetch_pam_reduces_non_ghg.php');
																												echo $reduces_non_ghg . "<br/>";
																											}
																										} else {
																											echo "&nbsp;";
																										}
																									?></td></tr>
			<tr><td class="subsection">Description of Impact</td><td class="details"><?php if ($description_impact_on_non_ghg) {echo $description_impact_on_non_ghg;} else {echo "&nbsp;";}?></td></tr>
			<tr><td colspan="2" class="section_head">Information on costs</td></tr>
			<tr><td class="subsection">Costs in EURO per tonne CO2eq reduced/ sequestered</td><td class="details"><?php if ($costs_per_tonne) {echo $costs_per_tonne;} else {echo "&nbsp;";}?></td></tr>
			<tr><td class="subsection">Absolute costs per year in EURO</td><td class="details"><?php if ($costs_per_year) {echo $costs_per_year;} else {echo "&nbsp;";}?></td></tr>
			<tr><td class="subsection">Description of cost estimates</td><td class="details"><?php if ($costs_description) {echo $costs_description;} else {echo "&nbsp;";}?></td></tr>
			<tr><td class="subsection">Documentation/ Source of cost estimation</td><td class="details"><?php if ($costs_documention_source) {echo $costs_documention_source;} else {echo "&nbsp;";}?></td></tr>			
		</table>
	</body>
</html>
