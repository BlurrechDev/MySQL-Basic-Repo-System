<?php 
ob_start(); // Turns on output buffering to enable header redirection.
session_start(); // Starts the login session so that frequently used user details can be easily accessed throughout the system.
header('Cache-control: private'); // Adjusts the header mode allowing PHP code triggered navigations/redirects to occur below.
if (isset($_POST['Username'])) { // If the username was set pre-submission
  if ($_POST['Username'] != '' && $_POST['Password'] != '') { // If the username and the password are not empty strings.
    require_once 'scripts/mysql_connect.php'; // Connect to the underlying MySQL database.
    $resource = mysql_query("SELECT * FROM Users WHERE UserEmail = '{$_POST['Username']}' AND UserPassword = '{$_POST['Password']}'"); // A query to return the user whose username and password are a correct match.
    if (mysql_num_rows($resource) != 1) { // If the number of users returned is not one.
      $error = "Your username or password are incorrect!"; // Assign the error message to outline that the input details were incorrect.
    } else {
      $_SESSION['UserID'] = mysql_result($resource, 0, 'UserID'); // Stores the identifier of the logged in user to the global session.
      $_SESSION['UserAdmin'] = mysql_result($resource, 0, 'UserAdmin'); // Stores the administrative status of the logged in user to the global session.
      if ($_SESSION['UserAdmin']) { // If the user is an administrator
          header('Location: admin.php'); // Redirects to the admin panel page, after a successful administrator login.
      } else {
          header('Location: projects.php'); // Redirects to the project list page, after a successful user login.
      }
    }
  } else {
      $error = "Your username or password are incorrect!"; // Assign the error message to outline that the input details were incorrect.
  }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Login</title> <!-- The tab title to contextual explain the page purpose. -->
<?php require 'layout/headings.php'; ?> <!-- Loads the CSS links and meta tags of the master page. -->
</head>
<body>
  <div id="header"></div> <!-- The header element, portraying the company logo as the divider background. -->
  <?php require 'layout/leftnav.php'; ?> <!-- The left-aligned system navigation section. -->
  <?php require 'layout/rightnav.php'; ?> <!-- The right-aligned system project search section. -->
  <div class="input-pane"> <!-- A white bordered themed container surrounding input or output contents. -->
    <h1>LOGIN</h1><hr> <!-- The central page heading, underlined to provide the page with a focus. -->
    <form method="post"> <!-- The login form encapsulating all data inputs. -->
      <input type="email" name="Username" placeholder="Email" maxlength="30" /> <!-- The email input element, representing the login username. -->
      <input pattern="^(?=.*\d)(?=.*[a-zA-Z])(?!.*[\W_\x7B-\xFF]).{6,15}$" title="Your password must contain 6-15 characters including at least 1 letter, and 1 number." type="password" name="Password" placeholder="Password" maxlength="30"/> <!-- The password input element, representing the login password. -->
      <p style="color: red;"><?= $error; ?></p> <!-- An error message PHP variable that is only displayed if a login is unsuccessful. -->
      <input type="submit" name="Submit" value="Login" /> <!-- The button submit input element, that submits the form and initiates the login process. -->
      <a href="register.php">Don't have an account?</a> <!-- A registration hyperlink allowing a user without an account to create one. -->
    </form>
  </div>
  <?php require 'layout/footer.php'; ?> <!-- Loads footer element of the page, for copyright or company information. -->
</body>
</html>