<!DOCTYPE html 
     PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "DTD/xhtml1-Transitional.dtd">
<!--<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">-->
<html xmlns="http://www.w3.org/1999/xhtml" lang="{$langId}">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<meta name="Title"      		content="The European Environment Agency"/>
	<meta name="Publisher"  		content="EEA, The European Environment Agency"/>
	<meta name="Rights"     		content="Copyright EEA Copenhagen 1993-2004"/>
	<link rel="stylesheet" 		type="text/css" href="css/style.css"/>
	<link rel="stylesheet" 		type="text/css" href="css/damstyle.css"/>
	<link rel="stylesheet" 		type="text/css" href="css/folders.css"/>
	<script type="text/javascript" 	src="js/EventManager.js"></script>
	<script type="text/javascript" 	src="js/script.js"></script>
	<script type="text/javascript" src="js/ajax.js"></script>
	<!--<script type="text/javascript" src="js/GMaps_WMSSpec_0.4.js"></script>-->
	<!--<script type="text/javascript" 	src="js/gmap-wms.js"></script>-->
	<!-- TODO : Set Google on --> 
	<script src="http://maps.google.com/maps?file=api&amp;v=1&amp;key={$GOOGLEMAPKEY}" type="text/javascript"></script>
	<title>{$Name}</title>
</head>
<body onunload="GUnload()">
<div id="title">
<!-- Source : EEA website / Start -->
<!-- Sitebanner, including Search  -->
<table align="center" width="100%" cellspacing="0" cellpadding="0" border="0" class="TopBanner">
   <tr>
       <!-- left area (not fixed) -->
      <td valign="top" height="63" class="TopBannerLeft" width="470">

         <table width="100%" cellspacing="0" cellpadding="0" border="0" class="TopBannerLeft">
            <tr>
              <td height="63" class="TopBannerLeft"></td>
            </tr>
        </table>
      </td>
      <!-- middle area (fixed) -->
      <td align="center" valign="top" height="63" class="TopBannerMain">
         <table width="770" cellspacing="0" cellpadding="0" border="0" class="TopBannerMain">

            <tr>
               <td align="right" valign="top" class="TopBannerMain"  width="70"   ><a href="/"><img src="css/logo.jpg" alt="Home" title="Home" height="63" width="70" border="0" /></a></td>
               
               <td width="470" valign="top" align="left" class="TopBannerMain"    ><a href="/"><img src="css/banner.gif" alt="Home" title="Home" height="63" width="470" border="0" /></a></td>
               <!-- search box -->
              
               <td class="TopBannerRight" align="right" valign="bottom" width="230" height="63"> 
                  
               </td>

            </tr>
         </table>
      </td>
      <!-- right area (not fixed) -->
     
      <td valign="top" height="63" class="TopBannerRight" width="470">
         <table width="100%" cellspacing="0" cellpadding="0" border="0" class="TopBannerRight" >
            <tr>
               <td height="63" class="TopBannerRight"></td>
            </tr>
         </table>
      </td>
   </tr>
</table>
<!-- Source : EEA website / End -->


<!--        Menu                   -->
<table width="100%" border="0" cellspacing="3" cellpadding="0">
   <tr><td height="22" colspan="2" style="color : black; border : 1px black solid;" align="center"><b>{$Name}</b></td></tr>
   <tr>
      <td valign="top" rowspan="10">
      {if $login eq true}
      <a href="index.php?lang={$langId}">{$home}</a> | 
      <a href="user.php?action=upd&amp;id={$mnuUserId}&amp;lang={$langId}">{$profilManage}</a> | 
      <a href="dams.php?lang={$langId}&amp;{$urlFilter}">{$damValidation}</a> | 
      {if $roleAdm eq 't'}
      	<a href="users.php?lang={$langId}">{$userAdm}</a> | 
      {/if}
      <a href="i18n.php?lang={$langId}">{$translationManage}</a> |
      {/if}
      </td><td><form id="langForm" method="get" action="#">
      <select name="lang" onchange="document.getElementById('langForm').submit();">
		  {html_options values=$langIds selected=$langId output=$langNames}
	  </select>
	  </form>
      </td>
      <td><a href='help.php'>{$help}</a></td>
	</tr>
</table>

</div>
