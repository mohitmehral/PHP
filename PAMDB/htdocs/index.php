<?php
require_once 'support.php';
standard_html_header("");

require_once 'Helper.php';
require_once 'DB.php';
require_once 'View.php';
require_once 'Model.php';

try {
    DB::vInit();
?>
<h1 class="documentFirstHeading">
European Climate Change Programme (ECCP) - Database on Policies and Measures in Europe
</h1>
<div class="visualClear"><!--&nbsp; --></div>

<p>
This database of climate change policies and measures in Europe includes
policies and measures reported by European Member States to the Commission
or under the UNFCCC. The database covers the relevant sectors energy,
industrial processes, agriculture, forestry, waste and cross-cutting
policies and provides detailed and complete information on Member States'
actions on climate change.
</p>
<p>
In the <span class="red">normal search mode</span>, the database is
searchable by Member State, sector, policy type, greenhouse gas affected
and reduction effects in sectors covered by the European emissions
trading scheme.
</p>
<p>
In the <span class="red">expert search mode</span>, the user can choose
additional selections for each sector: the status of implementation,
the category of policy and measures, related common and coordinated
policies and measures at European level, keywords and related quantitative
indicator.
</p>
<p>
In addition the user can choose to search exclusively for policies and
measures for which quantitative emission reduction effects are available
or for which cost estimates are provided.
</p>
<hr class="green"/>
<p class="head_green">
	Database Search
</p>
		<form action="output" method="get">
			<table>
				<tr>
					<td class="filter" colspan="2">
						<a class="big" href="sector">Switch to expert search mode</a>
					</td>
					<td class="filter" style="text-align:right">&nbsp;
						<!--<a class="small" href="explain.htm">Explanation of search options</a>-->
					</td>
				</tr>
				<tr>
                    <?php
                    View::vRenderCheckboxList(Model::rgGetMemberStates(), 'Member State', 'member_state');
                    View::vRenderCheckboxList(Model::rgGetSectors(), 'Sector', 'sector');
                    View::vRenderCheckboxList(Model::rgGetTypes(), 'Policy Type', 'type', true);
                    ?>
				</tr>
				<tr>
                    <?php
                    View::vRenderCheckboxList(Model::rgGetGhgs(), 'GHG affected', 'ghg_output', true, 'id_ghg');
                    View::vRenderCheckboxList(Model::rgGetStatusList(), 'Status', 'status', true);
                    // NOTICE: The following call produces a different "id" attribute on the input tag than the original code
                    View::vRenderCheckboxList(Model::rgGetScenarios(), 'Scenario', 'with_or_with_additional_measure', true);
                    ?>
				</tr>
				<tr>
					<td colspan="2" class="filter">
						<label for="any_word" class="question">Any word</label><br/>
						<input type="text" name="any_word" id="any_word"/>
					</td>
					<td class="filter" style="vertical-align:bottom">
						<input type="submit" value="SEARCH" name="normal"/>
						<input type="reset" value="RESET" name="reset"/>
					</td>
				</tr>
			</table>
		</form>
<?php
} catch (Exception $e) {
    Helper::vSendCrashReport($e);
    View::vRenderErrorMsg($e);
}

standard_html_footer();
?>
