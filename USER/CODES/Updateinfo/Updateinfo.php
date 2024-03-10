<?php
function updateUserProfile($ID_number, $updatedValues) {
    $mysqli = new mysqli("localhost", "root", "", "login_db");

    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // Build the update query dynamically based on the changed fields
    $query = "UPDATE user_accounts SET ";
    $placeholders = '';
    $params = [];

    foreach ($updatedValues as $key => $value) {
        $query .= "$key = ?, ";
        $placeholders .= 's';
        $params[] = &$updatedValues[$key];
    }

    $query = rtrim($query, ', '); // Remove the trailing comma
    $query .= " WHERE ID_number = ?";

    $placeholders .= 's';
    $params[] = &$ID_number;

    // Bind parameters and execute the update query
    $stmt = $mysqli->prepare($query);

    if ($stmt === false) {
        die("Error preparing statement: " . $mysqli->error);
    }

    // Use call_user_func_array to bind parameters dynamically
    call_user_func_array([$stmt, 'bind_param'], array_merge([$placeholders], $params));

    $stmt->execute();

    $stmt->close();
    $mysqli->close();

    return true; // Return true to indicate successful update
}
?>
