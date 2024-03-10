<?php

function performSearch($searchQuery, $filters) {
    $DbHost = "localhost";
    $DbUsername = "root";
    $DbPassword = "";
    $DbName = "researchfiledb";

    $fileDb = mysqli_connect($DbHost, $DbUsername, $DbPassword, $DbName);

    if (!$fileDb) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $results = array();

    $escapedQuery = mysqli_real_escape_string($fileDb, $searchQuery);

    $sql = "SELECT research_info.*, strands_list.Strand_name
    FROM research_info
    JOIN strands_list ON research_info.Strand_ID = strands_list.Strand_ID
    WHERE 1";

    if (!empty($escapedQuery)) {
    $sql .= " AND (research_info.RTitle LIKE '%$escapedQuery%' OR research_info.Authors LIKE '%$escapedQuery%' OR research_info.Abstract LIKE '%$escapedQuery%' OR research_info.Description LIKE '%$escapedQuery%')";
    }

    // Handle search filters

    if (isset($filters['startYear']) && isset($filters['endYear'])) {
        $startYearFilter = (int)$filters['startYear'];
        $endYearFilter = (int)$filters['endYear'];
        
        if ($startYearFilter > 0 && $endYearFilter > 0) {
            $sql .= " AND research_info.Date BETWEEN '$startYearFilter-01-01' AND '$endYearFilter-12-31'";
        }
    }
    
    if (isset($filters['author'])) {
    $authorFilter = mysqli_real_escape_string($fileDb, $filters['author']);
    $sql .= " AND research_info.Authors LIKE '%$authorFilter%'";
    }

    if (isset($filters['strand']) && $filters['strand'] != 'Select strand') {
    $strandFilter = mysqli_real_escape_string($fileDb, $filters['strand']);
    $sql .= " AND research_info.Strand_ID = '$strandFilter'";
    }

    $queryResult = mysqli_query($fileDb, $sql);

    if ($queryResult) {
        while ($row = mysqli_fetch_assoc($queryResult)) {
            $results[] = $row;
        }
        mysqli_free_result($queryResult);
    } else {
        echo "Error executing query: " . mysqli_error($fileDb);
        echo "<br>Generated SQL Query: " . $sql;
    }

    mysqli_close($fileDb);

    return $results;
}

?>