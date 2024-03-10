<?php

function getLatestResearchData($offset = 0, $limit = 5) {
  $DbHost = "localhost";
  $DbUsername = "root";
  $DbPassword = "";
  $DbName = "researchfiledb";

  $fileDb = mysqli_connect($DbHost, $DbUsername, $DbPassword, $DbName);

  if (!$fileDb) {
      die("Connection failed: " . mysqli_connect_error());
  }

  $researchData = array();

  $query = "SELECT strands_list.Strand_name, RTitle, Authors, Date, Abstract, UID, Publish_status
            FROM research_info 
            JOIN strands_list ON research_info.Strand_ID = strands_list.Strand_ID 
            ORDER BY Date DESC LIMIT $offset, $limit";

  $result = mysqli_query($fileDb, $query);

  if ($result) {
      while ($row = mysqli_fetch_assoc($result)) {
          $researchData[] = $row;
      }
  } else {
      echo "Error: " . mysqli_error($fileDb);
  }

  mysqli_close($fileDb);

  return $researchData;
}

function getTotalResearchCount() {
    $DbHost = "localhost";
    $DbUsername = "root";
    $DbPassword = "";
    $DbName = "researchfiledb";

    $fileDb = mysqli_connect($DbHost, $DbUsername, $DbPassword, $DbName);

    if (!$fileDb) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $query = "SELECT COUNT(*) AS total FROM research_info";

    $result = mysqli_query($fileDb, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $total = $row['total'];
    } else {
        echo "Error: " . mysqli_error($fileDb);
        $total = 0;
    }

    mysqli_close($fileDb);

    return $total;
}
?>
