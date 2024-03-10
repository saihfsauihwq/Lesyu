<?php
function getLatestResearchData($offset = 0, $limit = 5, $searchQuery = "", $strandFilter,  $start_date = "", $end_date = "") {
    $DbHost = "localhost";
    $DbUsername = "root";
    $DbPassword = "";
    $DbName = "archivedb";

    $fileDb = mysqli_connect($DbHost, $DbUsername, $DbPassword, $DbName);

    if (!$fileDb) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $researchData = array();
    
    $dateRangeFilter = "";
    if (!empty($start_date) && !empty($end_date)) {
        $dateRangeFilter = " AND Date BETWEEN '$start_date-01-01' AND '$end_date-12-31'";
    }

    $strandFilter = "";
    if (!empty($_GET['strand']) && $_GET['strand'] != 'ALL') {
        $strandFilter = " AND archive_research_info.Strand_ID = " . (int)$_GET['strand'];
    }
    
    $searchQueryFilter = ($searchQuery != "") ? " AND (RTitle LIKE '%$searchQuery%' OR Authors LIKE '%$searchQuery%' OR Abstract LIKE '%$searchQuery%' OR Description LIKE '%$searchQuery%' OR Deleter LIKE '%$searchQuery%')" : "";

    $query = "SELECT strands_list.Strand_name, RTitle, Authors, Date, Abstract, UID, Publish_status, Deleter
              FROM archive_research_info 
              JOIN strands_list ON archive_research_info.Strand_ID = strands_list.Strand_ID 
              WHERE archive_research_info.Publish_status = 'Published'"
              . $searchQueryFilter . $dateRangeFilter . $strandFilter . "
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

function getTotalResearchCount($searchQuery = "", $strandFilter, $start_date = "", $end_date = "") {
    $DbHost = "localhost";
    $DbUsername = "root";
    $DbPassword = "";
    $DbName = "archivedb";

    $fileDb = mysqli_connect($DbHost, $DbUsername, $DbPassword, $DbName);

    if (!$fileDb) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $dateRangeFilter = "";
    if (!empty($start_date) && !empty($end_date)) {
        $dateRangeFilter = " AND Date BETWEEN '$start_date' AND '$end_date'";
    }

    $strandFilter = "";
    if (!empty($_GET['strand']) && $_GET['strand'] != 'ALL') {
        $strandFilter = " AND archive_research_info.Strand_ID = " . (int)$_GET['strand'];
    }

    $searchQueryFilter = ($searchQuery != "") ? " AND (RTitle LIKE '%$searchQuery%' OR Authors LIKE '%$searchQuery%' OR Abstract LIKE '%$searchQuery%' OR Description LIKE '%$searchQuery%' OR Deleter LIKE '%$searchQuery%')" : "";

    $query = "SELECT COUNT(*) AS total FROM archive_research_info 
              JOIN strands_list ON archive_research_info.Strand_ID = strands_list.Strand_ID 
              WHERE archive_research_info.Publish_status = 'Published'"
              . $searchQueryFilter . $strandFilter . $dateRangeFilter;

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
