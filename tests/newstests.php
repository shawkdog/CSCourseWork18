<html>
<?php
$root = $_SERVER['DOCUMENT_ROOT'];
$title = "Testing";
include($root.'/includes/head.php');
include($root.'/includes/connect.php');
?>
  <head>
    <title>Statment Test</title>
  </head>

  <body>
    <header>PHP Output:</header>
    <?php

    $id = 7;
    $Query = sqlsrv_query($conn,"SELECT * FROM news WHERE id = $id");

    $row = sqlsrv_fetch_array($Query);

    while ($row = sqlsrv_fetch_array($Query))
    {
      $title = $row['title'];
      $body = $row['body'];
      $image = $row['image'];

      print "It be getting the far";
      print $id;
      print $title;
      print $body;
      print $image;
    }
    ?>

  </body>
</html>
