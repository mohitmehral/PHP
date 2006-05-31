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
	<div align="left">{$helptext}
	</div>
</div>
{include file="footer.tpl"}


