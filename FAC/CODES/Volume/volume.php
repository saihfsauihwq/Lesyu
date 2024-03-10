<?php
$searchQuery = isset($_GET['query']) ? $_GET['query'] : '';
$pubStat = isset($_GET['pubstat']) ? $_GET['pubstat'] : 'all';
$selectedVolume = isset($_GET['volume']) ? $_GET['volume'] : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="Author" content="Jhon llyod Navarro">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Research Papers repository For Liceo">
    <meta name="keywords" content="Research, Repository, Liceo, University, Admin interface">
    <title>Web-Based Research Repository</title>
    <link rel="stylesheet" href="General.css">
    <link rel="stylesheet" href="Header.css">
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="Searchbar.css">
    <link rel="stylesheet" href="Page.css" >
    <link rel="stylesheet" href="nav.css">
    <link rel="icon" href="/WRRL/IMAGES/favicon.png">
</head>
<body>

    <header>
        <div class="Header-background">
            <div class="Logo">
                <a href="/WRRL/FAC/Codes/HOME-PAGE/HOME-PAGE.php">
                    <img class="Logo-icon" src="/WRRL/IMAGES/mainlogo.png">
                </a>
            </div>
            <div class="Title">
                <p class="Name">Liceo U Repository</p>
                <p class="line">Committed to Total Human Formation!</p>
            </div>
    </header>

<nav>
    <div class="Nav-Container">
        <?php
        include("Display.php");

        $itemsPerPage = 5;
        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
        $selectedVolume = isset($_GET['volume']) ? $_GET['volume'] : null;


        $totalItems = getTotalResearchCount($selectedVolume, $searchQuery, $pubStat);

        $totalPages = ceil($totalItems / $itemsPerPage);

        $currentPage = max(1, min($currentPage, $totalPages));

        $offset = ($currentPage - 1) * $itemsPerPage;

        $latestResearchData = getLatestResearchData($offset, $itemsPerPage, $selectedVolume, $searchQuery, $pubStat);
        ?>
    </div>
</nav>

<main>
    <div class="Latest-Research-Container">
    <?php
    function getVolumeYear($volume) {
        // Assuming volumes represent future years, e.g., Volume 1 corresponds to the current year
        $currentYear = date("Y");
        $selectedYear = $volume - $currentYear + 2;

        return $selectedYear;
        }
        echo '<h3>';
        if ($selectedVolume !== null) {
            $volumeYear = getVolumeYear($selectedVolume);
            echo 'Volume ' . $volumeYear . ' (' . $selectedVolume . ')';
        } else {
            echo 'Volumes';
        }
        echo '</h3>';
        ?>

        <form action="volume.php" method="get">
            <div class="Searchbar">
                <select class="select" name="pubstat">
                    <option value="all" <?php echo (isset($_GET['pubstat']) && $_GET['pubstat'] == 'all') ? 'selected' : ''; ?>>All</option>
                    <option value="Published" <?php echo (isset($_GET['pubstat']) && $_GET['pubstat'] == 'Published') ? 'selected' : ''; ?>>Published</option>
                    <option value="Not Published" <?php echo (isset($_GET['pubstat']) && $_GET['pubstat'] == 'Not Published') ? 'selected' : ''; ?>>Not Published</option>
                </select>
                <input type="hidden" name="volume" value="<?php echo $selectedVolume; ?>">
                <input class="type" type="text" name="query" value="<?php echo htmlspecialchars($searchQuery); ?>" placeholder="Search">
                <input class="search" type="submit" value="Search">
            </div>
        </form>

        <?php
        foreach ($latestResearchData as $research) {
            echo '<div class="Research-Info" data-strand="' . $research['Strand_name'] . '">';
            echo '<button class="Strand">' . $research['Strand_name'] . '</button><br>';
            echo '<a class="Research-Paper-title" href="/WRRL/FAC/Codes/ABSTRACT-PAGE/ABSTRACT-PAGE.php?uid=' . $research['UID'] . '">' . $research['RTitle'] . '</a>';
            echo '<p class="Date-and-Author">' . $research['Date'] . ' - ' . $research['Authors'] . '</p>';
            echo '<p class="Abstract">' . $research['Abstract'] . '</p>';
            echo '<p class="pubstat">' . $research['Publish_status'] . '</p>';
            echo '</div>';
        }
        if (empty($latestResearchData)) {
            echo '<p class="empty">No research found.</p>';
        }        
        ?>

        <div class="page_con">
            <div class="pagination" id="pagination">
                <?php
                echo '<a href="?volume=' . $selectedVolume . '&page=' . ($currentPage - 1) . '&pubstat=' . $pubStat . '&query=' . urlencode($searchQuery) . '">Previous</a>';

                $maxPagesToShow = 5; // Change this to set the maximum number of pages to display

                if ($totalPages <= $maxPagesToShow) {
                    // If total pages are less than or equal to the maximum, display all pages
                    for ($i = 1; $i <= $totalPages; $i++) {
                        echo '<a href="?volume=' . $selectedVolume . '&page=' . $i . '&pubstat=' . $pubStat . '&query=' . urlencode($searchQuery) . '" ' . ($currentPage == $i ? 'class="active"' : '') . '>' . $i . '</a>';
                    }
                } else {
                    // If total pages exceed the maximum, display ellipsis
                    $halfMaxPagesToShow = floor($maxPagesToShow / 2);

                    // Display pages around the current page
                    if ($currentPage - $halfMaxPagesToShow > 1) {
                        echo '<a href="?volume=' . $selectedVolume . '&page=1&pubstat=' . $pubStat . '&query=' . urlencode($searchQuery) . '">1</a>';
                        echo '<a class="ellipsis">...</a>';
                    }

                    for ($i = max(1, $currentPage - $halfMaxPagesToShow); $i <= min($totalPages, $currentPage + $halfMaxPagesToShow); $i++) {
                        echo '<a href="?volume=' . $selectedVolume . '&page=' . $i . '&pubstat=' . $pubStat . '&query=' . urlencode($searchQuery) . '" ' . ($currentPage == $i ? 'class="active"' : '') . '>' . $i . '</a>';
                    }

                    // Display ellipsis for omitted pages after the current page
                    if ($currentPage + $halfMaxPagesToShow < $totalPages) {
                        echo '<a class="ellipsis">...</a>';
                        echo '<a href="?volume=' . $selectedVolume . '&page=' . $totalPages . '&pubstat=' . $pubStat . '&query=' . urlencode($searchQuery) . '">' . $totalPages . '</a>';
                    }
                }

                echo '<a href="?volume=' . $selectedVolume . '&page=' . ($currentPage + 1) . '&pubstat=' . $pubStat . '&query=' . urlencode($searchQuery) . '">Next</a>';
                ?>
            </div>

        </div>

    </div>
</main>
</body>
</html>
