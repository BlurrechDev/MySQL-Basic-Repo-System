<?php
if (isset($_POST['ProjectName'])) { // If the Project has been submitted for creation.
	mysql_query("INSERT INTO Projects (ProjectName, ProjectDescription) VALUES('{$_POST['ProjectName']}', '{$_POST['ProjectDescription']}')") or die (mysql_error()); // Inserts the newly created project into the Projects table.
    $dir = 'projects/'.mysql_insert_id().'/'; // Set the directory to be manipulated as this particular project's directory.
    if (!file_exists($dir)) mkdir($dir, 0777, true); // If the file-directory does not exist, make the directory recursively.
    move_uploaded_file($_FILES['ProjectImage']['tmp_name'], $dir . 'image.jpg'); // Moves the uploaded image to the project's associated directory.
    mysql_query("INSERT INTO Milestones (MilestoneProjectID, MilestoneName, MilestoneDescription, MilestoneStartDate, MilestoneTargetDate) VALUES('" . mysql_insert_id() . "', 'Project Completion', 'The project completion milestone representing the meta deadline.', 'Undecided', 'Undecided')") or die (mysql_error()); // Inserts a default project overall milestone into the Milestones table.
    mysql_query("INSERT INTO Revisions (RevisionUserID, RevisionMilestoneID, RevisionName, RevisionDescription, RevisionChanges, RevisionVersion) VALUES('{$_SESSION['UserID']}', '" . mysql_insert_id() . "', 'Master', 'The master final version of the project for completion.', '0', '1.1')") or die (mysql_error()); // Inserts a default project overall revision into the Revisions table.
    $dir .= mysql_insert_id() . '/'; // Set the directory to be manipulated as this particular project's revision directory.
    if (!file_exists($dir)) mkdir($dir, 0777, true); // If the file-directory does not exist, make the directory.
    echo "<div class='input-pane note'> You have successfully added {$_POST['ProjectName']} into the system. </div>"; // Outputs a success creation message, to keep the user informed.
} else if (isset($_GET['createproject'])) { // Else if the project creation page should be displayed:
?>
<div class="input-pane"> <!-- A white bordered themed container surrounding input or output contents. -->
  <h1>CREATE PROJECT</h1><hr> <!-- The central page heading, underlined to provide the page with a focus. -->
  <form method="post" enctype="multipart/form-data"> <!-- The project creation form encapsulating all data inputs. -->
    <input pattern="^[a-zA-Z ]+$" title="A project name can only contain letters." type="text" name="ProjectName" placeholder="Name"/> <!-- The project name input element. -->
    <input pattern="^[a-zA-Z ]+$" title="A project description can only contain letters." type="text" name="ProjectDescription" placeholder="Description"/> <!-- The project description input element. -->
    <p class='label'>Image</p><input type="file" name="ProjectImage"/> <!-- The project image file input element. -->
    <input type="submit" value="Create"/> <!-- The button submit input element, that submits the form and initiates the create issue process. -->
  </form>
</div>
<?php
}
?>