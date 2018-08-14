<?php session_start(); ?> <!-- Starts the login session so that frequently used user details can be easily accessed throughout the system. -->
<!DOCTYPE html>
<html>
<head>
<title>Projects</title> <!-- The tab title to contextual explain the page purpose. -->
<?php require 'layout/headings.php'; ?> <!-- Loads the CSS links and meta tags of the master page. -->
</head>
<body>
  <div id="header"></div> <!-- The header element, portraying the company logo as the divider background. -->
<?php 
require 'layout/leftnav.php'; // The left-aligned system navigation section.
require 'layout/rightnav.php'; // The right-aligned system project search section.
require_once 'scripts/mysql_connect.php'; // Connect to the underlying MySQL database.
require_once 'scripts/create_projects.php'; // Loads the Create Project PHP file, for when it is required.
require_once 'scripts/delete_projects.php'; // Loads the Delete Project PHP file, for when it is required.
require_once 'scripts/edit_projects.php'; // Loads the Edit Project PHP file, for when it is required.
require_once 'scripts/view_projects.php'; // Loads the View Project PHP file, for when it is required.
if (!strpos(currentURL(), '=')) {  // If the URL does not have any get parameters.
?>
  <div class="input-pane"> <!-- A white bordered themed container surrounding input or output contents. -->
    <h1>PROJECTS</h1><hr> <!-- The header element, portraying the company logo as the divider background. -->
<?php
    $resource = mysql_query("SELECT * FROM Projects ORDER BY ProjectName DESC"); // A query to all projects, ordered by their names, descending alphabetically.
    while($row = mysql_fetch_array($resource)) { // While the query is still returning a row
        echo "<div class='project-pane'><img class='productimage' src='projects/{$row['ProjectID']}/image.jpg' alt='{$row['ProjectName']}' width='150px;' height='190px;'/><h4>{$row['ProjectName']}</h4><p>{$row['ProjectDescription']}</p><a href='projects.php?view={$row['ProjectID']}'>View</a>";
        if (isset($_SESSION['UserAdmin'])) { // If the logged in user is an administrator.
            echo "<a style='padding-left: 10px;' href='projects.php?deleteid={$row['ProjectID']}' onclick='return confirm(\"Are you sure you want to delete that project?\")'>Delete</a><a style='padding-left: 10px;' href='projects.php?editid={$row['ProjectID']}'>Edit</a>";
        }
        echo '</div>';
    }
    if (mysql_num_rows($resource) == 0) echo "No projects have been created yet."; // If no rows are returned, output an explanatory message.
    echo '</div>';
  }
  require 'layout/footer.php'; // Loads footer element of the page, for copyright or company information.
?>
</body>
</html>