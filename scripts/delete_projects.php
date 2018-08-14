<?php 
if (isset($_GET['deleteid'])) { // If the project, represented by it's ID, has been confirmed for deletion.
    if ($_GET['deleteid'] == '') return; // Avoids an accidental URL malfunction deleting the entire projects directory.
    mysql_query("DELETE FROM Projects WHERE ProjectID='{$_GET['deleteid']}' LIMIT 1") or die (mysql_error()); // A query to delete the requested project from Projects table.
    deleteEntity("projects/{$_GET['deleteid']}/"); // Deletes the code of the now removed project from the filesystem.
    echo "<div class='input-pane note'> You have successfully deleted project {$_GET['deleteid']}! </div>";
}
?>