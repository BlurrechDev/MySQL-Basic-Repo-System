<?php session_start(); ?> <!-- Starts the login session so that frequently used user details can be easily accessed throughout the system. -->
<!DOCTYPE html>
<html>
<head>
<title>Admin Login</title> <!-- The tab title to contextual explain the page purpose. -->
<?php require 'layout/headings.php'; ?> <!-- Loads the CSS links and meta tags of the master page. -->
</head>
<body>
  <div id="header"></div> <!-- The header element, portraying the company logo as the divider background. -->
  <?php require 'layout/leftnav.php'; ?> <!-- The left-aligned system navigation section. -->
  <?php require 'layout/rightnav.php'; ?> <!-- The right-aligned system project search section. -->
  <div class="input-pane"> <!-- A white bordered themed container surrounding input or output contents. -->
    <h1>ADMIN PANEL</h1><hr> <!-- The central page heading, underlined to provide the page with a focus. -->
    <input type="submit" onclick="window.location='projects.php?createproject';" value="Create Projects" /> <!-- A button input that navigates to the 'create project' form. -->
    <input type="submit" onclick="window.location='projects.php';" value="Edit Projects"  /> <!-- A button input that navigates to the 'project list' form. -->
    <input type="submit" onclick="window.location='register.php';" value="Create Users"/> <!-- A button input that navigates to the 'user registration' form. -->
    <input type="submit" onclick="window.location='users.php';" value="Edit Users"/> <!-- A button input that navigates to the 'manage users' form. -->
  </div>
  <?php require 'layout/footer.php'; ?> <!-- Loads footer element of the page, for copyright or company information. -->
</body>
</html>