<?php
if (!isset($_GET['view'])) return; // Ensures there is a ProjectID that has been requested for view. If not, exit the page to prevent any errors.
$row = mysql_fetch_array(mysql_query("SELECT * FROM Projects WHERE ProjectID='{$_GET['view']}' LIMIT 1")); // A query that returns the Project entity that is being viewed.
?>
<br><br>
<div style='margin-bottom: 13vw;'> <!-- The detailed project view display, allowing for project navigation, planning and development. -->
    <div class='project-menu'> <!-- The left column of the three-column project view layout. -->
        <input type="submit" value="Upload Code" onclick="window.location='projects.php?view=<?=$_GET['view']?>&code=2';"/>
        <input type="submit" value="New Milestone" onclick="window.location='projects.php?view=<?=$_GET['view']?>&milestones=2';"/>
        <input type="submit" value="Back" onclick="window.location='projects.php';"/>
    </div>
    <div class='project-menu' style='width: 27%;'> <!-- The central column of the three-column project view layout. -->
        <img class='productimage' src='projects/<?=$row["ProjectID"]?>/image.jpg' alt='<?=$row["ProjectName"]?>' width='150px;' height='190px;' />
        <h4><?=$row["ProjectName"]?></h4><p><?=$row["ProjectDescription"]?></p><br><br>
    </div>
    <div class='project-menu'> <!-- The right column of the three-column project view layout. -->
        <input type="submit" value="Export Code" onclick="window.location='projects.php?view=<?=$_GET['view']?>&export=1';"/>
        <input type="submit" value="Milestones" onclick="window.location='projects.php?view=<?=$_GET['view']?>&milestones=1';"/>
    </div>
