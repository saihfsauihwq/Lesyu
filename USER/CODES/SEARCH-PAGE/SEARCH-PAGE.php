<!Doctype html>
  <html lang="en">
    <head>
      <meta charset= UTF-8>
      <meta name="Author" content="Jhon llyod Navarro">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta name="description" content="Research Papers repository For Liceo">
      <meta name="keywords" content="Research, Repository, Liceo, University, Admin interface">
      <title>Web-Based Research Repository</title>
      <link rel="Stylesheet" href="General.css">
      <link rel="Stylesheet" href="Header.css">
      <link rel="Stylesheet" href="main.css">
      <link rel="Stylesheet" href="Searchbar.css">
      <link rel="Stylesheet" href="Nav.css">
      <link rel="Stylesheet" href="Page.css">
      <link rel="Icon" href="/WRRL/IMAGES/favicon.png">
    </head>
    <body>

    <header>
        <div class="Header-background">
            <div class="Logo">
                <a href="/WRRL/USER/Codes/HOME-PAGE/HOME-PAGE.php">
                    <img class="Logo-icon" src="/WRRL/IMAGES/mainlogo.png">
                </a>
            </div>
            <div class="Title">
                <p class="Name">Liceo U Repository</p>
                <p class="line">Committed to Total Human Formation!</p>
            </div>
        </div>
    </header>

      <nav>
        
        <form id="searchForm" method="GET" action="SEARCH-PAGE.php">
            <div class="Nav-background">
                <h2 class="filtersec">Search filter</h2>
                <input class="Search-Author" type="text" name="author" placeholder="Author" value="<?php echo isset($_GET['author']) ? htmlspecialchars($_GET['author']) : ''; ?>">
                <select class="Strands" name="strand">
                    <option>Select strand</option>
                    <?php
                    include('Strands.php');
                    $strands = getStrands();
                    foreach ($strands as $strand) {
                        $selected = (isset($_GET['strand']) && $_GET['strand'] == $strand['Strand_ID']) ? 'selected' : '';
                        echo "<option value='{$strand['Strand_ID']}' $selected>{$strand['Strand_name']}</option>";
                    }
                    ?>
                </select>
 

                <div class="yearpicker">
                    <input class="Year-select" type="number" name="startYear" id="startYearPicker" min="1900" max="2100" value="<?php echo isset($_GET['startYear']) ? htmlspecialchars($_GET['startYear']) : '2020'; ?>">
                    <p class="to">To</p>
                    <input class="Year-select" type="number" name="endYear" id="endYearPicker" min="1900" max="2100" value="<?php echo isset($_GET['endYear']) ? htmlspecialchars($_GET['endYear']) : '2024'; ?>">
                </div>
                <input type="hidden" name="query" value="<?php echo isset($_GET['query']) ? htmlspecialchars($_GET['query']) : ''; ?>">
                <button class="Search" type="submit">Search</button>
            </div>
        </form>
      </nav>

      <main>
        <div class="Search-Result">
        <form action="http://localhost/WRRL/USER/Codes/SEARCH-PAGE/SEARCH-PAGE.php" method="GET">
            <div class="Searchbar">
                <input class="type" type="text" name="query" placeholder="Search Repository" value="<?php echo isset($_GET['query']) ? htmlspecialchars($_GET['query']) : ''; ?>">
                <input class="search" type="submit" value="Search">
            </div>
        </form>
        <h2 class="Result-Header">Search Results</h2>

        <?php
        include("search-process.php");

        $itemsPerPage = 5;
        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

        $searchResults = [];

        if (isset($_GET['query'])) {
            $searchQuery = $_GET['query'];
            $searchResults = performSearch($searchQuery, $_GET);
        }

        $totalItems = count($searchResults);
        $totalPages = ceil($totalItems / $itemsPerPage);

        $currentPage = max(1, min($currentPage, $totalPages));
        $offset = ($currentPage - 1) * $itemsPerPage;

        $pagedResults = array_slice($searchResults, $offset, $itemsPerPage);

        if (empty($pagedResults)) {
            echo '<p>No Research Found.</p>';
        } else {
            foreach ($pagedResults as $result) {
                echo '<div class="Research-Info">';
                echo '<button class="Strand">' . $result['Strand_name'] . '</button><br>';
                echo '<a class="Research-Paper-title" href="/WRRL/USER/Codes/ABSTRACT-PAGE/ABSTRACT-PAGE.php?uid=' . $result['UID'] . '">' . $result['RTitle'] . '</a>';
                echo '<p class="Date-and-Author">' . $result['Date'] . ' - ' . $result['Authors'] . '</p>';
                echo '<p class="Abstract">' . $result['Abstract'] . '</p>';
                echo '<p class="pubstat">' . $result['Publish_status'] . '</p>';
                echo '</div>';
            }
        }

        // Update pagination links
        echo '<div class="page_con">';
        echo '<div class="pagination" id="pagination">';
        $queryString = http_build_query(array_merge($_GET, ['page' => null])); // Remove 'page' from the query

        echo '<a href="?page=' . ($currentPage - 1) . '&' . $queryString . '">Previous</a>';

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

        echo '<a href="?page=' . ($currentPage + 1) . '&' . $queryString . '">Next</a>';
        echo '</div>';
        echo '</div>';
        ?>
    </div>
</main>
    </body>
  </html>