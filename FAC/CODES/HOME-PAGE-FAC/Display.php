<?php
function getLatestResearchData($offset = 0, $limit = 5, $searchQuery = "",  $pubStat = "all",  $start_date = "", $end_date = "") {
    $DbHost = "localhost";
    $DbUsername = "root";
    $DbPassword = "";
    $DbName = "researchfiledb";

    $fileDb = mysqli_connect($DbHost, $DbUsername, $DbPassword, $DbName);

    if (!$fileDb) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $researchData = array();
    
    $dateRangeFilter = "";
    if (!empty($start_date) && !empty($end_date)) {
        $dateRangeFilter = " AND Date BETWEEN '$start_date-01-01' AND '$end_date-12-31'";
    }

    $pubStatFilter = "";
    if ($pubStat === "Published" || $pubStat === "Not Published") {
        $pubStatFilter = " AND Publish_status = '$pubStat'";
    }

    $searchQueryFilter = ($searchQuery != "") ? " AND (RTitle LIKE '%$searchQuery%' OR Authors LIKE '%$searchQuery%' OR Abstract LIKE '%$searchQuery%' OR Description LIKE '%$searchQuery%')" : "";

    $query = "SELECT strands_list.Strand_name, RTitle, Authors, Date, Abstract, UID, Publish_status
              FROM research_info 
              JOIN strands_list ON research_info.Strand_ID = strands_list.Strand_ID 
              WHERE strands_list.Strand_name = 'FACULTY'"
              . $searchQueryFilter . $pubStatFilter . $dateRangeFilter . "
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

function getTotalResearchCount($searchQuery = "", $pubStat = "all", $start_date = "", $end_date = "") {
    $DbHost = "localhost";
    $DbUsername = "root";
    $DbPassword = "";
    $DbName = "researchfiledb";

    $fileDb = mysqli_connect($DbHost, $DbUsername, $DbPassword, $DbName);

    if (!$fileDb) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $dateRangeFilter = "";
    if (!empty($start_date) && !empty($end_date)) {
        $dateRangeFilter = " AND Date BETWEEN '$start_date-01-01' AND '$end_date-12-31'";
    }

    $pubStatFilter = "";
    if ($pubStat === "Published" || $pubStat === "Not Published") {
        $pubStatFilter = " AND Publish_status = '$pubStat'";
    }

    $searchQueryFilter = ($searchQuery != "") ? " AND (RTitle LIKE '%$searchQuery%' OR Authors LIKE '%$searchQuery%' OR Abstract LIKE '%$searchQuery%' OR Description LIKE '%$searchQuery%')" : "";

    $query = "SELECT COUNT(*) AS total FROM research_info 
              JOIN strands_list ON research_info.Strand_ID = strands_list.Strand_ID 
              WHERE strands_list.Strand_name = 'FACULTY'"
              . $searchQueryFilter . $pubStatFilter . $dateRangeFilter;

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
