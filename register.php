<?php session_start(); ?> <!-- Starts the login session so that frequently used user details can be easily accessed throughout the system. -->
<!DOCTYPE html>
<html>
<head>
<title>Register</title> <!-- The tab title to contextual explain the page purpose. -->
<?php require 'layout/headings.php'; ?> <!-- Loads the CSS links and meta tags of the master page. -->
</head>
<body>
  <div id="header"></div> <!-- The header element, portraying the company logo as the divider background. -->
  <?php require 'layout/leftnav.php'; ?> <!-- The left-aligned system navigation section. -->
  <?php require 'layout/rightnav.php'; ?> <!-- The right-aligned system project search section. -->
  <div class="input-pane"> <!-- A white bordered themed container surrounding input or output contents. -->
    <h1>CREATE ACCOUNT</h1><hr> <!-- The central page heading, underlined to provide the page with a focus. -->
    <?php
    require_once 'scripts/mysql_connect.php'; // Connect to the underlying MySQL database.
    if (isset($_POST['email'])) { // If the email was input. (This implies that the registration details have been entered)
        if ($_POST['password'] != $_POST['vpassword']) { // If the password double keying does not match
            echo 'Your entered passwords do not match <a href="register.php">click here</a>'; // Print out an error message, as at least one of the two passwords is wrong.
	    } else {
            if (mysql_num_rows(mysql_query("SELECT UserID FROM Users WHERE UserEmail='{$_POST['email']}' LIMIT 1")) == 1) { // If the query that returns the number of users with the input username is not empty,
    	        echo "Sorry the email: {$_POST['email']} is already used in the system, please try again.</a>"; // Print out an error message, as the username is already taken.
	        } else {
                mysql_query("INSERT INTO Users (UserEmail, UserPassword, UserForename, UserSurname, UserAdmin) VALUES('{$_POST['email']}', '{$_POST['password']}', '{$_POST['fname']}', '{$_POST['sname']}', '".isset($_POST['admin'])."')") or die (mysql_error()); // A query to add the newly registered user to the Users table.
                echo "You have successfully created an account for {$_POST['email']}.<br><br>"; // Print out a success message and a hyperlink to advance further in the system.
	        }
	    }
    }
    ?>
    <form method="post"> <!-- The registration form encapsulating all data inputs. -->
        <input name="email" type="email" placeholder="Email"/> <!-- The email input element, representing the login username. -->
        <input pattern="^(?=.*\d)(?=.*[a-zA-Z])(?!.*[\W_\x7B-\xFF]).{6,15}$" title="Your password must contain 6-15 characters including at least 1 letter, and 1 number." type="password" name="password" placeholder="Password"/> <!-- The password input element, representing the login password.. -->
        <input pattern="^(?=.*\d)(?=.*[a-zA-Z])(?!.*[\W_\x7B-\xFF]).{6,15}$" title="Your password must contain 6-15 characters including at least 1 letter, and 1 number." type="password" name="vpassword" placeholder="Verify Password"/> <!-- The verify password input element, representing a double key verification check for accuracy. -->
        <input pattern="^[a-zA-Z]+$" title="Your forename can only contain letters." type="text" name="fname" placeholder="Forename"/> <!-- The user's forename input to associate a real identity to them within the system.. -->
        <input pattern="^[a-zA-Z]+$" title="Your surname can only contain letters." type="text" name="sname" placeholder="Surname"/> <!-- The user's surname input element to associate a real formal identity to them within the system -->
        <?=(isset($_SESSION['UserAdmin'])) ? '<p class="label">Admin:</p><input type="checkbox" name="admin"/>' : '' ?> <!-- The admin checkbox element, allowing additional administratot accounts to be created by existing administrators, if necessary. -->
        <input type="submit" name="Submit" value="Register"/> <!-- The button submit input element, that submits the form and initiates the registration process. -->
    </form>
  </div>
  <?php require 'layout/footer.php'; ?> <!-- Loads footer element of the page, for copyright or company information. -->
</body>
</html>