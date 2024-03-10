<?php
function getUserInfo($ID_number) {
    $mysqli = new mysqli("localhost", "root", "", "login_db");

    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    $query = "SELECT * FROM user_accounts WHERE ID_number = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $ID_number);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $stmt->close();
        $mysqli->close();
        return $row;
    } else {
        $stmt->close();
        $mysqli->close();
        return false;
    }
}
?>
