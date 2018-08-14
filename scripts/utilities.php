<?php
/* Displays a tabular formatting of data from the given table for the rows that match the given constraints. */
function displayTabledData($sqlTable, $projectID, $sqlID, $queryID, $getParam) {
    echo "<table class='border'>"; // Begin the table opening tag, to format as required.
    $resource = mysql_query("SELECT * FROM $sqlTable WHERE $sqlID='{$projectID}'"); // A query that returns all the rows that abide by the given constraints for the given table.
    if (mysql_num_rows($resource) == 0) { // If there are no rows in this table:
        echo "There are no entities in this table."; // Output an error message explaining the scenario to the user.
    } else {
        /* Sets up the table headings. */
        for ($count = 1; $count < mysql_num_fields($resource); $count++) { // Iterates through every column header
            $field_info = mysql_fetch_field($resource, $count);
            $field_name = str_replace(substr($sqlTable, 0, -1), '', $field_info->name);
            $field_name = str_replace(substr($sqlTable, 0, -2), '', $field_name); // Ignoring the first column that is always an ID.
            echo "<th class='$count'>{$field_name}</th>"; // Output the now setup table headings.
        }
        /* Fills the table with the requested data. */
        $columnrequired = array_fill(0, 20, true); // Creates a boolean array, to determine which columns match the given constraints.
        while ($row = mysql_fetch_array($resource)) { // While the query is still returning a row.
            $url = appendGetParameter($getParam.$row[$queryID]); // Append the given GET parameter to the URL.
            echo "<tr onclick='document.location = \"$url\"' class='hoverer'>"; // Output the tables row encapsulating element.
            foreach($row as $field => $value) { // For each column in the row, with the given values.
                if (!is_numeric($field)) continue; // If the field's name is not numeric, ignore it to avoid considering a field twice.
                if (strpos(mysql_field_name($resource, $field), "ID")) $columnrequired[$field] = false; // If a column is for an ID, it is not required.
                echo "<td class='$field'>$value</td>"; // Output the table cells value.
            }
        }
        echo "<script>function removeElementsByClass(className) {var elements = document.getElementsByClassName(className);while(elements.length > 0) elements[0].parentNode.removeChild(elements[0]);}";
        foreach($columnrequired as $field => $value) if (!$value) echo "removeElementsByClass('".$field."');";
        echo "</script>"; // Outputs a javascript script to remove all columns that were not deemed to be required.
    }
    echo "</table>";
}

/* Returns whether or not the given file exists in the given directory, as a boolean. */
function is_in_dir($file, $directory) {
    $directory = realpath($directory); // Canonicalize the directory path.
    $parent = realpath($file); // Canonicalize the file path.
    while ($parent) { // Loop indefinitely, until a return or break.
        if ($directory == $parent) return true; // The file is in the directory. (Implied as the file's parent directory is the given directory.)
        if ($parent == dirname($parent)) break; // If the file's parent is correctly a directory, break to prevent an infinite loop.
        $parent = dirname($parent); // Ensure the file's parent is a directory path, to perform an exhaustive double check.
    }
    return false; // The loop did not confirm otherwise, so the given file is definitely not in the given directory.
}

/* A function to delete both folders and files from the server's filesystem */
function deleteEntity($path) {
    if (is_dir($path)) { // If the given String path represents a directory entity.
        foreach (array_diff(scandir($path), array('.', '..')) as $file) { // Iterate/scan through the contents of the folder.
            deleteEntity(realpath($path).'/'.$file); // Recursively delete everything inside the folder.
       }
       return rmdir($path); // Now that the directory contents have been deleted, delete the directory itself, returning the success state.
    } else if (is_file($path)) { // If the given String path represents a file entity.
        return unlink($path); // Deletes the file and returns the success state.
    }
    return false; // The path is not a directory or file, so the request fails, return a boolean in a failure state.
}

/* Appends a variable to the URL as a GET parameter */
function appendGetParameter($param) {
    $url = currentURL(); // Request the current page URL.
    $query = parse_url($url, PHP_URL_QUERY); // Return the query/variables at the end of the URL.
    if ($query) { // If a query or variables already exist
        return $url.'&'.$param; // Appends the given parameter variable to the URL and then returns the String.
    } else {
        return $url.'?'.$param; // Begin a new query with the given parameter variable and then returns the String.
    }
}

/* Returns the canonical URL for the current page. */
function currentURL() {
    $pageURL = 'http'; // Define the page's URL String, which always starts with http.
    if ($_SERVER["HTTPS"] == "on") $pageURL .= "s"; // If the page uses the secure HTTP protocol, then append an s to the URL.
    $pageURL .= "://"; // Append the required '://' standard URL format.
    if ($_SERVER["SERVER_PORT"] != "80") { // If the server port is not set to 80, the default.
        $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; // Append the domain, port and the Uniform Resource Identifier, to complete the URL.
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]; // Appends the domain and Uniform Resource Identifier to complete the URL.
    }
    return $pageURL; // Returns the generated URL String.
}

/* Recursively copies the contents of a source directory into a newly created destination directory. */
function recurseCopy($source, $destination) { 
    $dir = opendir($source); // Opens the source directory, so it may be copied from.
    @mkdir($destination); // Makes the destination directory, with all errors suppressed.
    while(false !== ($file = readdir($dir))) {  // While there are still files to be read in the directory.
        if ($file != '.' && $file != '..') { // If the file is not the current or parent directory.
            if (is_dir($source.'/'.$file)) {  // If the current entity is a directory.
                recurseCopy($source.'/'.$file, $destination.'/'.$file); // Recursively treat it the same as the source directory.
            } else {
                copy($source.'/'.$file, $destination.'/'.$file); // Copy the file with the PHP system library, in a non-recursive manner.
            }
        }
    } 
    closedir($dir); // Close the directory, now that the copying has been completed.
} 
?>