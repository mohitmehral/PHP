<tr>
  <th rowspan="2" class="head_output"><a href="output?sort=member_state<?php foreach ($_GET as $valve => $value) {
																					if (is_array($value)) {
																						foreach ($value as $option) {
																							echo "&" . $valve . "[]=" . $option;
																						}
																					} else {
																						if ($valve != "sort") {echo "&" . $valve . "=" . $value;}
																					}
																				}?>">Member<br/>State</a></th>
  <th rowspan="2" class="head_output"><a href="output?sort=sector<?php foreach ($_GET as $valve => $value) {
																				if (is_array($value)) {
																					foreach ($value as $option) {
																						echo "&" . $valve . "[]=" . $option;
																					}
																				} else {
																					if ($valve != "sort") {echo "&" . $valve . "=" . $value;}
																				}
																			}?>">Sector</a></th>
  <th rowspan="2" class="head_output">Projection<br />Scenario</th>
  <th rowspan="2" class="head_output">Name</th>
<!--  <th rowspan="2" class="head_output"><a href="output?sort=category
																			<?php
//																				foreach ($_GET as $valve => $value) {
//																					if (is_array($value)) {
//																						foreach ($value as $option) {
//																							echo "&" . $valve . "[]=" . $option;
//																						}
//																					} else {
//																						if ($valve != "sort") {echo "&" . $valve . "=" . $value;}
//																					}
//																				}
																			?>
																				">Category</a></th>-->
  <th rowspan="2" class="head_output">Type</th>
  <th rowspan="2" class="head_output">GHG</th>
  <th rowspan="2" class="head_output">Status</th>
  <th colspan="3" class="head_output"><nobr>Absolute Reduction</nobr><br/><nobr>[kt CO<sub>2</sub> eq. p.a.]</nobr></th>
  <th rowspan="2" class="head_output"><a href="output?sort=costs_per_tonne<?php foreach ($_GET as $valve => $value) {
																					if (is_array($value)) {
																						foreach ($value as $option) {
																							echo "&" . $valve . "[]=" . $option;
																						}
																					} else {
																						if ($valve != "sort") {echo "&" . $valve . "=" . $value;}
																					}
																				}?>">Costs<br/>[EUR/t]</a></th>
</tr>
<tr>
  <th class="head_output">2005</th>
  <th class="head_output"><a href="output?sort=red_2010_val<?php foreach ($_GET as $valve => $value) {
																if (is_array($value)) {
																	foreach ($value as $option) {
																		echo "&" . $valve . "[]=" . $option;
																	}
																} else {
																	if ($valve != "sort") {echo "&" . $valve . "=" . $value;}
																}
															}?>">2010</a></th>
  <th class="head_output">2020</th>
</tr>
