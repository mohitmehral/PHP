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

    if ($name_pam) standard_html_header($name_pam);
    else standard_html_header("Detailed Results");

    View::vRenderDetailView($mpPam);
?>
		<h1>
			Detailed Results<?php if ($name_pam) {echo " for ". $name_pam;}?>
		</h1>
		<table class="datatable" style="width:95%">
		<col style="width: 25%"/>
		<col style="width: 75%"/>
			<tr><th scope="row" class="scope-row">Name of policy or measure (or group)</th><td class="details"><?=$name_pam?></td></tr>
			<tr><th scope="row" class="scope-row">Internal PaM identifier</th><td class="details"><?=$pam_identifier?></td></tr>
			<tr><th scope="row" class="scope-row">PaM-No</th><td class="details"><?=$pam_no?></td></tr>
			<tr><th scope="row" class="scope-row">Member State</th><td class="details"><?=$member_state?></td></tr>
			<tr><th scope="row" class="scope-row">With or with additional measure</th><td class="details"><?=$with_or_with_additional_measure?></td></tr>
			<tr><th scope="row" class="scope-row">Objective of measure(s)</th><td class="details"><?=$objective_of_measure?></td></tr>
			<tr><th scope="row" class="scope-row">Description of policy or measure</th><td class="details"><?=$description_pam?></td></tr>
			<tr><th scope="row" class="scope-row">Sector(s) targeted</th><td class="details"><?=$sector?></td></tr>
			<tr><th scope="row" class="scope-row">GHG affected</th><td class="details"><?=$ghg_output?></td></tr>
			<tr><th scope="row" class="scope-row">Type of instruments</th><td class="details"><?=$type?></td></tr>
			<tr><th scope="row" class="scope-row">Status of policy, measure or group</th><td class="details"><?=$status?></td></tr>
			<tr><th scope="row" class="scope-row">Start year of implementation</th><td class="details"><?=$start?></td></tr>
			<tr><th scope="row" class="scope-row">End year of implementation</th><td class="details"><?=$ende?></td></tr>
			<tr><th scope="row" class="scope-row">Implementing entity or entities</th><td class="details"><?=$implementing_entity?>(<?=$specification?>)</td></tr>
		</table>
		<h2>Estimate of GHG emission reduction effect or sequestration effect in Gg CO<sub>2</sub> eq per year</h2>
		<table class="datatable" style="width:95%">
		<col style="width: 25%"/>
		<col style="width: 75%"/>
            <!-- TODO: handle cluster values (link to constituting measures) -->
			<tr><th scope="row" class="scope-row subsection">2005</th><td class="details"><?=$red_2005_val?><br><?=$red_2005_text?></td></tr>
			<tr><th scope="row" class="scope-row subsection">2010</th><td class="details"><?=$red_2010_val?><br><?=$red_2010_text?></td></tr>
			<tr><th scope="row" class="scope-row subsection">2015</th><td class="details"><?=$red_2015_val?><br><?=$red_2015_text?></td></tr>
			<tr><th scope="row" class="scope-row subsection">2020</th><td class="details"><?=$red_2020_val?><br><?=$red_2020_text?></td></tr>
			<tr><th scope="row" class="scope-row subsection">Cumulative emission reduction 2008- 2012 (Gg CO2eq)</th><td class="details"><?=$cumulative_2008_2012?></td></tr>
		</table>
		<h2>Explanation of emission reduction estimate</h2>
		<table class="datatable" style="width:95%">
		<col style="width: 25%"/>
		<col style="width: 75%"/>
			<tr><th scope="row" class="scope-row subsection">Explanation of the basis for  the mitigation estimates</th><td class="details"><?=$explanation_basis_of_mitigation_estimates?></td></tr>
			<tr><th scope="row" class="scope-row subsection">Factors resulting in emission reduction </th><td class="details"><?=$factors_resulting_in_emission_reduction?></td></tr>
			<tr><th scope="row" class="scope-row subsection">Does the estimate include reductions related to common and coordinated policies and measures?</th><td class="details"><?=$include_common_reduction?></td></tr>
			<tr><th scope="row" class="scope-row subsection">Documentation/ Source of estimation if available</th><td class="details"><?=$documention_source?></td></tr>
			<tr><th scope="row" class="scope-row subsection">Indicators used to monitor progress of implementation  </th><td class="details"><?=$indicator_monitor_implementation?></td></tr>
			<tr><th scope="row" class="scope-row subsection">Common and coordinated policy and measure (CCPM)</th><td class="details"><?=$related_ccpm?></td></tr>
			<tr><th scope="row" class="scope-row subsection">General Comment</th><td class="details"><?=$general_comment?></td></tr>
			<tr><th scope="row" class="scope-row subsection">Reference</th><td class="details"><?=$reference?></td></tr>
		</table>
		<h2>Description of mitigation impact of measure on Non-Greenhouse Gases (e.g. Sox, NOx, NMVOC, Particulates)</h2>
		<table class="datatable" style="width:95%">
		<col style="width: 25%"/>
		<col style="width: 75%"/>
			<tr><th scope="row" class="scope-row subsection">Reduces non Greenhouse Gas Emissions</th><td class="details"><?=$reduces_non_ghg?></td></tr>
			<tr><th scope="row" class="scope-row subsection">Description of Impact</th><td class="details"><?=$description_impact_on_non_ghg?></td></tr>
		</table>
		<h2>Information on costs</h2>
		<table class="datatable" style="width:95%">
		<col style="width: 25%"/>
		<col style="width: 75%"/>
			<tr><th scope="row" class="scope-row subsection">Costs in EURO per tonne CO2eq reduced/ sequestered</th><td class="details"><?=$costs_per_tonne?></td></tr>
			<tr><th scope="row" class="scope-row subsection">Absolute costs per year in EURO</th><td class="details"><?=$costs_per_year?></td></tr>
			<tr><th scope="row" class="scope-row subsection">Description of cost estimates</th><td class="details"><?=$costs_description?></td></tr>
			<tr><th scope="row" class="scope-row subsection">Documentation/ Source of cost estimation</th><td class="details"><?=$costs_documention_source?></td></tr>			
		</table>
<?php
} catch (Exception $e) {
    Helper::vSendCrashReport($e);
    View::vRenderErrorMsg($e);
}

standard_html_footer();
?>
