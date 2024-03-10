<?php
// Fetch the latest volume from the database
$DbHost = "localhost";
$DbUsername = "root";
$DbPassword = "";
$DbName = "researchfiledb";

$fileDb = mysqli_connect($DbHost, $DbUsername, $DbPassword, $DbName);

if (!$fileDb) {
    die("Connection failed: " . mysqli_connect_error());
}

$latestVolumeQuery = "SELECT MAX(YEAR(Date)) AS LatestVolume FROM research_info";
$latestVolumeResult = mysqli_query($fileDb, $latestVolumeQuery);

if ($latestVolumeResult) {
    $latestVolumeRow = mysqli_fetch_assoc($latestVolumeResult);
    $latestVolume = $latestVolumeRow['LatestVolume'];
    $selectedVolume = $latestVolume;
} else {
    // Handle the error if needed
    $selectedVolume = null;
}

mysqli_close($fileDb);
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
    <link rel="Stylesheet" href="General.css">
    <link rel="Stylesheet" href="Header.css">
    <link rel="Stylesheet" href="main.css">
    <link rel="Stylesheet" href="Searchbar.css">
    <link rel="Stylesheet" href="Page.css">
    <link rel="Icon" href="/WRRL/IMAGES/favicon.png">

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
            <?php 
                include("/xampp/htdocs/WRRL/FAC/Codes/Assets/profilebutton.php");
                ?>
        </div>
    </header>



 <main>

        <div class="Repository-name" >
            <p class="Head" >Liceo Senior High School <br>Research Repository</p>
            <p class="main">Liceo repository is the designated academic space for senior high school students at Liceo De Cagayan University,
                 offering an accessible platform to share and access academic works, supporting a comprehensive environment for
                  academic knowledge.</p>
        </div>


        <nav>
    <ul class="Navigator">
        <li class="nav-text">
            <a class="nav-text" href="/WRRL/FAC/Codes/HOME-PAGE/HOME-PAGE.php">Home</a>
        </li>
        <li class="nav-text" >
            <a class="nav-text" href="/WRRL/FAC/Codes/HOME-PAGE-STEM/HOME-PAGE-STEM.php" >Science, Technology, Engineering, and Mathematics</a>
        </li>
        <li class="nav-text" >
            <a class="nav-text" href="/WRRL/FAC/Codes/HOME-PAGE-ABM/HOME-PAGE-ABM.php" style=" Color: rgb(164,0,1);">Accountancy, Business and Management</a>
        </li>
        <li class="nav-text" >
            <a class="nav-text" href="/WRRL/FAC/Codes/HOME-PAGE-ICT/HOME-PAGE-ICT.php">Information and Communications Technology</a>
        </li>
        <li class="nav-text" >
            <a class="nav-text" href="/WRRL/FAC/Codes/HOME-PAGE-HE/HOME-PAGE-HE.php">Home Economics</a>
        </li>
        <li class="nav-text" >
            <a class="nav-text" href="/WRRL/FAC/Codes/HOME-PAGE-FAC/HOME-PAGE-FAC.php"> Senior High School Faculty</a>
        </li>
        <li class="nav-text">
            <a class="nav-text" href="/WRRL/FAC/Codes/Volume/volume.php?volume=<?php echo $selectedVolume; ?>">Volumes</a>
        </li>
    </ul>
