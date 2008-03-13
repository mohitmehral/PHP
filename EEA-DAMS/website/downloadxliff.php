<?php
/**
 * EEA-DAMS xliff.php
 *
 * The contents of this file are subject to the Mozilla Public
 * License Version 1.1 (the "License"); you may not use this file
 * except in compliance with the License. You may obtain a copy of
 * the License at http://www.mozilla.org/MPL/
 *
 * Software distributed under the License is distributed on an "AS
 * IS" basis, WITHOUT WARRANTY OF ANY KIND, either express or
 * implied. See the License for the specific language governing
 * rights and limitations under the License.
 *
 * The Original Code is "EEA-DAMS version 1.0".
 *
 * The Initial Owner of the Original Code is European Environment
 * Agency.  Portions created by Finsiel Romania are
 * Copyright (C) European Environment Agency.  All
 * Rights Reserved.
 *
 * Contributor(s):
 *  Original Code: Søren Roug, European Environment Agency
 *
 *
 * @abstract     xliff.php
 * @copyright    2008
 * @version      0.1
 *
 *
 */


require_once ('commons/config.php');


require_once 'DataObjects/Public_dams.php';
require_once 'DataObjects/Public_user_dams.php';

// Connecting, selecting database
$dbconn = pg_connect("host=localhost dbname=EEADAMS user=postgres")
   or die('Could not connect: ' . pg_last_error());

$extractlang = "fr";

// Performing SQL query
$query = "SELECT page_id, id, en as source, $extractlang as target FROM i18n ORDER by page_id, id";

$result = pg_query($query) or die('Query failed: ' . pg_last_error());
if (!pg_num_rows($result)) {
        echo '<h1>Query failed</h1>\n';
}
else {
        header('Content-Disposition: attachment; filename="i18n.xlf"');
        header('Content-type: text/xml');
        print '<?xml version="1.0" encoding="UTF-8"?>';
        print "<xliff version='1.2' xmlns='urn:oasis:names:tc:xliff:document:1.2'>";

        $result = pg_query($query) or die('Query failed: ' . pg_last_error());
        $currpage = "NoSuchPage";

        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            if ($currpage != $line['page_id']) {
                if ($currpage != "NoSuchPage")
                    print "</body>\n</file>";
                $currpage = $line['page_id'];
                print "<file original='{$line['page_id']}' source-language='en' target-language='$extractlang' datatype='plaintext'>";
                print "<body>";
            }
            print "<trans-unit id=\"{$line['id']}\">";
            print "<source>".htmlspecialchars($line['source'])."</source>";
            print "<target>".htmlspecialchars($line['target'])."</target>";
            print "</trans-unit>";
        }
        print "</body>";
        print "</file>";
        print "</xliff>";
}
// Free resultset
pg_free_result($result);
// Closing connection
pg_close($dbconn);
?> 
