<?php

function getStrands() {
    $strandDbHost = "localhost";
    $strandDbUsername = "root";
    $strandDbPassword = "";
    $strandDbName = "archivedb";

    $strandDb = mysqli_connect($strandDbHost, $strandDbUsername, $strandDbPassword, $strandDbName);

    if (!$strandDb) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $strands = array();

    $query = "SELECT Strand_ID, Strand_name FROM strands_list";
    $result = mysqli_query($strandDb, $query);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Check if the expected keys exist in the row
            if (isset($row['Strand_ID']) && isset($row['Strand_name'])) {
                $strands[] = $row;
            } else {
                // Handle the case where expected keys are missing
                // This could be logged or handled in a way that suits your application
                echo "Warning: Unexpected data structure in strands_list table.";
            }
        }
    }

    mysqli_close($strandDb);

    return $strands;
}

?>
