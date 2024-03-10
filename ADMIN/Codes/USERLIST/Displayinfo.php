<?php
function getUserData() {
  $mysqli = new mysqli("localhost", "root", "", "login_db");

  if ($mysqli->connect_error) {
      die("Connection failed: " . $mysqli->connect_error);
  }

  $query = "SELECT * FROM user_accounts";
  $result = mysqli_query($mysqli, $query);
  
  $userData = array();

  while ($row = mysqli_fetch_assoc($result)) {
      $userData[] = $row;
  }

  return $userData;
}

function filterUserData($searchTerm) {
  $mysqli = new mysqli("localhost", "root", "", "login_db");

  if ($mysqli->connect_error) {
      die("Connection failed: " . $mysqli->connect_error);
  }

  // Use a prepared statement to prevent SQL injection
  $query = "SELECT * FROM user_accounts WHERE ID_number LIKE ?";
  $stmt = $mysqli->prepare($query);

  if (!$stmt) {
      die("Error in preparing statement: " . $mysqli->error);
  }

  // Bind the search term to the prepared statement
  $searchTerm = "%" . $searchTerm . "%";
  $stmt->bind_param("s", $searchTerm);

  // Execute the statement
  $stmt->execute();

  // Get the result
  $result = $stmt->get_result();

  // Fetch the filtered user data
  $userData = array();
  while ($row = $result->fetch_assoc()) {
      $userData[] = $row;
  }

  // Close the statement and database connection
  $stmt->close();
  $mysqli->close();

  return $userData;
}
?>