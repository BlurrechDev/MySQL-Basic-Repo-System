<?php
if (isset($_POST['EditName'])) { // If the project has been submitted after editing
	mysql_query("UPDATE Projects SET ProjectName='{$_POST['EditName']}', ProjectDescription='{$_POST['EditDescription']}', WHERE ProjectID='{$_POST['thisID']}'"); // Updates the project with the edited information.
	echo "The update was successful.";  // Outputs a success message to keep the user informed.		
	if ($_FILES['ProjectImage']['tmp_name'] != "") { // If the file uploaded successfully, indicated by the existence of it's temporary name:
        unlink("projects/{$_POST['thisID']}/image.jpg"); // Delete the old image file that is no longer required.
        move_uploaded_file($_FILES['ProjectImage']['tmp_name'], "projects/{$_POST['thisID']}/image.jpg"); // Copy over the new image file, completing the image editing requested.
	}
} else if (isset($_GET['editid'])) { // If the project has been requested for editing.
	$row = mysql_fetch_array(mysql_query("SELECT * FROM Projects WHERE ProjectID='{$_GET['editid']}' LIMIT 1")); // A query to return the single project that was requested for editing.
?>
<div class="input-pane"> <!-- A white bordered themed container surrounding input or output contents. -->
  <h1>EDIT PROJECT</h1><hr> <!-- The central page heading, underlined to provide the page with a focus. -->
  <form method="post" enctype="multipart/form-data"> <!-- The edit project form encapsulating all data inputs. -->
    <input name="EditName" type="text" value="<?= $row['ProjectName'] ?>" placeholder="Name"/> <!-- The new project name input element. -->
    <p class='label'>Image:</p><input type="file" name="ProjectImage"/> <!-- The new project image input element. -->
    <textarea name="EditDescription" placeholder="Description" cols="39" rows="3"><?= $row["ProjectDescription"] ?></textarea> <!-- The new project description input element. -->
    <input name="thisID" type="hidden" value="<?= $_GET['editid'] ?>" /> <!-- Ensures the GET project ID variable persists post form submission. -->
    <input type="submit" value="Save Changes"/> <!-- The button submit input element, that submits the form and initiates the edit project process. -->
  </form>
</div>
<?php
}
?>