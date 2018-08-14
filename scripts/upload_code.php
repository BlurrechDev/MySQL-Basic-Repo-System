<?php
if (isset($_FILES['code'])) { // If the code upload have been completed.
    $resource = mysql_query("SELECT * FROM Milestones WHERE MilestoneProjectID='{$_GET['view']}' LIMIT 1"); // A query that returns all revisions associated to the project.
    $resource = mysql_query("SELECT * FROM Revisions WHERE RevisionMilestoneID='".mysql_result($resource, 0, 'MilestoneID')."' LIMIT 1");
    $dir = "projects/".$_GET['view']."/".mysql_result($resource, 0, 'RevisionID')."/"; // Set the directory to the specific project's revision associated subdirectory.
        if (!file_exists($dir)) mkdir($dir, 0777, true); // If the directory does not exist, make the directory.
    foreach (glob($dir.'*') as $file) deleteEntity($file); // Delete everything in the directory, as the newly uploaded code should create a totally new code base.
    foreach ($_FILES["code"]["error"] as $key => $error) if ($error == UPLOAD_ERR_OK) move_uploaded_file($_FILES["code"]["tmp_name"][$key], $dir.$_FILES['code']['name'][$key]); // For each uploaded file, if there are no errors, move it to the correct project subdirectory. 
    echo "The code was successfully uploaded to the project.";
    return;
}
?>
<div class="input-pane"> <!-- A white bordered themed container surrounding input or output contents. -->
  <h1>UPLOAD CODE</h1><hr> <!-- The central page heading, underlined to provide the page with a focus. -->
  <form method="post" enctype="multipart/form-data"> <!-- The upload code form encapsulating all data inputs. -->
    <input name="code[]" type="file" multiple webkitdirectory/> <!-- The upload code files/folder input element. -->
    <input type="submit" value="Submit"/>  <!-- The button submit input element, that submits the form and initiates the upload code process. -->
  </form>
</div>