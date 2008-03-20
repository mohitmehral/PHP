{include file="header.tpl" type="dam" pagetitle="Dams"}
{include file="login.tpl"}
<div id="workarea">
  {section name=dam loop=$dt}
    {if $smarty.section.dam.first}
      {if $dt[$smarty.section.dam.index].valid eq 't'}
        <h1>Valid</h1>
      {/if}
        <table width="100%" class="datatable">
          <col style="width: 70%"/>
          <col style="width: 20%"/>
          <col style="width: 5%"/>
          <col style="width: 5%"/>
          <tr>
            <th>Name</th>
            <th>Code</th>
            <th colspan="2">Score</th>
          </tr>
    {else}
      {if $dt[$smarty.section.dam.index_prev].valid neq 't' && $dt[$smarty.section.dam.index].valid eq 't'}
        </table>
        <hr/>
          <h1>Valid</h1>
          <table width="100%" class="datatable">
            <col style="width: 70%"/>
            <col style="width: 20%"/>
            <col style="width: 5%"/>
            <col style="width: 5%"/>
            <tr>
              <th>Name</th>
              <th>Code</th>
              <th colspan="2">Score</th>
            </tr> 
      {/if}
    {/if}
    {if $dt[$smarty.section.dam.index_prev].country neq $dt[$smarty.section.dam.index].country}
      <tr>
        <th colspan="4" style="text-align:left; font-size:125%">{$dt[$smarty.section.dam.index].country}</th>
      </tr>
    {/if}
    <tr>
      <td><a href="dams.php?lang={$langId}&amp;cd={$dt[$smarty.section.dam.index].noeea}">{$dt[$smarty.section.dam.index].name}</a></td>
      <td>{$dt[$smarty.section.dam.index].noeea}</td>
      <td><!--<input type="checkbox" disabled="disabled" name="valid" {if $dt[$smarty.section.dam.index].valid eq 't'}checked="checked"{/if}/>-->
      {if $dt[$smarty.section.dam.index].valid eq 't'}<img width="15" src="{$VALIDICON}" title="Valid" alt="valid"/>{else}{/if}</td>
      <td>{$dt[$smarty.section.dam.index].score}</td>
    </tr>
    {if $smarty.section.dam.last}
  </table>
    {/if}
  {/section}
</div>
{include file="footer.tpl"}

