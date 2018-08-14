<?php session_start(); ?> <!-- Starts the login session so that frequently used user details can be easily accessed throughout the system. -->
<!DOCTYPE html>
<html>
<head>
<title>Home</title> <!-- The tab title to contextual explain the page purpose. -->
<?php require 'layout/headings.php'; ?> <!-- Loads the CSS links and meta tags of the master page. -->
</head>
<body>
  <div id="header"></div> <!-- The header element, portraying the company logo as the divider background. -->
  <?php require 'layout/leftnav.php'; ?> <!-- The left-aligned system navigation section. -->
  <?php require 'layout/rightnav.php'; ?> <!-- The right-aligned system project search section. -->
  <div class="input-pane"> <!-- A white bordered themed container surrounding input or output contents. -->
    <h1>HOME</h1><hr> <!-- The central page heading, underlined to provide the page with a focus. -->
    <p style="font-size: 2vw">Software Development Application</p>
    <p style="height: 60px;">This is the internal software development and project management system for Plexus Solutions. To begin, please use the left-hand side navigation or the right-hand side search bar.</p>
  </div>
  <?php require 'layout/footer.php'; ?> <!-- Loads footer element of the page, for copyright or company information. -->
</body>
</html>