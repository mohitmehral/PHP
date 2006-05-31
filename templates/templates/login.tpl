
<div id="login">
<table class="LeftBoxTable" width="150" cellspacing="0" cellpadding="0" border="0">
<tbody><tr>
      <td class="boxtitle" ><b>{$loginin}</b></td>
   </tr>
   <tr>
      <td>
		<div class="BodyColumnLeftContent">
		{if $login eq false}
			<form method="post" action="{$URL}">
			<input type="hidden" name="lang" value="{$langId}" size="8"/>
			{$username}<input type="text" name="username" size="8"/><br />
			{$password}<input type="password" name="password" size="8"/><br />
			<input type="submit" class="SearchButton" value="{$loginin}"/>
			</form>
			{if $loginfailed neq false}
			{$loginfailed}
			{/if}
		{elseif $login eq true}
			{$welcome} {$useracc}<br/>
			<a href="index.php?act=logout&amp;lang={$langId}">{$logout}</a>
		{/if}
		
	
		</div>
    </td> 
 </tr>
</tbody>
</table>
</div>