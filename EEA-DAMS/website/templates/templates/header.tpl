<!DOCTYPE html 
     PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "DTD/xhtml1-Transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{$langId}">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="http://www.eionet.europa.eu/styles/eionet2007/screen.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="http://www.eionet.europa.eu/styles/eionet2007/print.css" media="print" />
    <link rel="stylesheet" type="text/css" href="http://www.eionet.europa.eu/styles/eionet2007/handheld.css" media="handheld" />

    <link rel="stylesheet" 		type="text/css" href="css/damstyle.css"/>
    <link rel="stylesheet" 		type="text/css" href="css/folders.css"/>
    <script type="text/javascript" 	src="js/EventManager.js"></script>
    <script type="text/javascript" 	src="js/script.js"></script>
    <script type="text/javascript" src="js/ajax.js"></script>
    <script type="text/javascript" src="js/pageops.js"></script>
    <!--<script type="text/javascript" src="js/GMaps_WMSSpec_0.4.js"></script>-->
    <!--<script type="text/javascript" 	src="js/gmap-wms.js"></script>-->
    <!-- TODO : Set Google on --> 
    <script src="http://maps.google.com/maps?file=api&amp;v=1&amp;key={$GOOGLEMAPKEY}" type="text/javascript"></script>
    <title>{$Name}</title>
</head>
<body onunload="GUnload()" class="{$bodyclass}">
    <div id="container">
      <div id="toolribbon">
        <div id="lefttools">
          <a id="eealink" href="http://www.eea.europa.eu/">EEA</a>
          <a id="ewlink" href="http://www.ewindows.eu.org/">EnviroWindows</a>
        </div>
        <div id="righttools">
          {if $login eq false}<a id="loginlink" href="loginpage.php?lang={$langId}"><span>{$loginin}</span></a>
          {elseif $login eq true}<a id="logoutlink" href="index.php?act=logout&amp;lang={$langId}">{$logout} ({$useracc})</span></a>
          {/if}
          <a id="printlink" title="Print this page" href="javascript:this.print();"><span>Print</span></a>
          <a id="fullscreenlink" href="javascript:toggleFullScreenMode()" title="Switch to/from full screen mode"><span>Switch to/from full screen mode</span></a>
          <a id="pagehelplink" href="help.php" title="Help for this page"><span>{$help}</span></a>
          <a id="acronymlink" href="/acronyms" title="Look up acronyms"><span>Acronyms</span></a>
          <form action="http://search.eionet.europa.eu/search.jsp" method="get"><div id="freesrchform"><label for="freesrchfld">Search</label>
            <input id="freesrchfld" name="query"  size="10" type="text" onfocus="if(this.value=='Search Eionet')this.value='';" onblur="if(this.value=='')this.value='Search Eionet';" title="Search Eionet" value="Search Eionet"/>
            <input id="freesrchbtn" type="image" src="css/button_go.gif" alt="Go"/></div></form>
        </div>
      </div> <!-- toolribbon -->
      <div id="pagehead">
        <a href="/"><img src="css/eealogo.gif" alt="Logo" id="logo" width="428" height="87" /></a>
        <div id="networktitle">Eionet</div>
        <div id="sitetitle">{$Name}</div>
        <div id="sitetagline">Place the dam on the map</div>
      </div>
      <div id="menuribbon">
{include file="dropdownmenus.xml"}
      </div>

      <div class="breadcrumbtrail">
	<div class="breadcrumbhead">You are here:</div>
	<div class="breadcrumbitem eionetaccronym"><a href="http://www.eionet.europa.eu">Eionet</a></div>
	<div class="breadcrumbitem"><a href="/">DAMPOS</a></div>
	<div class="breadcrumbitemlast">Frontpage</div>
	<div class="breadcrumbtail"></div>
      </div>

      <div id="leftcolumn" class="localnav">
<!--        Menu                   -->
      {if $login eq true}
      <ul>
      <li><a href="index.php?lang={$langId}">{$home} </a></li>
      <li><a href="user.php?action=upd&amp;id={$mnuUserId}&amp;lang={$langId}">{$profilManage} </a></li>
      <li><a href="dams.php?lang={$langId}&amp;{$urlFilter}">{$damValidation} </a></li>
      {if $roleAdm eq 't'}
      	<li><a href="users.php?lang={$langId}">{$userAdm} </a></li>
      {/if}
      <li><a href="i18n.php?lang={$langId}">{$translationManage} </a></li>
      </ul>
      {/if}
      <form id="langForm" method="get" action="#">
      <div>
      <select name="lang" onchange="document.getElementById('langForm').submit();">
		  {html_options values=$langIds selected=$langId output=$langNames}
	  </select>
      </div>
      </form>
{include file="legend.tpl"}
{if $login eq true}
{include file="options.tpl"}
{/if}
      </div>
