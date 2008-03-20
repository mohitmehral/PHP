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
        <th>Name</th>
        <th>Code</th>
        <th>Country</th>
        <th>Valid</th>
      </tr>
    </thead>
    <tbody>
      {foreach from=$dt item=dam name=dl}
        <tr {if $smarty.foreach.dl.index % 2 == 1}class="odd"{/if}>
          <td><a href="dams.php?cd={$dam->code}" title="Click to see this dam on the map">{$dam->name}</a></td>
          <td>{$dam->code}</td>
          <td>{$dam->country}</td>
          <td>{if $dam->valid}1{else}0{/if}</td>
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
