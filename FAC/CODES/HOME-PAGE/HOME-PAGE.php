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
                    <a class="nav-text" href="/WRRL/FAC/Codes/HOME-PAGE/HOME-PAGE.php" style=" Color: rgb(164,0,1);">Home</a>
                </li>
                <li class="nav-text" >
                    <a class="nav-text" href="/WRRL/FAC/Codes/HOME-PAGE-STEM/HOME-PAGE-STEM.php">Science, Technology, Engineering, and Mathematics</a>
                </li>
                <li class="nav-text" >
                    <a class="nav-text" href="/WRRL/FAC/Codes/HOME-PAGE-ABM/HOME-PAGE-ABM.php">Accountancy, Business and Management</a>
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
        <form action="http://localhost/WRRL/FAC/Codes/SEARCH-PAGE/SEARCH-PAGE.php" method="GET">
            <div class="Searchbar">
                <input class="type" type="text" name="query" placeholder="Search Repository">
                <input class="search" type="submit" value="Search">
            </div>
        </form>
        
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
        <h1 class="Latest-Research-Heading">Latest Studies</h1>
        <div class="Latest-Research-Container">


            <?php
            include("/xampp/htdocs/WRRL/FAC/Codes/ABSTRACT-PAGE/dbconnect.php");

            $latestResearchData = getLatestResearchData($offset, $itemsPerPage);

            foreach ($latestResearchData as $research) {
                echo '<div class="Research-Info" data-strand="' . $research['Strand_name'] . '">';
                echo '<button class="Strand">' . $research['Strand_name'] . '</button><br>';
                echo '<a class="Research-Paper-title" href="/WRRL/FAC/Codes/ABSTRACT-PAGE/ABSTRACT-PAGE.php?uid=' . $research['UID'] . '">' . $research['RTitle'] . '</a>';
                echo '<p class="Date-and-Author">' . $research['Date'] . ' - ' . $research['Authors'] . '</p>';
                echo '<p class="Abstract">' . $research['Abstract'] . '</p>';
                echo '<p class="pubstat">' . $research['Publish_status'] . '</p>';
                echo '</div>';
            }
            ?>

<div class="page_con">
            <div class="pagination" id="pagination">
                <?php
                echo '<a href="?page=' . max($currentPage - 1, 1) . '">Previous</a>';

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

                echo '<a href="?page=' . min($currentPage + 1, $totalPages) . '">Next</a>';
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
                    var url = window.location.href.split('?')[0] + '?page=' + clickedPage;

                    fetch(url, {
                        method: 'GET'
                    })
                    .then(response => response.text())
                    .then(data => {
                        var parser = new DOMParser();
                        var newDocument = parser.parseFromString(data, 'text/html');
                        document.body.innerHTML = newDocument.body.innerHTML;

                        // Re-run the script to ensure event listeners are attached to new elements
                        var script = newDocument.querySelector('script');
                        if (script) {
                            eval(script.innerHTML);
                        }
                    })
                    .catch(error => console.error('Error:', error));
                }
            </script>
        </div>

            
        </div>
        

    </main>
    </body>
</html>
