<?php
	include('conx/db_conx_open.php');
	require_once 'support.php';
	
	$sql = "select * from tabelle1";
	$tabelle1 = @mysql_query($sql);
	$tabelle1_num = @mysql_num_rows($tabelle1);
	if (!$tabelle1) {
		sql_error('tabelle1', $sql);
	} else {
		if ($pos_mes) {echo(" ... tabelle1");}
	} 
	
	while ($tabelle1_fetch = mysql_fetch_array($tabelle1)) {
		$pam_identifier = $tabelle1_fetch['pam_identifier'];
		$cluster = $tabelle1_fetch['cluster'];
		$pam_no = $tabelle1_fetch['pam_no'];
		$with_or_with_additional_measure = $tabelle1_fetch['with_or_with_additional_measure'];
		$name_pam = $tabelle1_fetch['name_pam'];
		$objective_of_measure = $tabelle1_fetch['objective_of_measure'];
		$description_pam = $tabelle1_fetch['description_pam'];
		$Cross_cutting = $tabelle1_fetch['Cross-cutting'];
		$Energy_supply = $tabelle1_fetch['Energy supply'];
		$Energy_consumption = $tabelle1_fetch['Energy consumption'];
		$Transport = $tabelle1_fetch['Transport'];
		$Industrial_Processes = $tabelle1_fetch['Industrial Processes'];
		$Agriculture = $tabelle1_fetch['Agriculture'];
		$Forestry = $tabelle1_fetch['Forestry'];
		$Waste = $tabelle1_fetch['Waste'];
		$co2 = $tabelle1_fetch['co2'];
		$ch4 = $tabelle1_fetch['ch4'];
		$n2o = $tabelle1_fetch['n2o'];
		$hfc = $tabelle1_fetch['hfc'];
		$pfc = $tabelle1_fetch['pfc'];
		$sf6 = $tabelle1_fetch['sf6'];
		$Economic = $tabelle1_fetch['Economic'];
		$Fiscal = $tabelle1_fetch['Fiscal'];
		$Voluntary_negotiated_agreement = $tabelle1_fetch['Voluntary/ negotiated agreement'];
		$Regulatory = $tabelle1_fetch['Regulatory'];
		$Information = $tabelle1_fetch['Information'];
		$Education = $tabelle1_fetch['Education'];
		$Research = $tabelle1_fetch['Research'];
		$Planning = $tabelle1_fetch['Planning'];
		$Other = $tabelle1_fetch['Other'];
		$status = $tabelle1_fetch['status'];
		$start = $tabelle1_fetch['start'];
		$ende = $tabelle1_fetch['ende'];
		$National_Government = $tabelle1_fetch['National Government'];
		$Regional_Entities = $tabelle1_fetch['Regional Entities'];
		$Municipalities_local_governments = $tabelle1_fetch['Municipalities / local governments'];
		$Companies_Businesses_industrial_associations = $tabelle1_fetch['Companies / Businesses / industrial associations'];
		$Research_institutions = $tabelle1_fetch['Research institutions'];
		$Others = $tabelle1_fetch['Others'];
		$red_2005_val = $tabelle1_fetch['red_2005_val'];
		$red_2005_text = $tabelle1_fetch['red_2005_text'];
		$red_2010_val = $tabelle1_fetch['red_2010_val'];
		$red_2010_text = $tabelle1_fetch['red_2010_text'];
		$red_2015_val = $tabelle1_fetch['red_2015_val'];
		$red_2015_text = $tabelle1_fetch['red_2015_text'];
		$red_2020_val = $tabelle1_fetch['red_2020_val'];
		$red_2020_text = $tabelle1_fetch['red_2020_text'];
		$cumulative_2008_2012 = $tabelle1_fetch['cumulative_2008_2012'];
		$explanation_basis_of_mitigation_estimates = $tabelle1_fetch['explanation_basis_of_mitigation_estimates'];
		$factors_resulting_in_emission_reduction = $tabelle1_fetch['factors_resulting_in_emission_reduction'];
		$include_common_reduction = $tabelle1_fetch['include_common_reduction'];
		$documention_source = $tabelle1_fetch['documention_source'];
		$indicator_monitor_implementation = $tabelle1_fetch['indicator_monitor_implementation'];
		$related_ccpm = $tabelle1_fetch['related_ccpm'];
		$related_ccpm_1 = $tabelle1_fetch['related_ccpm_1'];
		$general_comment = $tabelle1_fetch['general_comment'];
		$reference = $tabelle1_fetch['reference'];
		$reduces_non_ghg = $tabelle1_fetch['reduces_non_ghg'];
		$description_impact_on_non_ghg = $tabelle1_fetch['description_impact_on_non_ghg'];
		$costs_per_tonne = $tabelle1_fetch['costs_per_tonne'];
		$costs_per_year = $tabelle1_fetch['costs_per_year'];
		$costs_description = $tabelle1_fetch['costs_description'];
		$costs_documention_source = $tabelle1_fetch['costs_documention_source'];
		
		if ($pam_identifier) {$pam_identifier=spaces($pam_identifier);}
		if ($cluster) {$cluster=spaces($cluster);}
		if ($pam_no) {$pam_no=spaces($pam_no);}
		if ($with_or_with_additional_measure) {$with_or_with_additional_measure=spaces($with_or_with_additional_measure);}
		if ($name_pam) {$name_pam=spaces($name_pam);}
		if ($objective_of_measure) {$objective_of_measure=spaces($objective_of_measure)}
		if ($description_pam) {$description_pam=spaces($description_pam);}
		if ($Cross_cutting) {$Cross_cutting=spaces($Cross_cutting);}
		if ($Energy_supply) {$Energy_supply=spaces($Energy_supply);}
		if ($Energy_consumption) {$Energy_consumption=spaces($Energy_consumption);}
		if ($Transport) {$Transport=spaces($Transport);}
		if ($Industrial_Processes) {$Industrial_Processes=spaces($Industrial_Processes);}
		if ($Agriculture) {$Agriculture=spaces($Agriculture);}
		if ($Forestry) {$Forestry=spaces($Forestry);}
		if ($Waste) {$Waste=spaces($Waste);}
		if ($co2) {$co2=spaces($co2);}
		if ($ch4) {$ch4=spaces($ch4);}
		if ($n2o) {$n2o=spaces($n2o);}
		if ($hfc) {$hfc=spaces($hfc);}
		if ($pfc) {$pfc=spaces($pfc);}
		if ($sf6) {$sf6=spaces($sf6);}
		if ($Economic) {$Economic=spaces($Economic);}
		if ($Fiscal) {$Fiscal=spaces($Fiscal);}
		if ($Voluntary_negotiated_agreement) {$Voluntary_negotiated_agreement=spaces($Voluntary_negotiated_agreement);}
		if ($Regulatory) {$Regulatory=spaces($Regulatory);}
		if ($Information) {$Information=spaces($Information);}
		if ($Education) {$Education=spaces($Education);}
		if ($Research) {$Research=spaces($Research);}
		if ($Planning) {$Planning=spaces($Planning);}
		if ($Other) {$Other=spaces($Other);}
		if ($status) {$status=spaces($status);}
		if ($start) {$start=spaces($start);}
		if ($ende) {$ende=spaces($ende);}
		if ($National_Government) {$National_Government=spaces($National_Government);}
		if ($Regional_Entities) {$Regional_Entities=spaces($Regional_Entities);}
		if ($Municipalities_local_governments) {$Municipalities_local_governments=spaces($Municipalities_local_governments);}
		if ($Companies_Businesses_industrial_associations) {$Companies_Businesses_industrial_associations=spaces($Companies_Businesses_industrial_associations);}
		if ($Research_institutions) {$Research_institutions=spaces($Research_institutions);}
		if ($Others) {$Others=spaces($Others);}
		if ($red_2005_val) {$red_2005_val=spaces($red_2005_val);}
		if ($red_2005_text) {$red_2005_text=spaces($red_2005_text);}
		if ($red_2010_val) {$red_2010_val=spaces($red_2010_val);}
		if ($red_2010_text) {$red_2010_text=spaces($red_2010_text);}
		if ($red_2015_val) {$red_2015_val=spaces($red_2015_val);}
		if ($red_2015_text) {$red_2015_text=spaces($red_2015_text);}
		if ($red_2020_val) {$red_2020_val=spaces($red_2020_val);}
		if ($red_2020_text) {$red_2020_text=spaces($red_2020_text);}
		if ($cumulative_2008_2012) {$cumulative_2008_2012=spaces($cumulative_2008_2012);}
		if ($explanation_basis_of_mitigation_estimates) {$explanation_basis_of_mitigation_estimates=spaces($explanation_basis_of_mitigation_estimates);}
		if ($factors_resulting_in_emission_reduction) {$factors_resulting_in_emission_reduction=spaces($factors_resulting_in_emission_reduction);}
		if ($include_common_reduction) {$include_common_reduction=spaces($include_common_reduction);}
		if ($documention_source) {$documention_source=spaces($documention_source);}
		if ($indicator_monitor_implementation) {$indicator_monitor_implementation=spaces($indicator_monitor_implementation);}
		if ($related_ccpm) {$related_ccpm=spaces($related_ccpm);}
		if ($related_ccpm_1) {$related_ccpm_1=spaces($related_ccpm_1);}
		if ($general_comment) {$general_comment=spaces($general_comment);}
		if ($reference) {$reference=spaces($reference);}
		if ($reduces_non_ghg) {$reduces_non_ghg=spaces($reduces_non_ghg);}
		if ($description_impact_on_non_ghg) {$description_impact_on_non_ghg=spaces($description_impact_on_non_ghg);}
		if ($costs_per_tonne) {$costs_per_tonne=spaces($costs_per_tonne);}
		if ($costs_per_year) {$costs_per_year=spaces($costs_per_year);}
		if ($costs_description) {$costs_description=spaces($costs_description);}
		if ($costs_documention_source) {$costs_documention_source=spaces($costs_documention_source);}

		$sql = "insert into pam set ";
			if ($cluster and $cluster != "") {$sql = $sql . "cluster = '$cluster', ";} else {$sql = $sql . "cluster = null, ";}
			if ($pam_no and $pam_no != "") {$sql = $sql . "pam_no = '$pam_no', ";} else {$sql = $sql . "pam_no = null, ";}
			if ($name_pam and $name_pam != "") {$sql = $sql . "name_pam = '$name_pam', ";} else {$sql = $sql . "name_pam = null, ";}
			if ($objective_of_measure and $objective_of_measure != "") {$sql = $sql . "objective_of_measure = '$objective_of_measure', ";} else {$sql = $sql . "objective_of_measure = null, ";}
			if ($description_pam and $description_pam != "") {$sql = $sql . "description_pam = '$description_pam', ";} else {$sql = $sql . "description_pam = null, ";}
			if ($start and $start != "") {$sql = $sql . "start = '$start', ";} else {$sql = $sql . "start = null, ";}
			if ($ende and $ende != "") {$sql = $sql . "ende = '$ende', ";} else {$sql = $sql . "ende = null, ";}
			if ($red_2005_val and $red_2005_val != "") {$sql = $sql . "red_2005_val = '$red_2005_val', ";} else {$sql = $sql . "red_2005_val = null, ";}
			if ($red_2005_text and $red_2005_text != "") {$sql = $sql . "red_2005_text = '$red_2005_text', ";} else {$sql = $sql . "red_2005_text = null, ";}
			if ($red_2010_val and $red_2010_val != "") {$sql = $sql . "red_2010_val = '$red_2010_val', ";} else {$sql = $sql . "red_2010_val = null, ";}
			if ($red_2010_text and $red_2010_text != "") {$sql = $sql . "red_2010_text = '$red_2010_text', ";} else {$sql = $sql . "red_2010_text = null, ";}
			if ($red_2015_val and $red_2015_val != "") {$sql = $sql . "red_2015_val = '$red_2015_val', ";} else {$sql = $sql . "red_2015_val = null, ";}
			if ($red_2015_text and $red_2015_text != "") {$sql = $sql . "red_2015_text = '$red_2015_text', ";} else {$sql = $sql . "red_2015_text = null, ";}
			if ($red_2020_val and $red_2020_val != "") {$sql = $sql . "red_2020_val = '$red_2020_val', ";} else {$sql = $sql . "red_2020_val = null, ";}
			if ($red_2020_text and $red_2020_text != "") {$sql = $sql . "red_2020_text = '$red_2020_text', ";} else {$sql = $sql . "red_2020_text = null, ";}
			if ($cumulative_2008_2012 and $cumulative_2008_2012 != "") {$sql = $sql . "cumulative_2008_2012 = '$cumulative_2008_2012', ";} else {$sql = $sql . "cumulative_2008_2012 = null, ";}
			if ($explanation_basis_of_mitigation_estimates and $explanation_basis_of_mitigation_estimates != "") {$sql = $sql . "explanation_basis_of_mitigation_estimates = '$explanation_basis_of_mitigation_estimates', ";} else {$sql = $sql . "explanation_basis_of_mitigation_estimates = null, ";}
			if ($factors_resulting_in_emission_reduction and $factors_resulting_in_emission_reduction != "") {$sql = $sql . "factors_resulting_in_emission_reduction = '$factors_resulting_in_emission_reduction', ";} else {$sql = $sql . "factors_resulting_in_emission_reduction = null, ";}
			if ($include_common_reduction and $include_common_reduction != "") {$sql = $sql . "include_common_reduction = '$include_common_reduction', ";} else {$sql = $sql . "include_common_reduction = null, ";}
			if ($documention_source and $documention_source != "") {$sql = $sql . "documention_source = '$documention_source', ";} else {$sql = $sql . "documention_source = null, ";}
			if ($indicator_monitor_implementation and $indicator_monitor_implementation != "") {$sql = $sql . "indicator_monitor_implementation = '$indicator_monitor_implementation', ";} else {$sql = $sql . "indicator_monitor_implementation = null, ";}
			if ($general_comment and $general_comment != "") {$sql = $sql . "general_comment = '$general_comment', ";} else {$sql = $sql . "general_comment = null, ";}
			if ($reference and $reference != "") {$sql = $sql . "reference = '$reference', ";} else {$sql = $sql . "reference = null, ";}
			if ($description_impact_on_non_ghg and $description_impact_on_non_ghg != "") {$sql = $sql . "description_impact_on_non_ghg = '$description_impact_on_non_ghg', ";} else {$sql = $sql . "description_impact_on_non_ghg = null, ";}
			if ($costs_per_tonne and $costs_per_tonne != "") {$sql = $sql . "costs_per_tonne = '$costs_per_tonne', ";} else {$sql = $sql . "costs_per_tonne = null, ";}
			if ($costs_per_year and $costs_per_year != "") {$sql = $sql . "costs_per_year = '$costs_per_year', ";} else {$sql = $sql . "costs_per_year = null, ";}
			if ($costs_description and $costs_description != "") {$sql = $sql . "costs_description = '$costs_description', ";} else {$sql = $sql . "costs_description = null, ";}
			if ($costs_documention_source and $costs_documention_source != "") {$sql = $sql . "costs_documention_source = '$costs_documention_source', ";} else {$sql = $sql . "costs_documention_source = null, ";}
			$sql = $sql . "pam_identifier = '$pam_identifier'";
		if (@mysql_query($sql)) {
			if ($pos_mes) {echo("<p>... pam</p>");}
		} else {
			echo("<p>Es gab einen Fehler bei pam</p><p>$sql</p>");
		}
		$id = mysql_insert_id();
		
		if ($with_or_with_additional_measure == "WM") {
			$sql = "insert into pam_with_or_with_additional_measure set ";
			$sql = $sql . "id = '$id', ";
			$sql = $sql . "id_with_or_with_additional_measure = '1'";
			if (@mysql_query($sql)) {
				if ($pos_mes) {echo("<p>... pam</p>");}
			} else {
				echo("<p>Es gab einen Fehler bei pam</p><p>$sql</p>");
			}
		}
		
		if ($with_or_with_additional_measure == "WAM") {
			$sql = "insert into pam_with_or_with_additional_measure set ";
			$sql = $sql . "id = '$id', ";
			$sql = $sql . "id_with_or_with_additional_measure = '2'";
			if (@mysql_query($sql)) {
				if ($pos_mes) {echo("<p>... pam</p>");}
			} else {
				echo("<p>Es gab einen Fehler bei pam</p><p>$sql</p>");
			}
		}
		
		if (substr($pam_identifier, 0 , 2) == "cl") {$pam_identifier = substr($pam_identifier, 3);}

		if (substr($pam_identifier, 0, 2) and substr($pam_identifier, 0, 2) != "") {
			$sql = "select id_member_state from val_member_state where ms = '" . substr($pam_identifier, 0, 2) . "'";
			$val_member_state = @mysql_query($sql);
			$val_member_state_num = @mysql_num_rows($val_member_state);
			if (!$val_member_state) {
				sql_error('val_member_state', $sql);
			} else {
				if ($pos_mes) {echo(" ... val_member_state");}
			} 
			if ($val_member_state_num) {
				$val_member_state_fetch = mysql_fetch_array($val_member_state);
				$id_member_state = $val_member_state_fetch['id_member_state'];
				$sql = "insert into pam_member_state set ";
				$sql = $sql . "id = '$id', ";
				$sql = $sql . "id_member_state = '$id_member_state'";
				if (@mysql_query($sql)) {
					if ($pos_mes) {echo("<p>... member_state</p>");}
				} else {
					echo("<p>Es gab einen Fehler bei member_state</p><p>$sql</p>");
				}
			}
		}
			
		if ($Cross_cutting and $Cross_cutting != "") {
			$sql = "insert into pam_sector set ";
			$sql = $sql . "id = '$id', ";
			$sql = $sql . "id_sector = '1'";
			if (@mysql_query($sql)) {
				if ($pos_mes) {echo("<p>... Cross-cutting</p>");}
			} else {
				echo("<p>Es gab einen Fehler bei Cross-cutting</p><p>$sql</p>");
			}
		}
		
		if ($Energy_supply and $Energy_supply != "") {
			$sql = "insert into pam_sector set ";
			$sql = $sql . "id = '$id', ";
			$sql = $sql . "id_sector = '2'";
			if (@mysql_query($sql)) {
				if ($pos_mes) {echo("<p>... Energy supply</p>");}
			} else {
				echo("<p>Es gab einen Fehler bei Energy supply</p><p>$sql</p>");
			}
		}
			
		if ($Energy_consumption and $Energy_consumption != "") {
			$sql = "insert into pam_sector set ";
			$sql = $sql . "id = '$id', ";
			$sql = $sql . "id_sector = '3'";
			if (@mysql_query($sql)) {
				if ($pos_mes) {echo("<p>... Energy consumption</p>");}
			} else {
				echo("<p>Es gab einen Fehler bei Energy consumption</p><p>$sql</p>");
			}
		}
		
		if ($Transport and $Transport != "") {
			$sql = "insert into pam_sector set ";
			$sql = $sql . "id = '$id', ";
			$sql = $sql . "id_sector = '4'";
			if (@mysql_query($sql)) {
				if ($pos_mes) {echo("<p>... Transport</p>");}
			} else {
				echo("<p>Es gab einen Fehler bei Transport</p><p>$sql</p>");
			}
		}
		
		if ($Industrial_Processes and $Industrial_Processes != "") {
			$sql = "insert into pam_sector set ";
			$sql = $sql . "id = '$id', ";
			$sql = $sql . "id_sector = '5'";
			if (@mysql_query($sql)) {
				if ($pos_mes) {echo("<p>... Industrial Processes</p>");}
			} else {
				echo("<p>Es gab einen Fehler bei Industrial Processes</p><p>$sql</p>");
			}
		}
		
		if ($Agriculture and $Agriculture != "") {
			$sql = "insert into pam_sector set ";
			$sql = $sql . "id = '$id', ";
			$sql = $sql . "id_sector = '6'";
			if (@mysql_query($sql)) {
				if ($pos_mes) {echo("<p>... Agriculture</p>");}
			} else {
				echo("<p>Es gab einen Fehler bei Agriculture</p><p>$sql</p>");
			}
		}
		
		if ($Forestry and $Forestry != "") {
			$sql = "insert into pam_sector set ";
			$sql = $sql . "id = '$id', ";
			$sql = $sql . "id_sector = '7'";
			if (@mysql_query($sql)) {
				if ($pos_mes) {echo("<p>... Forestry</p>");}
			} else {
				echo("<p>Es gab einen Fehler bei Forestry</p><p>$sql</p>");
			}
		}
		
		if ($Waste and $Waste != "") {
			$sql = "insert into pam_sector set ";
			$sql = $sql . "id = '$id', ";
			$sql = $sql . "id_sector = '8'";
			if (@mysql_query($sql)) {
				if ($pos_mes) {echo("<p>... Waste</p>");}
			} else {
				echo("<p>Es gab einen Fehler bei Waste</p><p>$sql</p>");
			}
		}
		
		if ($co2 and $co2 != "") {
			$sql = "insert into pam_ghg set ";
			$sql = $sql . "id = '$id', ";
			$sql = $sql . "id_ghg = '1'";
			if (@mysql_query($sql)) {
				if ($pos_mes) {echo("<p>... co2</p>");}
			} else {
				echo("<p>Es gab einen Fehler bei co2</p><p>$sql</p>");
			}
		}
		
		if ($ch4 and $ch4 != "") {
			$sql = "insert into pam_ghg set ";
			$sql = $sql . "id = '$id', ";
			$sql = $sql . "id_ghg = '2'";
			if (@mysql_query($sql)) {
				if ($pos_mes) {echo("<p>... ch4</p>");}
			} else {
				echo("<p>Es gab einen Fehler bei ch4</p><p>$sql</p>");
			}
		}
		
		if ($n2o and $n2o != "") {
			$sql = "insert into pam_ghg set ";
			$sql = $sql . "id = '$id', ";
			$sql = $sql . "id_ghg = '3'";
			if (@mysql_query($sql)) {
				if ($pos_mes) {echo("<p>... n2o</p>");}
			} else {
				echo("<p>Es gab einen Fehler bei n2o</p><p>$sql</p>");
			}
		}
		
		if ($hfc and $hfc != "") {
			$sql = "insert into pam_ghg set ";
			$sql = $sql . "id = '$id', ";
			$sql = $sql . "id_ghg = '4'";
			if (@mysql_query($sql)) {
				if ($pos_mes) {echo("<p>... hfc</p>");}
			} else {
				echo("<p>Es gab einen Fehler bei hfc</p><p>$sql</p>");
			}
		}
		
		if ($pfc and $pfc != "") {
			$sql = "insert into pam_ghg set ";
			$sql = $sql . "id = '$id', ";
			$sql = $sql . "id_ghg = '5'";
			if (@mysql_query($sql)) {
				if ($pos_mes) {echo("<p>... pfc</p>");}
			} else {
				echo("<p>Es gab einen Fehler bei pfc</p><p>$sql</p>");
			}
		}
		
		if ($sf6 and $sf6 != "") {
			$sql = "insert into pam_ghg set ";
			$sql = $sql . "id = '$id', ";
			$sql = $sql . "id_ghg = '6'";
			if (@mysql_query($sql)) {
				if ($pos_mes) {echo("<p>... sf6</p>");}
			} else {
				echo("<p>Es gab einen Fehler bei sf6</p><p>$sql</p>");
			}
		}
		
		if ($Economic and $Economic != "") {
			$sql = "insert into pam_type set ";
			$sql = $sql . "id = '$id', ";
			$sql = $sql . "id_type = '1'";
			if (@mysql_query($sql)) {
				if ($pos_mes) {echo("<p>... Economic</p>");}
			} else {
				echo("<p>Es gab einen Fehler bei Economic</p><p>$sql</p>");
			}
		}
		
		if ($Fiscal and $Fiscal != "") {
			$sql = "insert into pam_type set ";
			$sql = $sql . "id = '$id', ";
			$sql = $sql . "id_type = '2'";
			if (@mysql_query($sql)) {
				if ($pos_mes) {echo("<p>... Fiscal</p>");}
			} else {
				echo("<p>Es gab einen Fehler bei Fiscal</p><p>$sql</p>");
			}
		}
		
		if ($Voluntary_negotiated_agreement and $Voluntary_negotiated_agreement != "") {
			$sql = "insert into pam_type set ";
			$sql = $sql . "id = '$id', ";
			$sql = $sql . "id_type = '3'";
			if (@mysql_query($sql)) {
				if ($pos_mes) {echo("<p>... Voluntary_negotiated_agreement</p>");}
			} else {
				echo("<p>Es gab einen Fehler bei Voluntary_negotiated_agreement</p><p>$sql</p>");
			}
		}
		
		if ($Regulatory and $Regulatory != "") {
			$sql = "insert into pam_type set ";
			$sql = $sql . "id = '$id', ";
			$sql = $sql . "id_type = '4'";
			if (@mysql_query($sql)) {
				if ($pos_mes) {echo("<p>... Regulatory</p>");}
			} else {
				echo("<p>Es gab einen Fehler bei Regulatory</p><p>$sql</p>");
			}
		}
		
		if ($Information and $Information != "") {
			$sql = "insert into pam_type set ";
			$sql = $sql . "id = '$id', ";
			$sql = $sql . "id_type = '5'";
			if (@mysql_query($sql)) {
				if ($pos_mes) {echo("<p>... Information</p>");}
			} else {
				echo("<p>Es gab einen Fehler bei Information</p><p>$sql</p>");
			}
		}
		
		if ($Education and $Education != "") {
			$sql = "insert into pam_type set ";
			$sql = $sql . "id = '$id', ";
			$sql = $sql . "id_type = '6'";
			if (@mysql_query($sql)) {
				if ($pos_mes) {echo("<p>... Education</p>");}
			} else {
				echo("<p>Es gab einen Fehler bei Education</p><p>$sql</p>");
			}
		}
		
		if ($Research and $Research != "") {
			$sql = "insert into pam_type set ";
			$sql = $sql . "id = '$id', ";
			$sql = $sql . "id_type = '7'";
			if (@mysql_query($sql)) {
				if ($pos_mes) {echo("<p>... Research</p>");}
			} else {
				echo("<p>Es gab einen Fehler bei Research</p><p>$sql</p>");
			}
		}
		
		if ($Planning and $Planning != "") {
			$sql = "insert into pam_type set ";
			$sql = $sql . "id = '$id', ";
			$sql = $sql . "id_type = '8'";
			if (@mysql_query($sql)) {
				if ($pos_mes) {echo("<p>... Planning</p>");}
			} else {
				echo("<p>Es gab einen Fehler bei Planning</p><p>$sql</p>");
			}
		}
		
		if ($Other and $Other != "") {
			$sql = "insert into pam_type set ";
			$sql = $sql . "id = '$id', ";
			$sql = $sql . "id_type = '9'";
			if (@mysql_query($sql)) {
				if ($pos_mes) {echo("<p>... Other</p>");}
			} else {
				echo("<p>Es gab einen Fehler bei Other</p><p>$sql</p>");
			}
		}
		
		if ($National_Government and $National_Government != "") {
			$sql = "insert into pam_implementing_entity set ";
			$sql = $sql . "id = '$id', ";
			if ($National_Government != "x") {
				$sql = $sql . "specification = '$National_Government', ";
			}
			$sql = $sql . "id_implementing_entity = '1'";
			if (@mysql_query($sql)) {
				if ($pos_mes) {echo("<p>... National_Government</p>");}
			} else {
				echo("<p>Es gab einen Fehler bei National_Government</p><p>$sql</p>");
			}
		}
		
		if ($Regional_Entities and $Regional_Entities != "") {
			$sql = "insert into pam_implementing_entity set ";
			$sql = $sql . "id = '$id', ";
			if ($Regional_Entities != "x") {
				$sql = $sql . "specification = '$Regional_Entities', ";
			}
			$sql = $sql . "id_implementing_entity = '2'";
			if (@mysql_query($sql)) {
				if ($pos_mes) {echo("<p>... Regional_Entities</p>");}
			} else {
				echo("<p>Es gab einen Fehler bei Regional_Entities</p><p>$sql</p>");
			}
		}
		
		if ($Municipalities_local_governments and $Municipalities_local_governments != "") {
			$sql = "insert into pam_implementing_entity set ";
			$sql = $sql . "id = '$id', ";
			if ($Municipalities_local_governments != "x") {
				$sql = $sql . "specification = '$Municipalities_local_governments', ";
			}
			$sql = $sql . "id_implementing_entity = '3'";
			if (@mysql_query($sql)) {
				if ($pos_mes) {echo("<p>... Municipalities_local_governments</p>");}
			} else {
				echo("<p>Es gab einen Fehler bei Municipalities_local_governments</p><p>$sql</p>");
			}
		}
		
		if ($Companies_Businesses_industrial_associations and $Companies_Businesses_industrial_associations != "") {
			$sql = "insert into pam_implementing_entity set ";
			$sql = $sql . "id = '$id', ";
			if ($Companies_Businesses_industrial_associations != "x") {
				$sql = $sql . "specification = '$Companies_Businesses_industrial_associations', ";
			}
			$sql = $sql . "id_implementing_entity = '4'";
			if (@mysql_query($sql)) {
				if ($pos_mes) {echo("<p>... Companies_Businesses_industrial_associations</p>");}
			} else {
				echo("<p>Es gab einen Fehler bei Companies_Businesses_industrial_associations</p><p>$sql</p>");
			}
		}
		
		if ($Research_institutions and $Research_institutions != "") {
			$sql = "insert into pam_implementing_entity set ";
			$sql = $sql . "id = '$id', ";
			if ($Research_institutions != "x") {
				$sql = $sql . "specification = '$Research_institutions', ";
			}
			$sql = $sql . "id_implementing_entity = '5'";
			if (@mysql_query($sql)) {
				if ($pos_mes) {echo("<p>... Research_institutions</p>");}
			} else {
				echo("<p>Es gab einen Fehler bei Research_institutions</p><p>$sql</p>");
			}
		}
		
		if ($Others and $Others != "") {
			$sql = "insert into pam_implementing_entity set ";
			$sql = $sql . "id = '$id', ";
			if ($Others != "x") {
				$sql = $sql . "specification = '$Others', ";
			}
			$sql = $sql . "id_implementing_entity = '6'";
			if (@mysql_query($sql)) {
				if ($pos_mes) {echo("<p>... Others</p>");}
			} else {
				echo("<p>Es gab einen Fehler bei Others</p><p>$sql</p>");
			}
		}
		
		if ($status == "adopted ") {
			$sql = "insert into pam_status set ";
			$sql = $sql . "id = '$id', ";
			$sql = $sql . "id_status = '2'";
			if (@mysql_query($sql)) {
				if ($pos_mes) {echo("<p>... Status</p>");}
			} else {
				echo("<p>Es gab einen Fehler bei Status</p><p>$sql</p>");
			}
		}
		
		if ($status == "expired") {
			$sql = "insert into pam_status set ";
			$sql = $sql . "id = '$id', ";
			$sql = $sql . "id_status = '4'";
			if (@mysql_query($sql)) {
				if ($pos_mes) {echo("<p>... Status</p>");}
			} else {
				echo("<p>Es gab einen Fehler bei Status</p><p>$sql</p>");
			}
		}
		
		if ($status == "implemented" or $status == "implemented, current mitigation impact" or $status == "implemented, future mitigation impact") {
			$sql = "insert into pam_status set ";
			$sql = $sql . "id = '$id', ";
			$sql = $sql . "id_status = '1'";
			if (@mysql_query($sql)) {
				if ($pos_mes) {echo("<p>... Status</p>");}
			} else {
				echo("<p>Es gab einen Fehler bei Status</p><p>$sql</p>");
			}
		}
		
		if ($status == "Other") {
			$sql = "insert into pam_status set ";
			$sql = $sql . "id = '$id', ";
			$sql = $sql . "id_status = '5'";
			if (@mysql_query($sql)) {
				if ($pos_mes) {echo("<p>... Status</p>");}
			} else {
				echo("<p>Es gab einen Fehler bei Status</p><p>$sql</p>");
			}
		}
		
		if ($status == "planned") {
			$sql = "insert into pam_status set ";
			$sql = $sql . "id = '$id', ";
			$sql = $sql . "id_status = '3'";
			if (@mysql_query($sql)) {
				if ($pos_mes) {echo("<p>... Status</p>");}
			} else {
				echo("<p>Es gab einen Fehler bei Status</p><p>$sql</p>");
			}
		}
		
		if ($reduces_non_ghg == "Has no effect") {
			$sql = "insert into pam_reduces_non_ghg set ";
			$sql = $sql . "id = '$id', ";
			$sql = $sql . "id_reduces_non_ghg = '1'";
			if (@mysql_query($sql)) {
				if ($pos_mes) {echo("<p>... reduces_non_ghg</p>");}
			} else {
				echo("<p>Es gab einen Fehler bei reduces_non_ghg</p><p>$sql</p>");
			}
		}
		
		if ($reduces_non_ghg == "Reduces non-GHGs") {
			$sql = "insert into pam_reduces_non_ghg set ";
			$sql = $sql . "id = '$id', ";
			$sql = $sql . "id_reduces_non_ghg = '2'";
			if (@mysql_query($sql)) {
				if ($pos_mes) {echo("<p>... reduces_non_ghg</p>");}
			} else {
				echo("<p>Es gab einen Fehler bei reduces_non_ghg</p><p>$sql</p>");
			}
		}
		
		if ($related_ccpm and $related_ccpm != "") {
			$sql = "select id_related_ccpm from val_related_ccpm where related_ccpm = '$related_ccpm'";
			$val_related_ccpm = @mysql_query($sql);
			$val_related_ccpm_num = @mysql_num_rows($val_related_ccpm);
			if (!$val_related_ccpm) {
				sql_error('val_related_ccpm', $sql);
			} else {
				if ($pos_mes) {echo(" ... val_related_ccpm");}
			} 
			if ($val_related_ccpm_num) {
				$val_related_ccpm_fetch = mysql_fetch_array($val_related_ccpm);
				$id_related_ccpm = $val_related_ccpm_fetch['id_related_ccpm'];
				$sql = "insert into pam_related_ccpm set ";
				$sql = $sql . "id = '$id', ";
				$sql = $sql . "id_related_ccpm = '$id_related_ccpm'";
				if (@mysql_query($sql)) {
					if ($pos_mes) {echo("<p>... related_ccpm</p>");}
				} else {
					echo("<p>Es gab einen Fehler bei related_ccpm</p><p>$sql</p>");
				}
			} else {
				$sql = "insert into val_related_ccpm set ";
				$sql = $sql . "related_ccpm = '$related_ccpm'";
				if (@mysql_query($sql)) {
					if ($pos_mes) {echo("<p>... val_related_ccpm</p>");}
				} else {
					echo("<p>Es gab einen Fehler bei val_related_ccpm</p><p>$sql</p>");
				}
				$id_related_ccpm = mysql_insert_id();
				
				$sql = "insert into pam_related_ccpm set ";
				$sql = $sql . "id = '$id', ";
				$sql = $sql . "id_related_ccpm = '$id_related_ccpm'";
				if (@mysql_query($sql)) {
					if ($pos_mes) {echo("<p>... related_ccpm</p>");}
				} else {
					echo("<p>Es gab einen Fehler bei related_ccpm</p><p>$sql</p>");
				}
			
			}
		}
		
		if ($related_ccpm_1 and $related_ccpm_1 != "") {
			$sql = "select id_related_ccpm from val_related_ccpm where related_ccpm = '$related_ccpm_1'";
			$val_related_ccpm = @mysql_query($sql);
			$val_related_ccpm_num = @mysql_num_rows($val_related_ccpm);
			if (!$val_related_ccpm) {
				sql_error('val_related_ccpm', $sql);
			} else {
				if ($pos_mes) {echo(" ... val_related_ccpm");}
			} 
			if ($val_related_ccpm_num) {
				$val_related_ccpm_fetch = mysql_fetch_array($val_related_ccpm);
				$id_related_ccpm = $val_related_ccpm_fetch['id_related_ccpm'];
				$sql = "insert into pam_related_ccpm set ";
				$sql = $sql . "id = '$id', ";
				$sql = $sql . "id_related_ccpm = '$id_related_ccpm'";
				if (@mysql_query($sql)) {
					if ($pos_mes) {echo("<p>... related_ccpm</p>");}
				} else {
					echo("<p>Es gab einen Fehler bei related_ccpm</p><p>$sql</p>");
				}
			} else {
				$sql = "insert into val_related_ccpm set ";
				$sql = $sql . "related_ccpm = '$related_ccpm_1'";
				if (@mysql_query($sql)) {
					if ($pos_mes) {echo("<p>... val_related_ccpm</p>");}
				} else {
					echo("<p>Es gab einen Fehler bei val_related_ccpm</p><p>$sql</p>");
				}
				$id_related_ccpm = mysql_insert_id();
				
				$sql = "insert into pam_related_ccpm set ";
				$sql = $sql . "id = '$id', ";
				$sql = $sql . "id_related_ccpm = '$id_related_ccpm'";
				if (@mysql_query($sql)) {
					if ($pos_mes) {echo("<p>... related_ccpm</p>");}
				} else {
					echo("<p>Es gab einen Fehler bei related_ccpm</p><p>$sql</p>");
				}
			
			}
		}
	}
?>
