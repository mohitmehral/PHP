<?php
require_once 'support.php';
standard_html_header("");

require_once 'config.inc.php';
require_once 'Helper.php';
require_once 'DB.php';
require_once 'View.php';

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
					<td class="filter">
						<label class="question">Member State</label><br/>
						<select size="10" name="id_member_state[]" multiple="multiple">
							<option value="select_all">All</option>
							<?php
								include('select/select_val_member_state.php');
								if ($val_member_state_num) {
									while ($val_member_state_fetch = mysql_fetch_array($val_member_state)) {
										include('fetch/fetch_val_member_state.php');
										echo "<option value=\"$id_member_state\">" .
											$member_state . "</option>";
									}
								}
							?>
						</select><br/>
						Ctrl+click for<br/>multiple selection
					</td>
                    <?php
                    View::vRenderCheckboxList('Sector', 'id_sector', 'id_sector', 'sector', 'rgGetSectors');
                    ?>
					<td class="filter">
						<label class="question">Policy Type</label><br/>
						<input type="checkbox" name="id_type[]" id="id_type_all" value="select_all"/><label for="id_type_all" class="specialval">Select all</label><br/>
						<?php
							include('select/select_val_type.php');
							if ($val_type_num) {
								while ($val_type_fetch = mysql_fetch_array($val_type)) {
									include('fetch/fetch_val_type.php');
									echo "<input type=\"checkbox\" id=\"id_type$id_type\" name=\"id_type[]\" value=\"$id_type\"/><label for=\"id_type$id_type\">$type</label><br/>";
								}
							}
						?>
					</td>
				</tr>
				<tr>
					<td class="filter">
						<label class="question">GHG affected</label><br/>
						<input type="checkbox" name="id_ghg[]" value="select_all"/><label class="specialval">Select all</label><br/>
						<?php
							include('select/select_val_ghg.php');
							if ($val_ghg_num) {
								while ($val_ghg_fetch = mysql_fetch_array($val_ghg)) {
									include('fetch/fetch_val_ghg.php');
									echo "<input type=\"checkbox\" id=\"id_ghg$id_ghg\" name=\"id_ghg[]\" value=\"$id_ghg\"/><label for=\"id_ghg$id_ghg\">$ghg_output</label><br/>";
								}
							}
						?>
					</td>
					<td class="filter">
						<label class="question">Status</label><br/>
						<input type="checkbox" name="id_status[]" value="select_all"/><label class="specialval">Select all</label><br/>
						<input type="checkbox" name="id_status[]" value="no_value"/><label class="specialval">no value</label><br/>
						<?php
							include('select/select_val_status.php');
							if ($val_status_num) {
								while ($val_status_fetch = mysql_fetch_array($val_status)) {
									include('fetch/fetch_val_status.php');
									echo "<input type=\"checkbox\" id=\"id_status$id_status\" name=\"id_status[]\" value=\"$id_status\"/><label for=\"id_status$id_status\">$status</label><br/>";
								}
							}
						?>
					</td>
					<td class="filter">
						<label class="question">Scenario</label><br/>
						<input type="checkbox" name="id_with_or_with_additional_measure[]" value="select_all"/><label class="specialval">Select all</label><br/>
						<input type="checkbox" name="id_with_or_with_additional_measure[]" value="no_value"/><label class="specialval">no value</label><br/>
						<?php
							include('select/select_val_with_or_with_additional_measure.php');
							if ($val_with_or_with_additional_measure_num) {
								while ($val_with_or_with_additional_measure_fetch = mysql_fetch_array($val_with_or_with_additional_measure)) {
									include('fetch/fetch_val_with_or_with_additional_measure.php');
									echo "<input type=\"checkbox\" id=\"id_scenario$id_with_or_with_additional_measure\" name=\"id_with_or_with_additional_measure[]\" value=\"$id_with_or_with_additional_measure\"/><label for=\"id_scenario$id_with_or_with_additional_measure\">$with_or_with_additional_measure</label><br/>";
								}
							}
						?>
<!--						<label class="question">Key words</label><br/>
						<select size="6" name="id_keywords[]" multiple>
							<option value="select_all">
								All
							</option>-->
							<?php
//								$where_select = "where val_keywords.id_sector = '$id_sector'";
//								include('select/select_val_keywords.php');
//								if ($val_keywords_num) {
//									while ($val_keywords_fetch = mysql_fetch_array($val_keywords)) {
//										include('fetch/fetch_val_keywords.php');
//										echo "<option value=\"$id_keywords\">" .
//											$keywords . "</option>";
//									}
//								}	
//								unset($where_select);
							?>
<!--						</select><br/>
						Ctrl+click for<br/>multiple selection-->
					</td>
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
