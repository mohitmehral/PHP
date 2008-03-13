#!/usr/bin/env python
import urllib


def makeurl(url, params):
    listparams = [ key + "=" + val for key,val in params.items()]
    return url + '&'.join(listparams)


def getamap(url,layers):
    params = {
    'REQUEST':'GetMap',
    'SERVICE':'WMS',
    'VERSION':'1.1.1',
    'LAYERS':layers,
    'STYLES':'',
    'FORMAT':'image/png',
    'BGCOLOR':'0xFFFFFF',
    'TRANSPARENT':'TRUE',
    'SRS':'EPSG:4326',
#   'BBOX':'13.85,50.9,13.95,51.0', # Elbe & Kiessee
#   'BBOX':'9.2,47.5,9.5,47.8', # Bodensee/Lake Constance
    'BBOX':'11.2,53.5,11.5,53.8', # Schweriner See
    'WIDTH':'512',
    'HEIGHT':'512',
    'reaspect':'false'
    }
    f = urllib.urlopen(makeurl(url,params))
    d = f.read()
    m = f.info()
    f.close()
    if m.gettype() in ("application/vnd.ogc.se_xml","text/html"):
        print d
        return
    mapf = file("testmap.png","wb")
    mapf.write(d)
    mapf.close()

from wmsclient import getcapabilities

if __name__ == '__main__':
    url="http://dampos-demo.eea.europa.eu/cgi-bin/wseea?"
#   getcapabilities(url)
    getamap(url,'ERM2Water')
