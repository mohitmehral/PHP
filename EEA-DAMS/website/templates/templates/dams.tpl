
{include file="header.tpl" type="dam"}

{include file="login.tpl"}
{include file="options.tpl"}


<div id="menu">

</div>

<div id="contents" align="middle">
{section name=dam loop=$dt}
  {if $smarty.section.dam.first}
  <h1></h1>
  <table width="50%" border="0" cellspacing="0" cellpadding="0"><tr><td>Name</td><td>Code</td><td>Score</td></tr>
  {/if}
  {if $dt[$smarty.section.dam.index_prev].valid neq 't' && $dt[$smarty.section.dam.index].valid eq 't'}
  </table>
  <br/><hr/><h1>Valid</h1>
  <table width="50%" border="0" cellspacing="0" cellpadding="0"><tr><td>Name</td><td>Code</td><td>Score</td></tr> 
  {/if}
  {if $dt[$smarty.section.dam.index_prev].country neq $dt[$smarty.section.dam.index].country}
  <tr><td colspan="4"><hr/><h1>{$dt[$smarty.section.dam.index].country}<h1></td></tr>
  {/if}
  <tr>
	<td><a href="dams.php?lang={$langId}&amp;cd={$dt[$smarty.section.dam.index].noeea}">{$dt[$smarty.section.dam.index].name}</a></td>
	<td>{$dt[$smarty.section.dam.index].noeea}</td>
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

