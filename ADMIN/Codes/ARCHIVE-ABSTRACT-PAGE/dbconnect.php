<?php

    $DbHost = "localhost";
    $DbUsername = "root";
    $DbPassword = "";
    $DbName = "archivedb";
    
    $fileDb = mysqli_connect($DbHost, $DbUsername, $DbPassword, $DbName);
    
    if (!$fileDb) {
        die("Connection failed: " . mysqli_connect_error());
    }

?>