</nav>
    
    <?php
    include("Display.php");

    $itemsPerPage = 5;
    $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
    $searchQuery = isset($_GET['query']) ? $_GET['query'] : '';
    $pubStat = isset($_GET['pubstat']) ? $_GET['pubstat'] : 'all';
    $startDate = isset($_GET['start_date']) ? $_GET['start_date'] : '';
    $endDate = isset($_GET['end_date']) ? $_GET['end_date'] : '';

    $totalItems = getTotalResearchCount($searchQuery, $pubStat, $startDate, $endDate);

    $totalPages = ceil($totalItems / $itemsPerPage);

    $currentPage = max(1, min($currentPage, $totalPages));
    $offset = ($currentPage - 1) * $itemsPerPage;

    $latestResearchData = getLatestResearchData($offset, $itemsPerPage, $searchQuery, $pubStat, $startDate, $endDate);
    ?>

    <h1 class="Latest-Research-Heading">Accountancy, Business and Management</h1>

        <form action="HOME-PAGE-ABM.php" method="get">
            <div class="Searchbar">
                <input class="Date" type="number" name="start_date" placeholder="Start Date" value="<?php echo isset($_GET['start_date']) ? htmlspecialchars($_GET['start_date']) : '2020'; ?>">
                <p>To</p>
                <input class="Date" type="number" name="end_date" placeholder="End Date" value="<?php echo isset($_GET['end_date']) ? htmlspecialchars($_GET['end_date']) : '2024'; ?>">
                <select class="select" name="pubstat">
                    <option value="all" <?php echo (isset($_GET['pubstat']) && $_GET['pubstat'] == 'all') ? 'selected' : ''; ?>>All</option>
                    <option value="Published" <?php echo (isset($_GET['pubstat']) && $_GET['pubstat'] == 'Published') ? 'selected' : ''; ?>>Published</option>
                    <option value="Not Published" <?php echo (isset($_GET['pubstat']) && $_GET['pubstat'] == 'Not Published') ? 'selected' : ''; ?>>Not Published</option>
                </select>
                <input class="type" type="text" name="query" value="<?php echo htmlspecialchars($searchQuery); ?>" placeholder="Search in ABM">
                <input class="search" type="submit" value="Search">
            </div>
        </form>

    <div class="Latest-Research-Container">
        <?php
        if (empty($latestResearchData)) {
            echo '<p>No research found.</p>';
        } else {
            foreach ($latestResearchData as $research) {
                echo '<div class="Research-Info" data-strand="' . $research['Strand_name'] . '">';
                echo '<a class="Research-Paper-title" href="/WRRL/FAC/Codes/ABSTRACT-PAGE/ABSTRACT-PAGE.php?uid=' . $research['UID'] . '">' . $research['RTitle'] . '</a>';
                echo '<p class="Date-and-Author">' . $research['Date'] . ' - ' . $research['Authors'] . '</p>';
                echo '<p class="Abstract">' . $research['Abstract'] . '</p>';
                echo '<p class="pubstat">' . $research['Publish_status'] . '</p>';
                echo '</div>';
            }
        }
        ?>

<div class="page_con">
            <div class="pagination" id="pagination">
                <?php
                $queryParameters = $_GET;
                unset($queryParameters['page']); // Remove the 'page' parameter from the query
                $queryString = http_build_query($queryParameters);

                echo '<a href="?page=' . max($currentPage - 1, 1) . '&' . $queryString . '">Previous</a>';

                $maxPagesToShow = 5; // Change this to set the maximum number of pages to display

                if ($totalPages <= $maxPagesToShow) {
                    // If total pages are less than or equal to the maximum, display all pages
                    for ($i = 1; $i <= $totalPages; $i++) {
                        echo '<a href="?page=' . $i . '&' . $queryString . '" ' . ($currentPage == $i ? 'class="active"' : '') . '>' . $i . '</a>';
                    }
                } else {
                    // If total pages exceed the maximum, display ellipsis
                    $halfMaxPagesToShow = floor($maxPagesToShow / 2);

                    // Display pages around the current page
                    if ($currentPage - $halfMaxPagesToShow > 1) {
                        echo '<a href="?page=1&' . $queryString . '">1</a>';
                        echo '<a class="ellipsis">...</a>';
                    }

                    for ($i = max(1, $currentPage - $halfMaxPagesToShow); $i <= min($totalPages, $currentPage + $halfMaxPagesToShow); $i++) {
                        echo '<a href="?page=' . $i . '&' . $queryString . '" ' . ($currentPage == $i ? 'class="active"' : '') . '>' . $i . '</a>';
                    }

                    // Display ellipsis for omitted pages after the current page
                    if ($currentPage + $halfMaxPagesToShow < $totalPages) {
                        echo '<a class="ellipsis">...</a>';
                        echo '<a href="?page=' . $totalPages . '&' . $queryString . '">' . $totalPages . '</a>';
                    }
                }

                echo '<a href="?page=' . min($currentPage + 1, $totalPages) . '&' . $queryString . '">Next</a>';
                ?>
            </div>
        </div>

 </main>
</body>
</html>