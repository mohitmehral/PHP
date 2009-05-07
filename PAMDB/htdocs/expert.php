<?php
$pos_mes = FALSE;
include('conx/db_conx_open.php');
require_once 'support.php';
standard_html_header("Expert search")
?>
<?php
	$where_select = "where val_sector.id_sector = '" . $_GET['id_sector'] . "'";
	include('select/select_val_sector.php');
	$val_sector_fetch = mysql_fetch_array($val_sector);
	include('fetch/fetch_val_sector.php');
	unset($where_select);
?>
		<h1>
			Database Expert Search Mode
		</h1>
		<p class="head_red">
			Sector: <?php echo $sector; ?>
		</p>
		<form action="output" method="get">
			<table>
				<tr>
					<td class="filter" colspan="2">
						<a class="big" href="sector">Select a new sector</a>
					</td>
					<td class="filter" style="text-align:right">&nbsp;
						<!--<a class="small" href="explain.htm">Explanation of search options</a>-->
					</td>
				</tr>
				<tr>
					<td class="filter">
						<label class="question">Member State</label><br/>
						<select size="10" name="id_member_state[]" multiple="multiple">
							<option value="select_all">
								All
							</option>
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
					<td class="filter">
						<label class="question">Policy Type</label><br/>
						<input type="checkbox" name="id_type[]" value="select_all"/><label class="specialval">Select all</label><br/>
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
					<td class="filter">
						&nbsp;
<!--						<label class="question">Category</label><br/>
						<select size="10" name="id_category[]" multiple="multiple">
							<option value="select_all">
								All
							</option>-->
							<?php
//								$where_select = "where val_category.id_sector = '$id_sector'";
//								include('select/select_val_category.php');
//								if ($val_category_num) {
//									while ($val_category_fetch = mysql_fetch_array($val_category)) {
//										include('fetch/fetch_val_category.php');
//										echo "<option value=\"$id_category\">" .
//											$category . "
//										</option>";
//									}
//								}	
//								unset($where_select);
							?>
<!--						</select><br/>
						Ctrl+click for<br/>multiple selection-->
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
//											$keywords . "
//										</option>";
//									}
//								}	
//								unset($where_select);
							?>
<!--						</select><br/>
						Ctrl+click for<br/>multiple selection-->
					</td>
				</tr>
<!--				<tr>
					<td class="filter" colspan="3">
						<input type="checkbox" name="quantified_emission_reduction_effect_only"/>only PAMs with quantified emission reduction effects<br/>
						<input type="checkbox" name="cost_data_only"/>only PAMs for which cost data is available 
					</td>
				</tr>-->
				<tr>
					<td class="filter" colspan="3">
						<label class="question">Related CCPM</label><br/>
						<select style="width:600px" size="6" name="id_related_ccpm[]" multiple="multiple">
							<option value="select_all">
								All
							</option>
							<?php
								$where_select = "where val_related_ccpm.id_sector = '$id_sector'";
								include('select/select_val_related_ccpm.php');
								if ($val_related_ccpm_num) {
									while ($val_related_ccpm_fetch = mysql_fetch_array($val_related_ccpm)) {
										include('fetch/fetch_val_related_ccpm.php');
										echo "<option value=\"$id_related_ccpm\">" .
											$related_ccpm . "
										</option>";
									}
								}	
								unset($where_select);
							?>
						</select><br/>
						Ctrl+click for<br/>multiple selection
					</td>
				</tr>
				<tr>	
					<td class="filter">
						<label class="question">Any word</label><br/>
						<input name="any_word" type="text"/>
					</td>
					<td class="filter" colspan="2" style="vertical-align:bottom; text-align:right;">
						<input type="hidden" value="<?php echo $id_sector; ?>" name="id_sector[]"/>
						<input type="submit" value="SEARCH" name="expert"/>
						<input type="reset" value="RESET" name="reset"/>
					</td>
				</tr>
			</table>
		</form>
		<p>
			<a class="big" href="index">Switch to normal search mode</a>
		</p>
<?php standard_html_footer() ?>
