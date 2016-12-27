<?php

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

function checkForRow($fileName, $author, $version, $description) {

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

