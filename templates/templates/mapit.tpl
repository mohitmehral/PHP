{include file="header.tpl"}

{include file="legend.tpl"}

{include file="login.tpl"}
{if $login eq true}{include file="options.tpl"}{/if}
<div id="menu">

</div>

<div id="contents">      
	<div align="center">
		<div id="map" style="width: 400px; height: 400px; "></div>
	</div>
</div>
{include file="footer.tpl"}

{$map}

