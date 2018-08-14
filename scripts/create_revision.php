<?php
if (isset($_POST['RevisionName'])) { // If the Revision has been submitted for creation.
    $resource = mysql_query("SELECT * FROM Revisions WHERE RevisionID='{$_POST['RevisionBase']}' LIMIT 1");
    $revisionChanges = mysql_result($resource, 0, 4);
    $revisionVersion = mysql_result($resource, 0, 5);
    mysql_query("INSERT INTO Revisions (RevisionUserID, RevisionMilestoneID, RevisionName, RevisionDescription, RevisionChanges, RevisionVersion) VALUES('{$_SESSION['UserID']}', '{$_GET['milestoneid']}', '{$_POST['RevisionName']}', '{$_POST['RevisionDescription']}', '{$revisionChanges}', '{$revisionVersion}')") or die(mysql_error());
   
    $dir = 'projects/'.$_GET['view'].'/'.mysql_insert_id().'/'; 
    if (!file_exists($dir)) mkdir($dir, 0777, true);
    
    recurseCopy('projects/'.$_GET['view'].'/'.$_POST['RevisionBase'], $dir);
    echo "<div class='input-pane note'> You have successfully added {$_POST['RevisionName']} into the system. </div>";
    return;
}
?>
<div class="input-pane"> <!-- A white bordered themed container surrounding input or output contents. -->
  <h1>CREATE REVISION</h1><hr> <!-- The central page heading, underlined to provide the page with a focus. -->
  <form method="post" enctype="multipart/form-data"> <!-- The create revision form encapsulating all data inputs. -->
    <input pattern="^[a-zA-Z ]+$" title="A revision name can only contain letters." type="text" name="RevisionName" placeholder="Name"/> <!-- The revision name input element. -->
    <input pattern="^[a-zA-Z ]+$" title="A revision description can only contain letters." type="text" name="RevisionDescription" placeholder="Description"/> <!-- The revision description input element. -->
    <select name="RevisionBase"> <!-- The select element for choosing the revision base. -->
<?php
$resource = mysql_query("SELECT * FROM Milestones WHERE MilestoneProjectID='{$_GET['view']}' LIMIT 1"); // A query that returns all revisions associated to the project.
if (mysql_result(mysql_query("SELECT * FROM Milestones WHERE MilestoneID='{$_GET['milestoneid']}' LIMIT 1"), 0, 'MilestoneName') != 'Project Completion') { // If this milestone is not the meta project milestone:
    $resource = mysql_query("SELECT * FROM Revisions WHERE RevisionMilestoneID='".mysql_result($resource, 0, 'MilestoneID')."' LIMIT 1");
    if (mysql_result($resource, 0, 'RevisionName') != "") echo "<option value='".mysql_result($resource, 0, 'RevisionID')."'>".mysql_result($resource, 0, 'RevisionName')."</option>";
}
$resource = mysql_query("SELECT * FROM Revisions WHERE RevisionMilestoneID='{$_GET['milestoneid']}'"); // A query that returns all revisions associated to the project.
while ($row = mysql_fetch_array($resource)) echo "<option value='{$row['RevisionID']}'>{$row['RevisionName']}</option>"; // While the query is still returning a row, output the possible revision bases.
?>
    </select>
    <input type="submit" value="Create" /> <!-- The button submit input element, that submits the form and initiates the create revision process. -->
  </form>
</div>