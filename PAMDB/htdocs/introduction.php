<?php
$pos_mes = FALSE;
include('conx/db_conx_open.php');
require_once 'support.php';
standard_html_header("Introduction")
?>
<h1 class="documentFirstHeading">
Introduction - Database on Policies and Measures in Europe
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
<?php standard_html_footer() ?>