</div>
<br><br><br>
<?php
if ($_GET['milestones'] == 1) { // If the milestones subsection of the project have been requested for display:
    if (isset($_GET['milestoneid'])) { // If a specific milestone has been selected.
        $row = mysql_fetch_array(mysql_query("SELECT * FROM Milestones WHERE MilestoneID='{$_GET['milestoneid']}' LIMIT 1")); // A query to select the milestone that will be shown.    
        $startDate = strtotime($row['MilestoneStartDate']); // The start date time of the milestone being shown.
        $targetDate = strtotime($row['MilestoneTargetDate']); // The target end date time of the milestone being shown.
        $daysSet = floor(($targetDate - $startDate) / (60 * 60 * 24)); // The number of days set between the start and end milestone dates.
        $daysLeft = floor(($targetDate - time()) / (60 * 60 * 24)); // The number of days remaining before the milestone becomes overdue.
        echo '<br><div style="width: 34%;" class="input-pane border">';
        echo "<h2>{$row['MilestoneName']}</h2>{$row['MilestoneDescription']}<br>{$row['MilestoneStartDate']} to {$row['MilestoneTargetDate']}<br><meter value='{$daysLeft}' max='{$daysSet}'>{$daysLeft} remaining to complete.</meter><hr>"; // The actual milestone element with a detailed progress bar and description.
        if (isset($_GET['revisions'])) { // If the revision creation pane should be shown.
            echo '<input type="submit" value="Back" onclick="window.history.back()" /> </div>'; // Output a back button to improve the user experience.
            require_once 'create_revision.php'; // Load the create milestone form and process.
        } else if (isset($_GET['revisionid'])) { // If a specific revision has been selected.
            $row = mysql_fetch_array(mysql_query("SELECT * FROM Revisions WHERE RevisionID='{$_GET['revisionid']}' LIMIT 1")); // A query to select the revision that will be shown.  
            echo "<div class='revision border'><h4 style='margin: 0'>{$row['RevisionName']} (Version: {$row['RevisionVersion']} Changes: {$row['RevisionChanges']})</h4>{$row['RevisionDescription']}<br>"; // The actual revision element with the version, change and name.
            if (isset($_GET['file']) || $_GET['code'] == 1) { // If the revision files explorer or a specific file is shown.
                echo '<input type="submit" value="Back" onclick="window.history.back()" />'; // Output a back button to improve the user experience.
            } else {
                echo '<input type="submit" value="Code" onclick="window.location=\''.currentURL().'&code=1\';"/>'; // Output a button to display the revision files explorer when pressed.
                if ($_SESSION['UserAdmin']) echo '<input type="submit" value="Merge" onclick="if (confirm(\'Are you sure you wish to merge this revision with its parent?\')) { alert(\'Successfully Merged\'); window.location = \'/system/merge.php?revisionid='.$_GET['revisionid'].'&baseid='.'000'.'\'; }"/>'; // Output a button to begin the merging process for administrator's.
            }
            echo '</div>';
            if (isset($_GET['file'])) { // If a specific file's code is being edited/viewed.
                if (isset($_POST['savechanges'])) { // If the file is being saved.
                    file_put_contents('projects/'.$_GET["view"].'/'.$_GET["revisionid"].'/'.urldecode(htmlspecialchars($_GET['file'])), $_POST['code']); // Save the new file changes to the file on the web server in the revision folder.
                    echo "<p style='text-align: center;'> The file has been successfully saved to the current revision. </p>"; // Output a success message confirming that the save was successful.
                }
                echo '<br><div id="editor">'.file_get_contents('projects/'.$_GET["view"].'/'.$_GET["revisionid"]."/".urldecode(htmlspecialchars_decode($_GET['file']))).'</div>'; // Display the Ace file editor with the selected code file that will be viewed/edited.
?>
<script src="library/ace/src-noconflict/ace.js" type="text/javascript" charset="utf-8"></script> <!-- Imports the Ace editor script to make the ace divider element now on the page functional. -->
<script src="library/ace/src-noconflict/ext-modelist.js"></script> <!-- Imports the Ace editor extension script that dynamically changes the code syntax highlighting based on the file type opened. -->
<script>
    editor = ace.edit('editor'); // Start the editor with the #editor divider element above.
    editor.getSession().setMode('ace/mode/javascript'); // Default to javascript syntax highlighting, incase the auto-detection fails.
    editor.getSession().setNewLineMode('unix'); // Use unix new lines to prevent a bug where the file collapses to a single line format.
    editor.setOptions({maxLines: Infinity}); // Set the height of the editor to as many lines as the file contains. This ensures the page doesn't have double scroll bars and a poor user experience.
    var modelist = ace.require('ace/ext/modelist'); // Initiate the syntax highlighting mode changer extension.
    var filePath = '<?=currentURL()?>'; // The file extension is the last GET parameter, so can be obtained directly through the utility function.
    var mode = modelist.getModeForPath(filePath).mode; // Get the auto detected programming language mode.
    editor.getSession().setMode(mode); // Set the auto detected programming language mode's syntax highlighting.
</script>
<form id="saver" method="post"> <!-- The save form for persisting any code file changes on the revision.  -->
    <textarea name="code" id="code" style='display: none;'></textarea> <!-- A hidden formula to allow variable access through the POST method. -->
    <input type="submit" name="savechanges" value="Save changes"/> <!-- The save submit button input element that initiates the saving of file changes to the revision. -->
</form>
<script>document.getElementById("saver").addEventListener("submit", function(e) {document.getElementById('code').value =  editor.getSession().getValue();});</script> <!-- A script that allows the POST method of access workaround for the code changes to become functional. -->
<?php
            } else if ($_GET['code'] == 1) { // If the revision's code filesystem should be shown.
                $root = 'projects/'.$_GET["view"].'/'.$_GET["revisionid"].'/'; // The root directory where the code is located for a particular revision.
                $path = null;
                if (isset($_GET['file'])) { // If a specific file/directory has been selected:
                    $path = $_GET['file']; // Set the path to be that file/directory path instead of the root directory.
                    if (is_in_dir($path, $root)) $path = '/'.$path; // Ensure, if it is a directory, that it can be appropriately appended to the root path.
                }
                if (is_file($root.$path)) { // If the full path references a file.
                    readfile($root.$path); // Read the file and do not show a filesystem, but instead the file contents.
                    return;
                }
                echo "<div style='min-height: 30px; margin-top: 20px; margin-bottom: 20px;' class='input-pane border'><h4 style='margin-bottom: 0; margin-top: 0;'>FILES</h4>"; // The filesystem selection heading title.
                if ($path) echo '<a href="'.currentURL().'&file='.urlencode(substr(dirname($root.$path), strlen($root) + 1)).'">..</a><br>';
                $empty = true; // A variable to determine whether the directory being shown is empty, in order to show an error message if necessary.
                foreach (glob($root.$path.'/*') as $file) {
                    $empty = false; // Do not show the error message.
                    $link = substr($file, strlen($root) + 1);
                    echo '<a href="'.currentURL().'&file='.urlencode($link).'">'.basename(realpath($file)).'</a><br>';
                }
                if ($empty) echo "There aren't any changed files in this revision."; // The filesystem is empty, so output an explanatory error message.
                echo "</div>";
            } else if (isset($_GET['issues'])) { // If the issues subsection of the project have been requested for display:
                if ($_GET['issues'] == 1) { // If a specific issue should be displayed.
                    if (isset($_GET['issueid'])) { // And it's ID can be accessed:
                        if (isset($_POST['issuestate'])) mysql_query("UPDATE Issues SET IssueState='{$_POST['issuestate']}' WHERE IssueID='{$_GET['issueid']}'"); // Update the issue state if the form has been submitted.
                        $row = mysql_fetch_array(mysql_query("SELECT * FROM Issues WHERE IssueID='{$_GET['issueid']}'")); // A query to select the issue that will be shown.                                       
                        echo '<br><div class="issue border"><form method="post" enctype="multipart/form-data"><h4> ' . $row["IssueName"] . '</h4>' . $row["IssueDescription"] . '<br><br><select name="issuestate"><option value="'.$row["IssueState"].'">'.$row["IssueState"].'</option>'; // The actual issue element with the state, description and name.
                        if ("Unassigned" != $row["IssueState"]) echo '<option value="Unassigned">Unassigned</option>'; // The following series of 'If' statements ensure the default issue state is the current one and that it is not shown twice.
                        if ("Started" != $row["IssueState"]) echo '<option value="Started">Started</option>';
                        if ("Delayed" != $row["IssueState"]) echo '<option value="Delayed">Delayed</option>';
                        if ("Reviewed" != $row["IssueState"]) echo '<option value="Reviewed">Reviewed</option>';
                        if ("Completed" != $row["IssueState"]) echo '<option value="Completed">Completed</option>';
                        echo '</select><br><input type="submit" value="Update"/></form></div>'; // The update button that allows the issue's state to be changed.
                    }
                } else if ($_GET['issues'] == 2) { // If the issue creation pane should be shown.
                    echo '</div>';
                    require_once 'create_issue.php'; // Load the create issue form and process.      
                }
            } else {
                echo '<h4 style="margin-bottom: 0">ISSUES</h4>'; // The issue selection heading title.
                displayTabledData('Issues', $_GET['revisionid'], 'IssueRevisionID', 'IssueID', 'issues=1&issueid='); // Displays the issue selection pane.
                echo '<input type="submit" value="New Issue" onclick="window.location=\''.currentURL().'&issues=2\';"/>'; // An input element button to create a new project milestone-revision's issue.
            }
        } else {
            echo '<h4 style="margin-bottom: 0">REVISIONS</h4>'; // The revision selection heading title.
            displayTabledData('Revisions', $_GET['milestoneid'], 'RevisionMilestoneID', 'RevisionID', 'revisionid='); // Displays the revision selection pane.
            echo '<input type="submit" value="New Revision" onclick="window.location=\''.currentURL().'&revisions=2\';"/>'; // An input element button to create a new project milestone's revision.
        }
        echo '</div>';
    } else {
        displayTabledData('Milestones', $_GET['view'], 'MilestoneProjectID', 'MilestoneID', 'milestoneid='); // Displays the milestone selection pane.
    }
} else if ($_GET['milestones'] == 2) { // If the milestone creation pane should be shown.
    echo '</div>';
    require_once 'create_milestone.php'; // Load the create milestone form and process.     
} else if ($_GET['code'] == 2) { // If the project master revision code upload selection pane should be shown.
    require_once 'upload_code.php'; // Load the upload code form and process.
}
?>