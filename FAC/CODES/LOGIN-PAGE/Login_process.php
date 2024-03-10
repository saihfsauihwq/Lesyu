<?php

session_start();

$mysqli = new mysqli("localhost", "root", "", "login_db");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
} 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login_input = $_POST["ID-number"]; 
    $password = $_POST["password"];

    if (filter_var($login_input, FILTER_VALIDATE_EMAIL)) {
        $query = "SELECT * FROM fac_accounts WHERE `Liceo_Email` = ?";
    } else {
        $query = "SELECT * FROM fac_accounts WHERE `ID_number` = ?";
    }

    // You had an extra "else" block here, which is not needed. 
    // Also, you should use OR condition in the query to check the Backup_email.
    $query .= " OR `Backup_email` = ?";
    
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ss", strtolower($login_input), strtolower($login_input));
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        
        // Check if the account is active
        if ($row["Account_status"] == "Inactive") {
            $_SESSION["error_message"] = "Your account has been blocked. Please contact the admin.";
            header("Location: Login-Page.php");
            exit();
        }

        $stored_hashed_password = $row["Password"];

        if (password_verify($password, $stored_hashed_password)) {
            $_SESSION["fac_accounts"] = ["ID_number" => $row["ID_number"], "Liceo_Email" => $row["Liceo_Email"]];
            header("Location: http://localhost/WRRL/FAC/Codes/HOME-PAGE/HOME-PAGE.php");
            exit();
        } else {
            $_SESSION["error_message"] = "Invalid user ID or password. Try again.";
            header("Location: Login-Page.php");
        }
    } else {
        $_SESSION["error_message"] = "Invalid user ID or password. Try again.";
        header("Location: Login-Page.php");
    }
}

?>
