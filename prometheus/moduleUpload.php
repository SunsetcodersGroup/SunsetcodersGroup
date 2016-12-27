<?php
/**
 * Unload module
 *
 * @author          Andrew Jeffries
 * @version         1.0.0               2016-12-27 14:06:57 SM:  Prototype - added header.
 */

if ($handle = opendir('prom_modules')) {
    while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != "..") {

            $moduleName = ucfirst(substr($entry, 0, - 4));

            include ('prom_modules/' . $entry);
            checkForRow($moduleName, $moduleName::ModuleAuthor, $moduleName::ModuleVersion, $moduleName::ModuleDescription);
        }
    }
    closedir($handle);
}

/**
 * Row checking function.
 *
 * @param string $fileName The file to check.
 * @param string $author The author of the file.
 * @param string $version The file version.
 * @param string $description The file description.
 * @return void
 */
function checkForRow($fileName, $author, $version, $description) {

    // SM:  TO DO:  Use the database module to get this connection, or include
    //              a DB object in the parameters for this function.
    $servername = "120.146.222.154";
    $username = "root";
    $password = "Aort101ms!";
    $dbName = "sunLibrary";

    $conn = new mysqli($servername, $username, $password, $dbName);
    $result = $conn->query("SELECT * FROM modules WHERE moduleName='$fileName'");

    if ($result->num_rows > 0) {

    } else {

        $insertUsersRow = $conn->prepare("INSERT INTO `modules` (moduleID, moduleName, moduleAuthor, moduleVersion, moduleDescription) VALUES (NULL, '$fileName', '$author', '$version', '$description')");
        $insertUsersRow->execute();
        $insertUsersRow->close();
    }

}

