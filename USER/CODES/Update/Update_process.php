<?php

function updatePassword($ID_number, $newPassword) {
    $DbHost = "localhost";
    $DbUsername = "root";
    $DbPassword = "";
    $DbName = "login_db";
    
    $fileDb = mysqli_connect($DbHost, $DbUsername, $DbPassword, $DbName);
    
    if (!$fileDb) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    $sql = "UPDATE user_accounts SET password = '$hashedPassword' WHERE ID_number = $ID_number";

    if ($fileDb->query($sql) === TRUE) {
        
    } else {
        echo "Error updating password: " . $fileDb->error;
    }

    $fileDb->close();
}
?>