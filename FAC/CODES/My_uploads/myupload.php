<?php
session_start();

include("Displayinfo.php");

if (!isset($_SESSION["fac_accounts"])) {
    header("Location: http://localhost/WRRL/FAC/Codes/LOGIN-PAGE/LOGIN-PAGE.php");
    exit();
}

$ID_number = $_SESSION["fac_accounts"]["ID_number"];
$userInfo = getUserInfo($ID_number);

$uploaderID = $userInfo["ID_number"];

if ($userInfo) {
    $userprofileBlob = $userInfo["Profile"];
}


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
    <link rel="Stylesheet" href="Page.css">
    <link rel="Stylesheet" href="nav.css">
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
    </header>

<nav>
  <div class="Nav-Section">
    <div class="user-ID">
      <p class="label"><?php echo $userInfo['Firstname']; ?><p>
      <p class="IDnum"><?php echo $userInfo['ID_number']; ?><p>
    </div>
    <ul>
      <li><a class="nav_bar" href="http://localhost/WRRL/FAC/Codes/Profile/Profile.php">Profile</a></li>
      <li style="background-color: rgba(148, 147, 144, 0.153);" ><a class="nav_bar" href="http://localhost/WRRL/FAC/Codes/My_uploads/myupload.php">My uploads</a></li>
      <li><a class="nav_bar" href="http://localhost/WRRL/FAC/Codes/Publish/Publish.php">Upload new paper</a></li>
      <li><a class="nav_bar" href="http://localhost/WRRL/FAC/Codes/Update/Update.php">Update Account</a></li>
      <li><a class="nav_bar" href="http://localhost/WRRL/FAC/Codes/LOGIN-PAGE/LOGIN-PAGE.php">Logout</a></li>
    </ul>
  </div>
</nav>

 <main>
  <div class="Research-con">
    <div>
    <?php
            include("Displayuploads.php");

            $itemsPerPage = 5;
            $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
            $searchQuery = isset($_GET['query']) ? $_GET['query'] : '';
            $pubStat = isset($_GET['pubstat']) ? $_GET['pubstat'] : 'all';
            $startDate = isset($_GET['start_date']) ? $_GET['start_date'] : '';
            $endDate = isset($_GET['end_date']) ? $_GET['end_date'] : '';


            $totalItems = getTotalResearchCount($uploaderID, $searchQuery, $pubStat, $startDate, $endDate); 


            $totalPages = ceil($totalItems / $itemsPerPage);


            $currentPage = max(1, min($currentPage, $totalPages));

            $offset = ($currentPage - 1) * $itemsPerPage;

            $latestResearchData = getLatestResearchData($uploaderID, $offset, $itemsPerPage, $searchQuery, $pubStat, $startDate, $endDate);

        ?>

        <h1 class="Latest-Research-Heading">My Uploads</h1>

        <form action="myupload.php?=" method="get">
            <div class="Searchbar">
                <input class="Date" type="number" name="start_date" placeholder="Start Date" value="<?php echo isset($_GET['start_date']) ? htmlspecialchars($_GET['start_date']) : '2020'; ?>">
                <p>To</p>
                <input class="Date" type="number" name="end_date" placeholder="End Date" value="<?php echo isset($_GET['end_date']) ? htmlspecialchars($_GET['end_date']) : '2024'; ?>">
                <select class="select" name="pubstat">
                    <option value="all" <?php echo (isset($_GET['pubstat']) && $_GET['pubstat'] == 'all') ? 'selected' : ''; ?>>All</option>
                    <option value="Published" <?php echo (isset($_GET['pubstat']) && $_GET['pubstat'] == 'Published') ? 'selected' : ''; ?>>Published</option>
                    <option value="Not Published" <?php echo (isset($_GET['pubstat']) && $_GET['pubstat'] == 'Not Published') ? 'selected' : ''; ?>>Not Published</option>
                </select>

                <input class="type" type="text" name="query" value="<?php echo htmlspecialchars($searchQuery); ?>" placeholder="Search in Uploads">
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
                echo '<button class="Strand">' . $research['Strand_name'] . '</button><br>';
                echo '<a class="Research-Paper-title" href="/WRRL/FAC/Codes/MY-ABSTRACT-PAGE/ABSTRACT-PAGE.php?uid=' . $research['UID'] . '">' . $research['RTitle'] . '</a>';
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

            
        </div>
 </main>

</body>
</html>
