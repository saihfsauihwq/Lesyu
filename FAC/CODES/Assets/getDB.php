<?php
function getUserInfo($ID_number) {
    $mysqli = new mysqli("localhost", "root", "", "login_db");

    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    $query = "SELECT ID_number, Firstname, Profile FROM fac_accounts WHERE `ID_number` = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $ID_number);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        return $row;
    }

    return null; 
}
?>
