<?php
	include('conx/db_conx_open.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
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
		<p>
			This database of climate change policies and measures in Europe includes policies and measures reported by European Member States to the Commission or under the UNFCCC. The database covers the relevant sectors energy, industrial processes, agriculture, forestry, waste and cross-cutting policies and provides detailed and complete information on Member States' actions on climate change.
		</p>
		<p>
			In the <font class="red">normal search mode</font>, the database is searchable by Member State, sector, policy type, greenhouse gas affected and reduction effects in sectors covered by the European emissions trading scheme.
		</p>
		<p>
			In the <font class="red">expert search mode</font>, the user can choose additional selections for each sector: the status of implementation, the category of policy and measures, related common and coordinated policies and measures at European level, keywords and related quantitative indicator.
		</p>
		<p>
			In addition the user can choose to search exclusively for policies and measures for which quantitative emission reduction effects are available or for which cost estimates are provided.
		</p>
		<hr class="green">
		<p class="head_green">
			Database Search
		</p>
		<form action="output.php" method="get">
			<table>
				<tr>
					<td class="filter" colspan="2">
						<a class="big" href="sector.php">Switch to expert search mode</a>
					</td>
					<td class="filter" style="text-align:right">&nbsp;
						<!--<a class="small" href="explain.htm" target="_blank">Explanation of search options</a>-->
					</td>
				</tr>
				<tr>
					<td class="filter">
						<font class="green">Member State</font><br>
						<select size="10" name="id_member_state[]" multiple>
							<option value="select_all">
								All
							</option>
							<?php
								include('select/select_val_member_state.php');
								if ($val_member_state_num) {
									while ($val_member_state_fetch = mysql_fetch_array($val_member_state)) {
										include('fetch/fetch_val_member_state.php');
										echo "<option value=\"$id_member_state\">" .
											$member_state . "
										</option>";
									}
								}
							?>
						</select><br>
						Ctrl+click for<br>multiple selection
					</td>
					<td class="filter">
						<font class="green">Sector</font><br>
						<input type="checkbox" name="id_sector[]" value="select_all"><font class="red">Select all</font><br>
						<?php
							include('select/select_val_sector.php');
							if ($val_sector_num) {
								while ($val_sector_fetch = mysql_fetch_array($val_sector)) {
									include('fetch/fetch_val_sector.php');
									echo "<input type=\"checkbox\" name=\"id_sector[]\" value=\"$id_sector\">$sector<br>";
								}
							}
						?>
					</td>
					<td class="filter">
						<font class="green">Policy Type</font><br>
						<input type="checkbox" name="id_type[]" value="select_all"><font class="red">Select all</font><br>
						<?php
							include('select/select_val_type.php');
							if ($val_type_num) {
								while ($val_type_fetch = mysql_fetch_array($val_type)) {
									include('fetch/fetch_val_type.php');
									echo "<input type=\"checkbox\" name=\"id_type[]\" value=\"$id_type\">$type<br>";
								}
							}
						?>
					</td>
				</tr>
				<tr>
					<td class="filter">
						<font class="green">GHG affected</font><br>
						<input type="checkbox" name="id_ghg[]" value="select_all"><font class="red">Select all</font><br>
						<?php
							include('select/select_val_ghg.php');
							if ($val_ghg_num) {
								while ($val_ghg_fetch = mysql_fetch_array($val_ghg)) {
									include('fetch/fetch_val_ghg.php');
									echo "<input type=\"checkbox\" name=\"id_ghg[]\" value=\"$id_ghg\">$ghg_output<br>";
								}
							}
						?>
					</td>
					<td class="filter">
						<font class="green">Status</font><br>
						<input type="checkbox" name="id_status[]" value="select_all"><font class="red">Select all</font><br>
						<input type="checkbox" name="id_status[]" value="no_value"><font class="red">no value</font><br>
						<?php
							include('select/select_val_status.php');
							if ($val_status_num) {
								while ($val_status_fetch = mysql_fetch_array($val_status)) {
									include('fetch/fetch_val_status.php');
									echo "<input type=\"checkbox\" name=\"id_status[]\" value=\"$id_status\">$status<br>";
								}
							}
						?>
					</td>
					<td class="filter">
						<font class="green">Scenario</font><br>
						<input type="checkbox" name="id_with_or_with_additional_measure[]" value="select_all"><font class="red">Select all</font><br>
						<input type="checkbox" name="id_with_or_with_additional_measure[]" value="no_value"><font class="red">no value</font><br>
						<?php
							include('select/select_val_with_or_with_additional_measure.php');
							if ($val_with_or_with_additional_measure_num) {
								while ($val_with_or_with_additional_measure_fetch = mysql_fetch_array($val_with_or_with_additional_measure)) {
									include('fetch/fetch_val_with_or_with_additional_measure.php');
									echo "<input type=\"checkbox\" name=\"id_with_or_with_additional_measure[]\" value=\"$id_with_or_with_additional_measure\">$with_or_with_additional_measure<br>";
								}
							}
						?>
<!--						<font class="green">Key words</font><br>
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
<!--						</select><br>
						Ctrl+click for<br>multiple selection-->
					</td>
				</tr>
				<tr>
					<td colspan="2" class="filter">
						<font class="green">Any word</font><br>
						<input name="any_word">
					</td>
					<td class="filter" style="vertical-align:bottom">
						<input type="submit" value="SEARCH" name="normal">
						<input type="reset" value="RESET" name="reset">
					</td>
				</tr>
			</table>
		</form>
	</body>
</html>