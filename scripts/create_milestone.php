<?php
if (isset($_POST['MilestoneName'])) { // If the Milestone has been submitted for creation.
    mysql_query("INSERT INTO Milestones (MilestoneProjectID, MilestoneName, MilestoneDescription, MilestoneStartDate, MilestoneTargetDate) VALUES('{$_GET['view']}', '{$_POST['MilestoneName']}', '{$_POST['MilestoneDescription']}', '{$_POST['MilestoneStartDate']}', '{$_POST['MilestoneTargetDate']}')") or die (mysql_error()); // A query to insert the created milestone into the Milestones table.
    echo "<div class='input-pane note'> You have successfully added {$_POST['MilestoneName']} into the system. </div>"; // Outputs a success message, giving visual feedback to the user.
    return;
}
?>
<div class="input-pane"> <!-- A white bordered themed container surrounding input or output contents. -->
  <h1>CREATE MILESTONE</h1><hr> <!-- The central page heading, underlined to provide the page with a focus. -->
  <form method="post" enctype="multipart/form-data"> <!-- The create milestone form encapsulating all data inputs. -->
    <input pattern="^[a-zA-Z ]+$" title="A milestone name can only contain letters." type="text" name="MilestoneName" placeholder="Name"/> <!-- The milestone name input element. -->
    <input pattern="^[a-zA-Z ]+$" title="A milestone description can only contain letters." type="text" name="MilestoneDescription" placeholder="Description"/> <!-- The milestone description input element. -->
    <input type="date" name="MilestoneStartDate" placeholder="Start Date"> <!-- The milestone start date input element. -->
    <input type="date" name="MilestoneTargetDate" placeholder="Target Date"> <!-- The milestone end date input element. -->
    <input type="submit" value="Create"/> <!-- The button submit input element, that submits the form and initiates the create issue process.  -->
  </form>
</div>