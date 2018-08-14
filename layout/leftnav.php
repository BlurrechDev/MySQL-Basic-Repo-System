<div id="leftnav"> <!-- The divider element encapsulating the left-aligned navigation menu. -->
  <h1><strong>Navigate <img src='company_logo.png'></strong></h1> <!-- The focused navigation title with additional company image branding. -->
  <p><a href="index.php">Home</a></p> <!-- A hyperlink to the system home page or landing page. -->
  <p><a href="projects.php">Projects</a></p> <!-- A hyperlink to the projects list page. -->
  <?php
    if (isset($_SESSION['UserAdmin'])) { // If the user is logged in. (If this session variable is set, it is implied)
        if ($_SESSION['UserAdmin']) echo '<p><a href="admin.php">Admin Panel</a></p>'; // If the user is an admin, output a hyperlink to the admin panel.
        echo '<p><a href="scripts/logout.php">Logout</a></p>'; // A hyperlink to initiate a logout, allowing a user prompted login session end.
    } else {
        echo '<p><a href="login.php">Login</a></p>'; // A hyperlink to the login page, allowing a user prompted login session start.
    }
  ?>
</div>