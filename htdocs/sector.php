<?php
$pos_mes = FALSE;
include('conx/db_conx_open.php');
require_once 'support.php';
standard_html_header("Expert search")
?>
<h1 class="documentFirstHeading">
	Database Expert Search Mode
</h1>

<p class="documentDescription">
In the expert search mode, the user can choose additional selections for each sector: the status of implementation, the category of policy and measures, related common and coordinated policies and measures at European level, keywords and related quantitative indicator. First a sector has to be chosen, then the additional search options appear.
</p>
<p>
	<h2>Select sector</h2>
	<?php
		include('select/select_val_sector.php');
		if ($val_sector_num) {
			while ($val_sector_fetch = mysql_fetch_array($val_sector)) {
				include('fetch/fetch_val_sector.php');
				echo "&nbsp;&nbsp;&bull;<a class=\"sector\" href=\"expert?id_sector=$id_sector\">$sector</a><br/>";
			}
		}
	?>
</p>
<p>
	<span class="red" style="font-size:larger;">Please select only one sector in this search mode, after the selection you will be automatically guided to further search options for this sector</span>
</p>
<p>
	<a class="big" href="index">Switch to normal search mode</a>
</p>
<?php standard_html_footer() ?>
