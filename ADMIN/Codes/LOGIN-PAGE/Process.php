<?php
session_start();

$mysqli = new mysqli("localhost", "root", "", "login_db");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
} 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST["user_id"];
    $password = $_POST["password"];

    $query = "SELECT * FROM admin_accounts WHERE `USER-ID` = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $stored_hashed_password = $row["PASSWORD"];

        
        if (password_verify($password, $stored_hashed_password)) {
            
            $_SESSION["admin_accounts"] = ["USER-ID" => $user_id];
            header("Location: http://localhost/WRRL/ADMIN/Codes/HOME-PAGE/HOME-PAGE.php");
            exit();
        } else {
        
            $_SESSION["error_message"] = "Invalid user ID or password, Try again.";
            header("Location: Login-Page.php");
        }
    } else {
    
        $_SESSION["error_message"] = "Invalid user ID or password, Try again.";
        header("Location: Login-Page.php");
    }
}

?>

