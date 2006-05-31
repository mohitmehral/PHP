{config_load file=test.conf section="setup"}
{include file="header.tpl"}


{include file="login.tpl"}
{if $login eq true}
	{include file="options.tpl"}
	{include file="legend.tpl"}
{/if}
<div id="menu">

</div>

<div id="contents">      
	<div align="left"><p>{$desc}</p>
	<br/>
	{if $roleAdm eq 't'}
		<br/>
		<b>{$damMap}</b>
		<br/>
		<ul id="countries">
		{foreach name=outer item=val from=$damCountryFilter}
			<li><a href="mapit.php?country={$val}">{$val}</a></li>
		{/foreach}
		</ul>
	<p style="clear:left">Download :</p>
		<ul>
		<li><a href='download.php?act=dam'>dams as CSV</a> <a href='downloadxml.php?act=dam'>/as XML</a></li>
		<li><a href='download.php?act=use'>users as CSV</a> <a href='downloadxml.php?act=use'>/as XML</a></li>
		<li><a href='download.php?act=udl'>users dams link as CSV</a> <a href='downloadxml.php?act=udl'>/as XML</a></li>
		</ul>
	{/if}
	</div>
	<div align="center">
		<div id="map" style="width: 400px; height: 400px; "></div>
	</div>
</div>
{include file="footer.tpl"}

{$map}

