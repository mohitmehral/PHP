{include file="header.tpl" bodyclass="threecolumns" pagetitle=$translationManage}
{include file="login.tpl"}

<div id="rightcolumn">
      <b>Target language</b><br/>
			<form method="post" action="">
		<div>
			<input type="hidden" name="listselect[]" value="en"/>
			<select name="listselect[]" size="26">
			{html_options values=$langIds selected=$selectedLang output=$langNames}
			</select><br/>
			<input type="submit" class="SearchButton" value="{$applyfilter}"/>
		</div>
			</form>
</div>


<div id="workarea">
<div class="tip-msg">
<strong>Information</strong>
<p>Please select your target language on the list to the right. Then enter the translation and click on Update.</p>
</div>
<table style="margin:0;">
{assign var="i" value=0}
<tr>
{foreach name=outer item=term from=$selectedLang}
	<th>{$term}</th>
{/foreach}
</tr>
{foreach name=outer item=term from=$terms}
<form action="i18n.php" method="post">
<tr>
			
	<input type="hidden" name="lang" value="{$langId}"/>
	<input type="hidden" name="action" value="update"/>
	{foreach key=key item=item from=$term}
  		{if $key eq 'page_id'}
	    		{assign var="myPage" value=$item}
	  			<input type="hidden" name="{$key}" value="{$item}"/>
    		{elseif $key eq 'id'}
	    		{assign var="myId" value=$item}
	  			<input type="hidden" name="{$key}" value="{$item}"/>
  			{else}
  				<td>
  					{if $item|count_words > 5}
  					<textarea name="{$key}" cols="22">{$item}</textarea>
  					{else}
  					<input type="text" size="28" name="{$key}" value="{$item}"/>
  					{/if}
  				</td>
  			{/if}  		
  	{/foreach}
	{assign var="i" value=1}
<td><input type="submit" value="{$update}" class="SearchButton"/></td>
</tr>	
</form>
<!--<form action="i18n.php" method="post">
	<td>
	<input type="hidden" name="id" value="{$myId}"/>
	<input type="hidden" name="action" value="delete"/>
	<input type="submit" value="{$delete}" class="SearchButton"/>
	</td>
</form>-->

{/foreach}
<tr>
</tr>	
</table>
<hr/><p>
{$addnewterm}
<table>
<tr><td>Page</td><td>Id</td>
{foreach name=outer item=term from=$selectedLang}
	<td>{$term}</td>
{/foreach}
</tr>
<tr>
<form action="i18n.php" method="post">
	<input type="hidden" name="lang" value="{$langId}"/>
	<td><input type="text" size="5" name="page_id" value=""/></td>
	<td><input type="text" size="5" name="id" value=""/></td>
	{foreach name=outer item=term from=$selectedLang}
		<td><input type="text" size="5" name="{$term}" value=""/></td>
	{/foreach}
	<td><input type="hidden" name="action" value="insert"/>
	<input type="submit" value="{$insert}" class="SearchButton"/></td>
</form>
</tr>
</table>
</p>
</div>

{include file="footer.tpl"}
