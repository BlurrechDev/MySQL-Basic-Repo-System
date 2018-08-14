<?php session_start(); ?> <!-- Starts the login session so that frequently used user details can be easily accessed throughout the system. -->
<!DOCTYPE html>
<html>
<head>
<title>Users</title> <!-- The tab title to contextual explain the page purpose. -->
<?php require 'layout/headings.php'; ?> <!-- Loads the CSS links and meta tags of the master page. -->
</head>
<body>
  <div id="header"></div> <!-- The header element, portraying the company logo as the divider background. -->
  <?php require 'layout/leftnav.php'; ?> <!-- The left-aligned system navigation section. -->
  <?php require 'layout/rightnav.php'; ?> <!-- The right-aligned system project search section. -->
  <div class="input-pane"> <!-- A white bordered themed container surrounding input or output contents. -->
    <h1>ACCOUNTS</h1><hr> <!-- The central page heading, underlined to provide the page with a focus. -->
    <table class="border">
    <?php
    require_once 'scripts/mysql_connect.php'; // Connect to the underlying MySQL database.
    $resource = mysql_query("SELECT * FROM Users ORDER BY UserForename ASC"); // A query that returns a list of all users in ascending alphabetical order of their forename.
    while($row = mysql_fetch_array($resource)) echo "<tr class='hoverer'><td>{$row['UserForename']} {$row['UserSurname']}<br>{$row['UserEmail']}<br><br></td></tr>"; // While the query is still returning a row, print out the user's email and full name.
    ?>
    </table>
  </div>
  <?php require 'layout/footer.php'; ?> <!-- Loads footer element of the page, for copyright or company information. -->
</body>
</html>