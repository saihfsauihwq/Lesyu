<?php
function getUserDataById($userId) {
  $mysqli = new mysqli("localhost", "root", "", "login_db");

  if ($mysqli->connect_error) {
      die("Connection failed: " . $mysqli->connect_error);
  }

  $query = "SELECT * FROM fac_accounts WHERE ID_number = ?";
  $stmt = $mysqli->prepare($query);

  if (!$stmt) {
      die("Error in preparing statement: " . $mysqli->error);
  }

  $stmt->bind_param("s", $userId);
  $stmt->execute();

  $result = $stmt->get_result();
  $userInfo = $result->fetch_assoc();

  $stmt->close();
  $mysqli->close();

  return $userInfo;
}
?>
