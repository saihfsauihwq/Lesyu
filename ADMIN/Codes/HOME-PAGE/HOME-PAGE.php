<?php
session_start();
include("getDB.php");


if (!isset($_SESSION["admin_accounts"])) {
  header("Location: Login-Page.php");
  exit();
}
$userId = $_SESSION["admin_accounts"]["USER-ID"];
$adminName = getAdminName($userId);


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
    <link rel="Stylesheet" href="nav.css">
    <link rel="Stylesheet" href="Page.css">
    <link rel="Icon" href="/WRRL/IMAGES/favicon.png">
</head>
<body>

<header>
    <div class="Header-background">
        <div class="Logo">
            <a href="/WRRL/ADMIN/Codes/HOME-PAGE/HOME-PAGE.php">
                <img class="Logo-icon" src="/WRRL/IMAGES/mainlogo.png">
            </a>
        </div>
        <div class="Title">
            <p class="Name">Liceo U Repository</p>
            <p class="line">Committed to Total Human Formation!</p>
        </div>
</header>

<nav>
    <div class="Navigation-bar-container">
        <div class="profile">
            <div class="Profile-picture">
                <img class="Ppic" src="Images/gojo.jpg">
                <a class="Username"> <?php echo $adminName; ?> </a>
            </div>
            <div class="nav-buttons">
                <div><img src="/WRRL/IMAGES/house-window.png"><a style="color: rgb(164,0,1); text-decoration: underline;" class="home" href="http://localhost/WRRL/ADMIN/Codes/HOME-PAGE/HOME-PAGE.php">Home</a></div>
                <div><img src="/WRRL/IMAGES/books.png" ><a class="publish" href="/WRRL/ADMIN/Codes/Volume/volume.php?volume=<?php echo $selectedVolume; ?>">Volumes</a></div>
                <div><img src="/WRRL/IMAGES/file-invoice.png    "><a class="publish" href="http://localhost/WRRL/ADMIN/Codes/PUBLISHLIST/Publishlist.php">Uploads</a></div>
                <div><img src="/WRRL/IMAGES/file-upload.png"><a class="publish" href="/WRRL/ADMIN/Codes/PUBLISH-PAGE/PUBLISH-PAGE.php">Upload new paper</a></div>
                <div><img src="/WRRL/IMAGES/user.png"><a class="publish" href="/WRRL/ADMIN/Codes/USERLIST/USERLIST.php">List of Accounts</a></div>
                <div><img src="/WRRL/IMAGES/folders.png"><a class="publish" href="/WRRL/ADMIN/Codes/ARCHIVE-PAGE/ARCHIVE-PAGE.php">Archives</a></div>
                <div class="logoutbtn"><img  src="/WRRL/IMAGES/log-out.png"><a class="logout" href="http://localhost/WRRL/ADMIN/Codes/LOGIN-PAGE/Login-Page.php">Logout</a></div>
            </div>
        </div>
    </div>
</nav>

<main>

        <?php
        include("Display.php");

        $itemsPerPage = 5;
        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;


        $totalItems = getTotalResearchCount(); 


        $totalPages = ceil($totalItems / $itemsPerPage);


        $currentPage = max(1, min($currentPage, $totalPages));

        $offset = ($currentPage - 1) * $itemsPerPage;

        $latestResearchData = getLatestResearchData($offset, $itemsPerPage);
    ?>
        <form action="http://localhost/WRRL/ADMIN/Codes/SEARCH-PAGE/SEARCH-PAGE.php" method="GET">
            <div class="Searchbar">
                <input class="type" type="text" name="query" placeholder="Search Repository">
                <input class="search" type="submit" value="Search">
            </div>
        </form>
    <h1 class="Latest-Research-Heading">Latest Uploads</h1>
<div class="Latest-Research-Container">

        

        <?php
        include("/xampp/htdocs/WRRL/USER/Codes/ABSTRACT-PAGE/dbconnect.php");

        $latestResearchData = getLatestResearchData($offset, $itemsPerPage);

        foreach ($latestResearchData as $research) {
            echo '<div class="Research-Info" data-strand="' . $research['Strand_name'] . '">';
            echo '<button class="Strand">' . $research['Strand_name'] . '</button><br>';
            echo '<a class="Research-Paper-title" href="/WRRL/ADMIN/Codes/ABSTRACT-PAGE/ABSTRACT-PAGE.php?uid=' . $research['UID'] . '">' . $research['RTitle'] . '</a>';
            echo '<p class="Date-and-Author">' . $research['Date'] . ' - ' . $research['Authors'] . '</p>';
            echo '<p class="Abstract">' . $research['Abstract'] . '</p>';
            echo '<p class="pubstat">' . $research['Publish_status'] . '</p>';
            echo '</div>';
        }
        ?>


<div class="page_con">
    <div class="pagination" id="pagination">
        <?php
        echo '<a href="?page=' . ($currentPage - 1) . '">Previous</a>';

        $maxPagesToShow = 5; // Change this to set the maximum number of pages to display

        if ($totalPages <= $maxPagesToShow) {
            // If total pages are less than or equal to the maximum, display all pages
            for ($i = 1; $i <= $totalPages; $i++) {
                echo '<a href="?page=' . $i . '" ' . ($currentPage == $i ? 'class="active"' : '') . '>' . $i . '</a>';
            }
        } else {
            // If total pages exceed the maximum, display ellipsis
            $halfMaxPagesToShow = floor($maxPagesToShow / 2);

            // Display pages around the current page
            if ($currentPage - $halfMaxPagesToShow > 1) {
                echo '<a href="?page=1">1</a>';
                echo '<a class="ellipsis">...</a>';
            }

            for ($i = max(1, $currentPage - $halfMaxPagesToShow); $i <= min($totalPages, $currentPage + $halfMaxPagesToShow); $i++) {
                echo '<a href="?page=' . $i . '" ' . ($currentPage == $i ? 'class="active"' : '') . '>' . $i . '</a>';
            }

            // Display ellipsis for omitted pages after the current page
            if ($currentPage + $halfMaxPagesToShow < $totalPages) {
                echo '<a class="ellipsis">...</a>';
                echo '<a href="?page=' . $totalPages . '">' . $totalPages . '</a>';
            }
        }

        echo '<a href="?page=' . ($currentPage + 1) . '">Next</a>';
        ?>
    </div>

    <script>
        document.getElementById('pagination').addEventListener('click', function (e) {
            e.preventDefault();

            var links = document.querySelectorAll('.pagination a');
            links.forEach(function (link) {
                link.classList.remove('active');
            });

            var clickedPage = e.target.getAttribute('href').split('=')[1];
            e.target.classList.add('active');

            history.pushState(null, null, '?page=' + clickedPage);

            loadNewContent(clickedPage);
        });

        function loadNewContent(clickedPage) {
            // Your existing JavaScript code for handling content loading
            // Add any additional logic here if needed
            location.reload();
        }
    </script>
</div>


    </div>
</main>
</body>
</html>
