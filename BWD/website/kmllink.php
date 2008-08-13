<?php
header('Content-Disposition: attachment; filename="kmllink.kml"');
header('Content-type: application/vnd.google-earth.kml+xml');
echo '<?xml version="1.0" encoding="UTF-8"?>'
?>
<kml xmlns="http://earth.google.com/kml/2.0">
  <Document>
    <name>Bathing water</name>
    <description><![CDATA[Shows a selection of bathing waters - zoom to see all]]></description>
    <visibility>1</visibility>
    <open>1</open>
    <LookAt>
      <longitude>15.0</longitude>
      <latitude>51.0</latitude>
      <altitude>0</altitude>
      <range>3500000</range>
      <tilt>0</tilt>
      <heading>0.0</heading>
    </LookAt>
    <NetworkLink>
      <name>Bathing water locations</name>
      <description><![CDATA[Shows a selection of bathing waters - zoom to see all]]></description>
      <Url>
        <href>http://bwd.eea.europa.eu/kmlfetch.php</href>
        <viewRefreshMode>onStop</viewRefreshMode>
        <viewRefreshTime>3</viewRefreshTime>
      </Url>
    </NetworkLink>
  </Document>
</kml>
