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
		<ul>
		{foreach name=outer item=val from=$damCountryFilter}
			<li><a href="mapit.php?country={$val}">{$val}</a></li>
		{/foreach}
		</ul>
	<br/><p>Download : <ul><li><a href='download.php?act=dam'>dams</a></li>
		<li><a href='download.php?act=use'>users</a></li>
		<li><a href='download.php?act=udl'>users dams link</a></li></ul>
	 </p>
	{/if}
	</div>
	<div align="center">
		<div id="map" style="width: 400px; height: 400px; "></div>
	</div>
</div>
{include file="footer.tpl"}

{$map}

