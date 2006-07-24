<?php
header('Content-Disposition: attachment; filename="link.kml"');
header('Content-type: application/vnd.google-earth.kml+xml');
echo '<?xml version="1.0" encoding="UTF-8"?>'
?>
<kml xmlns="http://earth.google.com/kml/2.0">
	<Document>
		<name>European Dams</name>
		<Snippet>Dams recorded in the EEA database.</Snippet>
		<description><![CDATA[<p>Shows the 20 largest in view at any time.</p>
		]]></description>
		<visibility>1</visibility>
		<open>1</open>
		<LookAt>
			<longitude>16</longitude>
			<latitude>47</latitude>
			<range>5000000.0</range>
			<tilt>0.0</tilt>
			<heading>0.0</heading>
		</LookAt>
		<NetworkLink>
			<name>Validated dams</name>
			<Snippet>Dams validated in Dampos</Snippet>
			<description><![CDATA[<p>Reading from DAMPOS service</p>
				]]></description>
			<visibility>1</visibility>
			<open>1</open>				
			<Url>
				<href>http://dampos.eea.europa.eu/showkml.php?coordinates=val</href>
				<viewRefreshMode>onStop</viewRefreshMode>
				<viewRefreshTime>1</viewRefreshTime>
			</Url>
		</NetworkLink>
		<NetworkLink>
			<name>Proposed dams</name>
			<Snippet>Proposed dams</Snippet>
			<description><![CDATA[<p>Reading from DAMPOS service</p>
				]]></description>
			<visibility>1</visibility>
			<open>1</open>				
			<Url>
				<href>http://dampos.eea.europa.eu/showkml.php?coordinates=prop</href>
				<viewRefreshMode>onStop</viewRefreshMode>
				<viewRefreshTime>1</viewRefreshTime>
			</Url>
		</NetworkLink>
		<NetworkLink>
			<name>iCold dams</name>
			<Snippet>iCold dams</Snippet>
			<description><![CDATA[<p>Reading from DAMPOS service</p>
				]]></description>
			<visibility>1</visibility>
			<open>1</open>				
			<Url>
				<href>http://dampos.eea.europa.eu/showkml.php?coordinates=icold</href>
				<viewRefreshMode>onStop</viewRefreshMode>
				<viewRefreshTime>1</viewRefreshTime>
			</Url>
		</NetworkLink>
	</Document>
</kml>
