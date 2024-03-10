<?php

function getLatestResearchData($offset = 0, $limit = 5, $selectedVolume = null, $searchQuery = '', $pubStat = 'all') {
    $DbHost = "localhost";
    $DbUsername = "root";
    $DbPassword = "";
    $DbName = "researchfiledb";

    $fileDb = mysqli_connect($DbHost, $DbUsername, $DbPassword, $DbName);

    if (!$fileDb) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $volumeCondition = ($selectedVolume !== null) ? "AND YEAR(Date) = $selectedVolume" : "";

    // Add conditions for search and publication status
    $searchCondition = ($searchQuery !== '') ? "AND (RTitle LIKE '%$searchQuery%' OR Authors LIKE '%$searchQuery%' OR Abstract LIKE '%$searchQuery%')" : "";
    $pubStatCondition = ($pubStat !== 'all') ? "AND Publish_status = '$pubStat'" : "";

    $query = "SELECT strands_list.Strand_name, RTitle, Authors, Date, Abstract, UID, Publish_status
              FROM research_info 
              JOIN strands_list ON research_info.Strand_ID = strands_list.Strand_ID 
              WHERE 1 $volumeCondition $searchCondition $pubStatCondition
              ORDER BY Date DESC LIMIT $offset, $limit";

    $result = mysqli_query($fileDb, $query);

    $researchData = array();

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

function getTotalResearchCount($selectedVolume = null, $searchQuery = '', $pubStat = 'all') {
    $DbHost = "localhost";
    $DbUsername = "root";
    $DbPassword = "";
    $DbName = "researchfiledb";

    $fileDb = mysqli_connect($DbHost, $DbUsername, $DbPassword, $DbName);

    if (!$fileDb) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $volumeCondition = ($selectedVolume !== null) ? "AND YEAR(Date) = $selectedVolume" : "";

    // Add conditions for search and publication status
    $searchCondition = ($searchQuery !== '') ? "AND (RTitle LIKE '%$searchQuery%' OR Authors LIKE '%$searchQuery%' OR Abstract LIKE '%$searchQuery%')" : "";
    $pubStatCondition = ($pubStat !== 'all') ? "AND Publish_status = '$pubStat'" : "";

    $query = "SELECT COUNT(*) AS total FROM research_info WHERE 1 $volumeCondition $searchCondition $pubStatCondition";

    $result = mysqli_query($fileDb, $query);

    $total = 0;

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $total = $row['total'];
    } else {
        echo "Error: " . mysqli_error($fileDb);
    }

    mysqli_close($fileDb);

    return $total;
}

function generateVolumeList() {
    $DbHost = "localhost";
    $DbUsername = "root";
    $DbPassword = "";
    $DbName = "researchfiledb";

    $fileDb = mysqli_connect($DbHost, $DbUsername, $DbPassword, $DbName);

    if (!$fileDb) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $volumeQuery = "SELECT DISTINCT YEAR(Date) as PublicationYear FROM research_info ORDER BY PublicationYear DESC";
    $volumeResult = mysqli_query($fileDb, $volumeQuery);

    echo '<h3>List of Volumes</h3>';
    echo '<ul class="Nav-List">';
    while ($volumeRow = mysqli_fetch_assoc($volumeResult)) {
        $volume = $volumeRow['PublicationYear'];
        echo '<li class="Nav-Item"><a href="?volume=' . $volume . '">Volume ' . ($volume - date("Y") + 2) . ' (' . $volume . ')</a></li>';
    }
    echo '</ul>';
    mysqli_close($fileDb);
}

// Call generateVolumeList function to display the list of volumes
generateVolumeList();

?>
