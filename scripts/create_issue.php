<?php
if (isset($_POST['IssueName'])) { // If the Issue has been submitted for creation.
    mysql_query("INSERT INTO Issues (IssueRevisionID, IssueName, IssueDescription, IssueState) VALUES('{$_GET['revisionid']}', '{$_POST['IssueName']}', '{$_POST['IssueDescription']}', '{$_POST['IssueState']}')") or die(mysql_error()); // A query to insert the created Issue into the Issues table.
    echo "<div class='input-pane note'> You have successfully added {$_POST['IssueName']} into the system. </div>"; // Outputs a success message, giving visual feedback to the user.
    return;
}
?>
<div class="input-pane"> <!-- A white bordered themed container surrounding input or output contents. -->
  <h1>CREATE ISSUE</h1><hr> <!-- The central page heading, underlined to provide the page with a focus. -->
  <form method="post" enctype="multipart/form-data"> <!-- The edit project form encapsulating all data inputs. -->
    <input pattern="^[a-zA-Z ]+$" title="An issue name can only contain letters." type="text" name="IssueName" placeholder="Name"/> <!-- The issue name input element. -->
    <input pattern="^[a-zA-Z ]+$" title="An issue description can only contain letters." type="text" name="IssueDescription" placeholder="Description"/> <!-- The issue description input element. -->
    <p class="label">State:</p> <!-- Descriptive text to explain the dropdown box option selection element. -->
    <select name="IssueState"> <!-- The select element for choosing the issue state. -->
      <option value="Unassigned">Unassigned</option> <!-- The unassigned option for the issue state. -->
      <option value="Started">Started</option> <!-- The started option for the issue state. -->
      <option value="Delayed">Delayed</option> <!-- The delayed option for the issue state. -->
      <option value="Reviewed">Reviewed</option> <!-- The reviewed option for the issue state. -->
      <option value="Completed">Completed</option> <!-- The completed option for the issue state. -->
    </select>
    <input type="submit" value="Create" /> <!-- The button submit input element, that submits the form and initiates the create issue process.  -->
  </form>
</div>