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

    $sql = "UPDATE fac_accounts SET password = '$hashedPassword' WHERE ID_number = $ID_number";

    if ($fileDb->query($sql) === TRUE) {
        // Password updated successfully
    } else {
        $errorMessage = "Error updating password: " . $fileDb->error;
        $_SESSION['errorMessage'] = $errorMessage; // Store the error message in the session
    }

    $fileDb->close();
}
?>
