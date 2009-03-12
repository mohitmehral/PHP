#!/bin/sh
#
directory="htdocs"
wget -O "$directory/getHeader.php" "http://webservices.eea.europa.eu/templates/getHeader?site=default"
wget -O "$directory/getRequiredHead.php" "http://webservices.eea.europa.eu/templates/getRequiredHead?site=default"
wget -O "$directory/getFooter.php" "http://webservices.eea.europa.eu/templates/getFooter?site=default"
