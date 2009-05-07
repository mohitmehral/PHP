<?php
function build_sortqs()
{
	foreach ($_GET as $valve => $value) {
		if (is_array($value)) {
			foreach ($value as $option) {
				echo "&amp;" . $valve . "[]=" . $option;
			}
		} else {
			if ($valve != "sort") {echo "&amp;" . $valve . "=" . $value;}
		}

	}
}

function breadcrumbs($page)
{
  echo '<div id="portal-breadcrumbs">';
  echo '<span dir="ltr"><a href="http://www.eea.europa.eu/" class="breadcrumbitem" >Home</a></span>';
  if ($page == "")
     echo "<span dir='ltr'><span class='breadcrumbitemlast'>PAM</span></span>";
  else
     echo "<span dir='ltr'><a href='/' class='breadcrumbitem'>PAM</a></span><span dir='ltr'><span class='breadcrumbitemlast'>$page</span></span>";
  echo '</div>';
}


function left_portlet()
{
?>
<dl class="portlet" id="portlet-navigation-tree">
  <dt class="portletHeader">
    <span class="portletTopLeft"></span>

    <a href="index" class="tile">PAM</a>
    <span class="portletTopRight"></span>
  </dt>
  <dd class="portletItem lastItem">
    <ul class="portletNavigationTree navTreeLevel0">
      <li class="navTreeItem visualNoMarker">
        <a class="navItemLevel1" href="sector" accesskey="e" title="Switch to expert search mode">Expert search mode</a>
      </li>

      <li class="navTreeItem visualNoMarker">
        <a class="navItemLevel1" href="index" accesskey="n" title="Switch to normal search mode">Normal search mode</a>
      </li>
    </ul>

    <span class="portletBottomLeft"></span>
    <span class="portletBottomRight"></span>

  </dd>
  <dt class="portletHeader">
    Information
  </dt>
  <dd class="portletItem lastItem">
    <ul class="portletNavigationTree navTreeLevel0">
      <li class="navTreeItem visualNoMarker">
        <a class="navItemLevel1" title="Introduction to PAM Database" href="introduction">Introduction</a>
      </li>
      <li class="navTreeItem visualNoMarker">
        <a class="navItemLevel1" title="About PAM Database" href="about" accesskey="b">About PAM</a>
      </li>
      <li class="navTreeItem visualNoMarker">
        <a class="navItemLevel1" href="copyright" title="Copyright and Privacy Policy for PAM Database">PAM Copyright and Disclaimer</a>
      </li>
      <li class="navTreeItem visualNoMarker">
        <a class="navItemLevel1" href="accessibility" title="Accessibility statement" accesskey="0">Accessibility statement</a>
      </li>
    </ul>

    <span class="portletBottomLeft"></span>
    <span class="portletBottomRight"></span>
  </dd>
</dl>
<?php
}


function standard_html_header($page)
{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
<?php include('template/getRequiredHead.txt'); ?>
		<title>
			<?echo $page ?> - Policies and Measures in Europe - ECCP
		</title>
		<link href="frm.css" rel="stylesheet" type="text/css"/>
	</head>
	<body>
		<div id="visual-portal-wrapper">
<?php include('template/getHeader.txt'); ?>
                <!-- The wrapper div. It contains the three columns. -->
                        <div id="portal-columns" class="visualColumnHideTwo">
                        <!-- start of the main and left columns -->
                        <div id="visual-column-wrapper">
                                <!-- start of main content block -->
                                <div id="portal-column-content">
                                        <div id="content" class="">
                                                <div class="documentContent panels" id="region-content">
							<?php breadcrumbs($page); ?>
                                                        <a name="documentContent"></a>
                                                        <div>
                                                                <div class="documentActions">
                                                                <h5 class="hiddenStructure">Document Actions</h5>
                                                                <ul>
                                                                        <li>
                                                                                <a href="javascript:this.print();"><img src="http://webservices.eea.europa.eu/templates/print_icon.gif"
                                                                        alt="Print this page"
                                                                        title="Print this page" /></a>
                                                                        </li>
                                                                        <li>
                                                                                <a href="javascript:toggleFullScreenMode();"><img src="http://webservices.eea.europa.eu/templates/fullscreenexpand_icon.gif"
                                                                        alt="Toggle full screen mode"
                                                                        title="Toggle full screen mode" /></a>
                                                                        </li>
                                                                </ul>
                                                                </div>
<?php
}

function standard_html_footer()
{
?>
<!-- END MAIN CONTENT -->
</div>
              </div>
            </div>
          </div>
          <!-- end of main content block -->
          <!-- start of the left (by default at least) column -->
          <div id="portal-column-one">
            <div class="visualPadding">
      <?php left_portlet() ?>
            </div>
          </div>
          <!-- end of the left (by default at least) column -->
        </div>
        <!-- end of the main and left columns -->
        <div class="visualClear"><!-- --></div>
      </div>
      <!-- end column wrapper -->
      <?php include('template/getFooter.txt'); ?>
    </div>
  </body>
</html>

<?php
}

function sql_error($table, $sql)
{
    echo("<div class=\"error-msg\"><p>Error in querying table: \"$table\".</p><p>$sql</p></div>");
}

?>
