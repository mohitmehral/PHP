<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
	$pos_mes = FALSE;
	include('conx/db_conx_open.php');
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
		<p>
			In the expert search mode, the user can choose additional selections for each sector: the status of implementation, the category of policy and measures, related common and coordinated policies and measures at European level, keywords and related quantitative indicator. First a sector has to be chosen, then the additional search options appear.
		</p>
		<hr class="green"/>
		<p class="head_green">
			Database Expert Search Mode
		</p>
		<p>
			<span class="green">Sector</span><br/>
			<?php
				include('select/select_val_sector.php');
				if ($val_sector_num) {
					while ($val_sector_fetch = mysql_fetch_array($val_sector)) {
						include('fetch/fetch_val_sector.php');
						echo "&nbsp;&nbsp;&bull;<a class=\"sector\" href=\"expert.php?id_sector=$id_sector\">$sector</a><br/>";
					}
				}
			?>
		</p>
		<p>
			<span class="red" style="font-size:larger;">Please select only one sector in this search mode, after the selection you will be automatically guided to further search options for this sector</span>
		</p>
		<p>
			<a class="big" href="index.php">Switch to normal search mode</a>
		</p>
	</body>
</html>
