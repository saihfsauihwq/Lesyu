<?php
function getAdminName($userId) {
    $mysqli = new mysqli("localhost", "root", "", "login_db");

    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    $query = "SELECT NAME FROM admin_accounts WHERE `USER-ID` = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        return $row["NAME"];
    }

    return null; 
}
?>
 