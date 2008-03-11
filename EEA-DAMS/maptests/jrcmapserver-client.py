#!/usr/bin/env python
import urllib
import xml.dom.minidom

def makeurl(url, params):
    listparams = [ key + "=" + val for key,val in params.items()]
    return url + '&'.join(listparams)


def getamap(url):
    params = {
    'REQUEST':'GetMap',
    'SERVICE':'WMS',
    'VERSION':'1.1.1',
    'LAYERS':'0',
    'STYLES':'',
    'FORMAT':'image/png',
    'BGCOLOR':'0xFFFFFF',
    'TRANSPARENT':'TRUE',
    'SRS':'EPSG:4326',
#   'BBOX':'22.5,21.94304553343818,33.75,31.95216223802497',
    'BBOX':'-12.075000,34.500000,32.775000,70.725000',
    'WIDTH':'256',
    'HEIGHT':'256',
    'reaspect':'false'
    }
    f = urllib.urlopen(makeurl(url,params))
    d = f.read()
    print f.info()
    f.close()
    mapf = file("testmap.png","wb")
    mapf.write(d)
    mapf.close()

from wmsclient import getcapabilities

if __name__ == '__main__':
    url="http://mapserver.jrc.it/wmsconnector/com.esri.wms.Esrimap/image2000_pan?"
    getcapabilities(url)
    getamap(url)
