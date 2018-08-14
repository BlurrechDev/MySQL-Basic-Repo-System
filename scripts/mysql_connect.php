<?php  
$connection = mysql_connect("productsdev.db.11652653.hostedresource.com", "productsdev", "fakepass12AB!") or die("could not connect to mysql"); // Opens a connection to the system's MySQL server.
$db = mysql_select_db("productsdev") or die("No database was found."); // Sets the current active database on the MySQL server.
require_once 'utilities.php'; // Loads the system's global utility functions, so any pages accessing the database have immediate access to them.
?>