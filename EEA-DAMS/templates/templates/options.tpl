<div id="options">
<table class="LeftBoxTable" width="150" cellspacing="0" cellpadding="0" border="0">
<tbody><tr>
      <td class="boxtitle"><b>{$statistics}</b></td>
   </tr>
   <tr>
      <td>
		<div class="BodyColumnLeftContent">
			{$inchargeof} {$userDamNumber}
		</div>
    </td> 
 </tr>
</tbody>
</table>

<br/>

<table class="LeftBoxTable" width="150" cellspacing="0" cellpadding="0" border="0">
<tbody><tr>
      <td class="boxtitle" ><b>{$addFilter}</b></td>
   </tr>
   <tr>
      <td>
		<div class="BodyColumnLeftContent">
		<form method="get" action="dams.php">
			<input type="hidden" name="lang" value="{$langId}"/>
			<ul>
			<li>{$code}: <input type="text" name="cd" size="5"/></li>
			<li>{$name}: <input type="text" name="srcName" size="5"/></li>
			<li>{$country}:
			  <select name="srcCountry">
				<option value="">*</option>
			  {html_options values=$damCountryFilter output=$damCountryFilter}
			  </select>			
<!--			 <input type="text" name="srcCountry" size="5"/>--><input class="SearchButton" type="submit" value="{$applyfilter}"/></li>
			</ul>
		</form>
		</div>
    </td> 
 </tr>
</tbody>
</table>
</div>


