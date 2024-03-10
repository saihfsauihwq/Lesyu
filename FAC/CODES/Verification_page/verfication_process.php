<?php

include '/xampp/htdocs/WRRL/FAC/CODES/REG-PAGE/Registration_process.php';

function verifyCode($email, $verification_code) {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "login_db";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $email = mysqli_real_escape_string($conn, trim($email));
    $verification_code = mysqli_real_escape_string($conn, $verification_code);

    $sql = "SELECT * FROM fac_accounts WHERE Liceo_Email = '$email' AND Verification_code = '$verification_code'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Verification successful, update user status or perform other actions as needed
        $sql = "UPDATE fac_accounts SET Verified = 1 WHERE Liceo_Email = '$email'";
        $conn->query($sql);

        // You may want to redirect the user to a success page or perform additional actions here
        return true;
    } else {
        return "Invalid verification code.";
    }

    $conn->close();
    return false;
}

// Add any additional functions or logic related to verification here

?>
