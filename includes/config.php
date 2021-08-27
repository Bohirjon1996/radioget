<?php

    //database configuration
    $host       = "sql210.ezyro.com";
    $user       = "ezyro_29491373";
    $pass       = "d2269n4jybdgyvq";
    $database   = "ezyro_29491373_1";

    $connect = new mysqli($host, $user, $pass, $database);

    if (!$connect) {
        die ("connection failed: " . mysqli_connect_error());
    } else {
        $connect->set_charset('utf8');
    }

    $ENABLE_RTL_MODE = 'false';

?>