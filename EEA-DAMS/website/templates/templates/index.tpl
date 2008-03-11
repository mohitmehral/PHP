{config_load file=test.conf section="setup"}
{include file="header.tpl" pagetitle=""}
{include file="login.tpl"}
<div id="workarea">
	<p>{$desc}</p>
  {if $roleAdm eq 't'}
  <h2>{$damMap}</h2>
	<ul id="countries">
	{foreach name=outer item=val from=$damCountryFilter}
    <li><a href="mapit.php?country={$val}">{$val}</a></li>
		{/foreach}
		</ul>
	<h2 style="clear:left">Download</h2>
  <ul>
    <li><a href='download.php?act=dam'>dams as CSV</a> <a href='downloadxml.php?act=dam'>/as XML</a> <a href='downloadkml.php?act=dam'>/as KML</a></li>
		<li><a href='download.php?act=use'>users as CSV</a> <a href='downloadxml.php?act=use'>/as XML</a></li>
		<li><a href='download.php?act=udl'>users dams link as CSV</a> <a href='downloadxml.php?act=udl'>/as XML</a></li>
  </ul>
	{/if}
	<div align="center">
	  <div style="float: right; width: 130px; text-align: left;">
	    Layers:
	    <br />
	    <br />
      <input id="test_overlay" type="checkbox" name="test" onclick="onClickOverlay(this)" title="Turn EEA layer" />
      <label for="test_overlay">Test</label>
      <br />
      <input id="i2k_overlay" type="checkbox" name="i2k_overlay" onclick="onClickOverlay(this)" title="Turn layer" />
      <label for="i2k_overlay">Image 2000</label>
      <br />
      <input id="eea_overlay" type="checkbox" name="i2k_overlay" onclick="onClickOverlay(this)" title="Turn layer" />
      <label for="eea_overlay">EEA WMS</label>
    </div>
		<div id="map" style="width: 500px; height: 400px; "></div>
		{literal}
    <script type="text/javascript" language="JavaScript">
      function onClickOverlay(callerObject)
      {
        var layerObject = eval( callerObject.id );
        if( callerObject.checked )
        {
          layerObject.show();
          return;
        }
        layerObject.hide();
      }
	  </script>
	  {/literal}
	</div>
</div>
{$map}
{include file="footer.tpl"}