<?php
  require_once 'config.inc.php';
  //Connect to the database server
  $db = @mysql_connect(DB_HOST, DB_USER, DB_PASSWD);
  if (!db) {
    echo("<p>Cant connect to MySQL.</p>");
    exit();
  } else {
    if ($pos_mes) {
      echo("<p>Successfully connected to MySQL.</p>");
    }
  }

  //Select the database
  if (!@mysql_select_db(DB_DATABASE)) {
    echo("<p>Cant connect to the database.</p>");
    exit();
  } else {
    if ($pos_mes) {
      echo("<p>Successfully connected to the database.</p>");
    }
  }
?>
