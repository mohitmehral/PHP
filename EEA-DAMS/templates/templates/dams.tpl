
{include file="header.tpl" type="dam"}

{include file="login.tpl"}
{include file="options.tpl"}


<div id="menu">

</div>

<div id="contents">
{section name=dam loop=$dt}
  {if $smarty.section.dam.first}
  <table>
  {/if}

  <tr>
	<td>{$dt[$smarty.section.dam.index].noeea}</td>
	<td><a href="dams.php?lang={$langId}&amp;cd={$dt[$smarty.section.dam.index].noeea}">{$dt[$smarty.section.dam.index].name}</a></td>
	<td><!--<input type="checkbox" disabled="disabled" name="valid" {if $dt[$smarty.section.dam.index].valid eq 't'}checked="checked"{/if}/>-->
		{if $dt[$smarty.section.dam.index].valid eq 't'}<img width="15" src="{$VALIDICON}" alt="valid"/>{else}{/if}</td>
	<td>{$dt[$smarty.section.dam.index].score}</td>
  </tr>

  {if $smarty.section.dam.last}
    </table>
  {/if}
{/section}

</div>
{include file="footer.tpl"}

