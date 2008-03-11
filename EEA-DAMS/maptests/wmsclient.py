#!/usr/bin/env python
import urllib
import xml.dom.minidom

def makeurl(url, params):
    listparams = [ key + "=" + val for key,val in params.items()]
    return url + '&'.join(listparams)


def getcapabilities(url):
    params = {
    'REQUEST':'GetCapabilities',
    'SERVICE':'WMS',
    'VERSION':'1.1.1',
    }
    f = urllib.urlopen(makeurl(url,params))
    capab = f.read()
    print capab
    doc = xml.dom.minidom.parseString(capab)
    root = doc.documentElement
    # top-level OnlineResource URL
    eService = root.getElementsByTagName('Service')[0]
    eCap = root.getElementsByTagName('Capability')[0]
    topLayers = eCap.getElementsByTagName('Layer')
    layers = []
    for eLayer in topLayers:
        try:
            eName = eLayer.getElementsByTagName('Name')[0]
        except:
            continue
        name = eName.firstChild.data
        eTitle = eLayer.getElementsByTagName('Title')[0]
        title = eTitle.firstChild.data
        try:
            eBBOX = eLayer.getElementsByTagName('BoundingBox')[0]
            srs = eBBOX.getAttribute('SRS')
        except:
            # try to get bounding box from the parent node
            try:
                eParent = eLayer.parentNode
                eBBOX = eParent.getElementsByTagName('BoundingBox')[0]
                srs = eBBOX.getAttribute('SRS')
            except:
                # fall back on LatLongBoundingBox
                try:
                    eBBOX = eLayer.getElementsByTagName('LatLonBoundingBox')[0]
                    srs = 'EPSG:4326'
                except:
                    continue
        bbox = {}
        bbox['minx'] = float(eBBOX.getAttribute('minx'))
        bbox['miny'] = float(eBBOX.getAttribute('miny'))
        bbox['maxx'] = float(eBBOX.getAttribute('maxx'))
        bbox['maxy'] = float(eBBOX.getAttribute('maxy'))
        layers.append({'name': name, 'title': title, 'srs': srs,
                       'bbox': bbox})
    for layer in layers:
        print "Name:%s, title:%s, SRS:%s" % ( layer['name'], layer['title'],layer['srs'])
        bbox = layer['bbox']
        print "    Bounding box: minx:%f miny:%f maxx:%f maxy:%f" % (bbox['minx'], bbox['miny'], bbox['maxx'], bbox['maxy'])

    f.close()

if __name__ == '__main__':
    url="http://mapserver.jrc.it/wmsconnector/com.esri.wms.Esrimap/image2000_pan?"
    getcapabilities(url)
#   getamap(url)
