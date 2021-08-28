<?php

    //database configuration
    $host       = "sql4.freemysqlhosting.net";
    $user       = "sql4433270";
    $pass       = "NHLHjTtbh4";
    $database   = "sql4433270";

    $connect = new mysqli($host, $user, $pass, $database);

    if (!$connect) {
        die ("connection failed: " . mysqli_connect_error());
    } else {
        $connect->set_charset('utf8');
    }

    $ENABLE_RTL_MODE = 'false';

?>
