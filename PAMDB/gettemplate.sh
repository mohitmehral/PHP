#!/bin/sh
#
directory="htdocs/template"
wget -O "$directory/getHeader.txt" "http://webservices.eea.europa.eu/templates/getHeader?site=default"
wget -O "$directory/getRequiredHead.txt" "http://webservices.eea.europa.eu/templates/getRequiredHead?site=default"
wget -O "$directory/getFooter.txt" "http://webservices.eea.europa.eu/templates/getFooter?site=default"
