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
		<li><a href='download.php?act=dam'>dams as CSV</a> <a href='downloadxml.php?act=dam'>/as XML</a></li>
		<li><a href='download.php?act=use'>users as CSV</a> <a href='downloadxml.php?act=use'>/as XML</a></li>
		<li><a href='download.php?act=udl'>users dams link as CSV</a> <a href='downloadxml.php?act=udl'>/as XML</a></li>
		</ul>
	{/if}
	<div align="center">
		<div id="map" style="width: 400px; height: 400px; "></div>
	</div>
</div>
{include file="footer.tpl"}

{$map}

