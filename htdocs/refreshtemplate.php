<?
function http_get($url, $filename, $range = 0)
{
    $buffer = '';
    $url_stuff = parse_url($url);
    $port = isset($url_stuff['port']) ? $url_stuff['port'] : 80;
   
    $fp = @fsockopen($url_stuff['host'], $port);
   
    if (!$fp)
        return false;
   
    $query  = 'GET '.$url_stuff['path'].'?'.$url_stuff['query']." HTTP/1.1\r\n";
    $query .= 'Host: '.$url_stuff['host']."\r\n";
    $query .= 'Connection: close'."\r\n";
    $query .= 'Cache-Control: no'."\r\n";
    $query .= 'Accept-Ranges: bytes'."\r\n";
    if ($range != 0)
        $query .= 'Range: bytes='.$range.'-'."\r\n"; // -500
    //$query .= 'Referer: http:/...'."\r\n";
    //$query .= 'User-Agent: myphp'."\r\n";
    $query .= "\r\n";
   
    fwrite($fp, $query);
   
    $chunksize = 1*(1024*1024);
    $headersfound = false;

    while (!feof($fp) && !$headersfound) {
        $buffer .= @fread($fp, 1);
        if (preg_match('/HTTP\/[0-9]\.[0-9][ ]+([0-9]{3}).*\r\n/', $buffer, $matches)) {
            $headers['HTTP'] = $matches[1];
            $buffer = '';
        } else if (preg_match('/([^:][A-Za-z_-]+):[ ]+(.*)\r\n/', $buffer, $matches)) {
            $headers[$matches[1]] = $matches[2];
            $buffer = '';
        } else if (preg_match('/^\r\n/', $buffer)) {
            $headersfound = true;
            $buffer = '';
        }

        if (strlen($buffer) >= $chunksize)
            return false;
    }

    if (preg_match('/4[0-9]{2}/', $headers['HTTP']))
        return false;
    else if (preg_match('/3[0-9]{2}/', $headers['HTTP']) && !empty($headers['Location'])) {
        $url = $headers['Location'];
        return http_get($url, $range);
    }

    $lfp = fopen($filename,"w");
    while (!feof($fp) && $headersfound) {
        $buffer = @fread($fp, $chunksize);
        fwrite($lfp, $buffer);
        ob_flush();
        flush();
    }
    fclose($lfp);

    $status = fclose($fp);

    return $status;
}

http_get("http://webservices.eea.europa.eu/templates/getHeader?site=default","template/getHeader.txt");
http_get("http://webservices.eea.europa.eu/templates/getRequiredHead?site=default", "template/getRequiredHead.txt");
http_get("http://webservices.eea.europa.eu/templates/getFooter?site=default", "template/getFooter.txt");

?>
