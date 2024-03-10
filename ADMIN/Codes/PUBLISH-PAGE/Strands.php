<?php

function getStrands() {
    $strandDbHost = "localhost";
    $strandDbUsername = "root";
    $strandDbPassword = "";
    $strandDbName = "researchfiledb";

    $strandDb = mysqli_connect($strandDbHost, $strandDbUsername, $strandDbPassword, $strandDbName);

    if (!$strandDb) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $strands = array();

    $query = "SELECT Strand_ID, Strand_name FROM strands_list";
    $result = mysqli_query($strandDb, $query);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $strands[] = $row;
        }
    }

    mysqli_close($strandDb);

    return $strands;
}

?>
