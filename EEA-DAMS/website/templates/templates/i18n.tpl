{include file="header.tpl" bodyclass="threecolumns"}
{include file="login.tpl"}

<div id="rightcolumn">
<table  width="150" cellspacing="0" cellpadding="0" border="0">
<tbody><tr>
      <td><b>Filter lang</b></td>
   </tr>
   <tr>
      <td>
		<div class="BodyColumnLeftContent">
			<form method="post" action="">
			<select name="listselect[]" multiple="multiple" size="16">
			{html_options values=$langIds selected=$selectedLang output=$langNames}
			</select><br/>
			<input type="submit" class="SearchButton" value="{$applyfilter}"/>
			</form>
		</div>
    </td> 
 </tr>
</tbody>
</table>
</div>


<div id="workarea">
<div id='div1' style='width: 99%; height: 470px; z-index: 1; overflow: scroll;' class="scroll">

<table style="margin:0;">
{assign var="i" value=0}
<tr>
{foreach name=outer item=term from=$selectedLang}
	<td>{$term}</td>
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
	  			<input type="hidden" size="2" name="{$key}" value="{$item}"/>
    		{elseif $key eq 'id'}
	    		{assign var="myId" value=$item}
	  			<input type="hidden" name="{$key}" value="{$item}"/>
  			{else}
  				<td>
  					{if $item|count_words > 5}
  					<textarea name="{$key}" cols="11">{$item}</textarea>
  					{else}
  					<input type="text" size="14" name="{$key}" value="{$item}"/>
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
</div>

{include file="footer.tpl"}
