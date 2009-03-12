<?php
$pos_mes = FALSE;
include('conx/db_conx_open.php');
require_once 'support.php';
standard_html_header("")
?>
                <h1>
                Accessibility statement
                </h1>
                <br />
                <h2>Keyboard shortcuts</h2>
<p>Most browsers support jumping to specific links by typing 
keys defined on the web site. On Windows, you can press
<strong>ALT + an access key</strong>; on Macintosh, you can 
press <strong>Control + an access key</strong> (this also depends on the browser used). In this 
website the following keyboard shortcuts are defined:</p>
<ul>
<li>
access key 1 - link to home page 
</li>
<li>
access key 9 - feedback and helpdesk 
</li>
<li>
access key 0 - link to accessibility statement (this page) 
</li>
<li>
access key l - link to login 
</li>
<li>
access key r - link to references
</li>
<li>
access key p - link to reports
</li>
<li>
access key s - link to Species module
</li>
<li>
access key h - link to Habitat types module
</li>
<li>
access key t - link to Sites module
</li>
<li>
access key 3 - link to site map
</li>
<li>
access key g - link to glossary
</li>
<li>
access key u - link to GIS Tool
</li>
<li>
access key b - link to introduction on EUNIS Database
</li>
</ul>
<h2>Standards compliance</h2>
<ul>
<li>the
<a title="Web Content Accessibility Guidelines" href="http://www.w3.org/TR/WAI-WEBCONTENT/wai-pageauth.html">
Web Content Accessibility Guidelines 1.0</a> (WAI-AA) 
</li>
<li>pages validate
<a title="XHML specifications" href="http://www.w3.org/TR/xhtml1/">XHTML 1.0 
Transitional</a></li>
<li>pages validate HTML 4.01 </li>
<li>the Cascading Style Sheets implemented respect the
<a title="CSS2 specifications" href="http://www.w3.org/TR/REC-CSS2/">CSS2 
Specification</a> </li>
<li>the web site is still usable without style sheets</li>
<li>there are still some special pages, like the GIS Tool - implemented in 
Macromedia Flash and some pages where navigation is based heavily on JavaScript 
and specific HTML elements where equivalent accessible page content can't be 
provided and the browsers which don't implement JavaScript will experience 
reduced functionality. Special measures where taken to announce the user when 
some functionality is available only on some browsers.</li>
</ul>
<h2>Navigation aids</h2>
<ul>
<li>the bread crumb trail - links to all parent folders 
from the current location to the root 
</li>
<li>the navigation for the regular end-users does not 
imply opening of pop-ups windows (note that some of the 
administrative pages with more complex functionality do) 
and the return to the previous page can be easily done 
through the <em>back</em> button from the browser</li>
<li>an accessibility link to the main content for every page is provided</li>
</ul>
<h2>Links</h2>
<ul>
<li>links have <em>TITLE</em> attributes which describe the link</li>
<li>links' names make sense out of context 
</li>
<li>the links' color is chosen to contrast sufficiently 
with the background color, and they are distinguishable 
by having a different text-decoration. Users can choose a personalized theme for 
the web site.  
</li>
<li>visited links and active ones are marked with 
different colors </li>
</ul>
<h2>Images</h2>
<ul>
<li>all content images used in the website include 
descriptive <em>ALT</em> attributes 
</li>
<li>purely decorative graphics include null <em>ALT</em> 
attributes so they will not be interpreted by speech 
synthesizers </li>
</ul>
<h2>Visual design</h2>
<ul>
<li>this website uses cascading style sheets (CSS2) for visual layout 
</li>
<li>only relative font sizes are used, compatible with 
the user-specified "text size" option in visual browsers 
</li>
<li>the website uses color safely. The pages layout is 
designed in web safe colors 
</li>
<li>if your browser or browsing device used does not 
support stylesheets at all, the content of each page is 
still readable and comprehensible </li>
</ul>
<h2>Content structure and other specific elements</h2>
<ul>
<li>The HTML code of every page starts with the 
appropriate DOCTYPE declaration</li>
<li>The language is identified on every page of the web 
site</li>
<li>The heading(s) element of every page - h5 and h6 - have a title 
specific to he page content:
</li>
<li>Semantically correct markup: the content is well 
structured and uses the correct HTML tags for the 
various semantic elements (headers, lists, accessible table information, etc.) 
</li>
<li>The web site does not uses frames, multimedia 
content (video, audio)</li>
<li>JavaScript is necessary to be enabled in some of the 
more complex pages</li>
<li><em>LABELs</em> are used for all visible form elements</li>
<li>Tables linearize correctly - web site can be used in text based browsers 
like Lynx</li>
<li>Metadata information is present in all pages</li>
<li><em>TITLEs</em> are used in every page to provide enhanced user experience and 
accessibility</li>
</ul>
<?php standard_html_footer() ?>                
