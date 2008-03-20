{include file="header.tpl" type="dam" pagetitle="Dams"}
{include file="login.tpl"}
<!-- Table sorting -->
<link rel="stylesheet" type="text/css" href="css/sortable.css" />
<script language="JavaScript" type="text/javascript"  src="js/css.js"></script>
<script language="JavaScript" type="text/javascript"  src="js/common.js"></script>
<script language="JavaScript" type="text/javascript"  src="js/standardista-table-sorting.js"></script>
<div id="workarea">
  <table width="100%" class="sortable">
    <thead>
      <tr>
        <th>Country</th>
        <th>Validated dams</th>
        <th>Invalided dams</th>
      </tr>
    </thead>
    <tbody>
      {foreach from=$dt item=cntry name=dl}
        <tr {if $smarty.foreach.dl.index % 2 == 1}class="odd"{/if}>
          <td><a href="dams.php?srcCountry={$cntry->country_code}" title="Click to see all the dams for this country">{$cntry->country_code}</a></td>
          <td>{if isset( $cntry->validatedDams ) }{$cntry->validatedDams}{else}0{/if}</td>
          <td>{if isset( $cntry->invalidatedDams ) }{$cntry->invalidatedDams}{else}0{/if}</td>
        </tr>
      {foreachelse}
        <tr>
          <td colspan="3">No dams found matching your criteria</td>
        </tr>
      {/foreach}
    </tbody>
  </table>
</div>
{include file="footer.tpl"}
