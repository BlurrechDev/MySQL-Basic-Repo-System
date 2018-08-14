<?php session_start(); ?> <!-- Starts the login session so that frequently used user details can be easily accessed throughout the system. -->
<!DOCTYPE html>
<html>
<head>
<title>Search</title> <!-- The tab title to contextual explain the page purpose. -->
<?php require 'layout/headings.php'; ?> <!-- Loads the CSS links and meta tags of the master page. -->
</head>
<body>
  <div id="header"></div> <!-- The header element, portraying the company logo as the divider background. -->
<?php 
require 'layout/leftnav.php'; // The left-aligned system navigation section.
require 'layout/rightnav.php'; // The right-aligned system project search section.
require_once 'scripts/mysql_connect.php'; // Connect to the underlying MySQL database.
?>
  <div class='input-pane'>  <!-- A white bordered themed container surrounding input or output contents. -->
    <h1>SEARCH</h1><hr> <!-- The central page heading, underlined to provide the page with a focus. -->
    <p>Your search produced the following results:</p><br><br> <!-- A descriptive contextual message regarding the search query result.-->
<?php
if (isset($_GET['s'])) { // If the search query has been added as a URL get parameter.
    $resource = mysql_query("SELECT * FROM Projects WHERE ProjectName LIKE '%{$_GET['s']}%'"); // A query to return all projects whose name is similar to the search query.
    while($row = mysql_fetch_array($resource)) { // While the query is still returning a row,
        echo "<div class='project-pane'><img class='productimage' src='projects/{$row['ProjectID']}/image.jpg' alt='{$row['ProjectName']}' width='150px;' height='190px;'/><h4>{$row['ProjectName']}</h4><p>{$row['ProjectDescription']}</p><a href='projects.php?view={$row['ProjectID']}'>View</a>";
        if (isset($_SESSION['UserAdmin'])) echo '<a style="padding-left: 10px;" href="projects.php?deleteid='.$row["ProjectID"].'">Delete</a><a style="padding-left: 10px;" href="projects.php?editid='.$row["ProjectID"].'">Edit</a>'; // If the logged in user is an administrator, display administrative controls.
        echo '</div>';
    }
    if (mysql_num_rows($resource) == 0) echo "No projects match your search."; // If no rows are returned, output an explanatory message.
}
?>
  </div>
<?php require 'layout/footer.php'; ?> <!-- Loads footer element of the page, for copyright or company information. -->
</body>
</html>