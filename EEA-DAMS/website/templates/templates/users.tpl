{include file="header.tpl" pagetitle=$userAdm}
{include file="login.tpl"}

<!-- OnChange JS activate update your profil -->

<div id="workarea">
<h1>{$list}</h1>
<table class="datatable">
<tr>
	<th>{$firstname}</th>
	<th>{$surname}</th>
	<th>{$login}</th>
	<th>{$mail}</th>
	<th>{$roleadm}</th>
	<th></th>
	<th>{$roledam}</th>
<!--	<th>{$address}</th>
	<th>{$phone}</th>-->
	<th colspan="2">{$action}</th>
</tr>
{foreach name=outer item=contact from=$users}
  <tr>
  {foreach key=key item=item from=$contact}
    {if $key eq 'roleadm'}
    	{assign var="myadm" value=$item}
    	<td><input type="checkbox" disabled="disabled" name="userroleadm" {if $item eq 't'}checked="checked"{/if}/></td>
    {elseif $key eq 'rolelang'}<td></td>
    {elseif $key eq 'roledam'}<td><input type="checkbox" disabled="disabled" name="userroledam" {if $item eq 't'}checked="checked"{/if}/></td>
    {elseif $key eq 'password'}
    {elseif $key eq 'address'}
    {elseif $key eq 'phone'}    
    {elseif $key eq 'login'}<td><a href="javascript:document.getElementById('user_upd{$myid}').submit();">{$item}</a></td>
    {elseif $key eq 'id'}
    {assign var="myid" value=$item}
    {else}
    <td>{$item}</td>
    {/if}
  {/foreach}
  <td>
  	    <form action="user.php" method="post" name="user_upd{$myid}" id="user_upd{$myid}">
  			<input type="hidden" value="{$langId}" name="lang"/>
  			<input type="hidden" value="{$myid}" name="id"/>
  			<input type="hidden" value="upd" name="action"/>
  			<input type="submit" value="{$update}" class="SearchButton"/>
  	    </form></td><td> 
  	    <form action="user.php" method="post" name="user_del{$myid}" id="user_del{$myid}">
  			<input type="hidden" value="{$langId}" name="lang"/>
  			<input type="hidden" value="{$myid}" name="id"/>
  			<input type="hidden" value="del" name="action"/>
  			<input type="submit" value="{$delete}" class="SearchButton"/>
  	   </form> 
  </td>
  </tr>
{/foreach}
</table>
{if $myadm eq true}	<!-- Add user only if adm -->
	<a href="user.php?lang={$langId}&amp;action=new">{$adduser}</a>
{/if}
</div>
{include file="footer.tpl"}
