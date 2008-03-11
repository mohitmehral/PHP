<div id="options">
<h2>{$statistics}</h2>
	<p>
		{$inchargeof} {$userDamNumber}
	</p>


<h2>{$addFilter}</h2>
<form method="get" action="dams.php">
	<input type="hidden" name="lang" value="{$langId}"/>
	<ul>
	<li><label>{$code}:</label> <input type="text" name="cd" size="5"/></li>
	<li><label>{$name}:</label> <input type="text" name="srcName" size="5"/></li>
	<li><label>{$country}:</label>
	  <select name="srcCountry">
		<option value="">*</option>
	  {html_options values=$damCountryFilter output=$damCountryFilter}
	  </select>			
<!--			 <input type="text" name="srcCountry" size="5"/>-->
    <input class="SearchButton" type="submit" value="{$applyfilter}"/></li>
	</ul>
</form>
</div>


