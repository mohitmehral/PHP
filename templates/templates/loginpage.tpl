{include file="header.tpl" pagetitle=$loginin}
<div id="workarea">
	<h1>{$loginin}</h1>
	{if $login eq false}
		<form method="post" action="index.php">
		<table>
		<tr>
		<tr><td><label for="username">{$username}</label></td> <td><input type="text" name="username" id="username" size="8"/></td></tr>
		<tr><td><label for="password">{$password}</label></td> <td><input type="password" name="password" id="password" size="8"/></td></tr>
		<tr><td colspan="2">
		<input type="hidden" name="lang" value="{$langId}" size="8"/>
		<input type="submit" class="SearchButton" value="{$loginin}"/></td></tr>
		</table>
		</form>
		{if $loginfailed neq false}
		{$loginfailed}
		{/if}
	{elseif $login eq true}
		{$welcome} {$useracc}<br/>
		<a href="index.php?act=logout&amp;lang={$langId}">{$logout}</a>
	{/if}
	

</div>
{include file="footer.tpl"}
