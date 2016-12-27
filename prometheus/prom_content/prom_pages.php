<style>
    .brownOut
    {
        background-color: #333;
        color: #fff;
        height: 30px;
    }
    
    .show-page-name
    {
        padding: 5px;
        background-color: #fff;
        color: #000;
    }
</style>

<?php

$dbConnection = databaseConnection();

function pages() {

    global $dbConnection;

    echo '<table border=1 cellpadding=10 cellspacing=5 width=100%>';
    echo '<tr class="brownOut"><td>PageName</td><td width=10%>Add New Page</td></tr>';
    echo '<tr><td width=30%>';
    
    echo '<table>';
    $stmt = $dbConnection->prepare("SELECT pageID, pageName FROM pages");
    $stmt->execute();

    $stmt->bind_result($pageID, $pageName);

    while ($checkRow = $stmt->fetch()) {

        echo '<tr bgcolor=white style="cursor: pointer;"  onclick="location.href=\'?id=Pages&&moduleID=EditPage&&pageID=' . $pageID . '\'"><td width="200" class="show-page-name">' . $pageName . '</td></tr>';
    }
    echo '</table>';
    
    echo '</td><td>';
    
    echo '<form method="POST" action="?id=Pages&&moduleID=uploadPage">';
    echo '<table border=0 width=100% cellpadding=10>';
    echo '<tr><td><b>Page Name</td><td><input type="text" name="pageName" size=60 required></td></tr>';
    echo '<tr><td colspan=2><input type="submit" name="Submit" value="Submit"></td></tr>';
    echo '</table>';
    echo '</form>';
    
    echo '</td></tr>';
    echo '</table>';
    
}

function uploadPage() {

    global $dbConnection;

    $pageName = filter_input(INPUT_POST, 'pageName');
    $pageRow1 = $pageRow2 = $pageRow3 = $pageRow4 = $pageRow5 = $pageRow6 = $pageRow7 = $pageRow8 = $pageRow9 = $pageRow10 = '';

    $stmt = $dbConnection->prepare("INSERT INTO pages (pageName, pageRow1, pageRow2, pageRow3, pageRow4, pageRow5, pageRow6, pageRow7, pageRow8, pageRow9, pageRow10) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
    $stmt->bind_param('sssssssssss', $pageName, $pageRow1, $pageRow2, $pageRow3, $pageRow4, $pageRow5, $pageRow6, $pageRow7, $pageRow8, $pageRow9, $pageRow10);
    $status = $stmt->execute();

        if ($status === false) {
        trigger_error($stmt->error, E_USER_ERROR);
    }
    echo 'You have successfully added a new Page. <br><br><br>Please Wait.....<br>';
    echo '<meta http-equiv="refresh" content="3;url=?id=Pages">';
}

function updatePage() {

    global $dbConnection;

    $setPageID = filter_input(INPUT_POST, 'pageID');
    $getDescription = filter_input(INPUT_POST, 'area2');

    $stmt = $dbConnection->prepare("UPDATE pages SET pageDescription=? WHERE pageID=?");
    $stmt->bind_param('si', $getDescription, $setPageID);

    if ($stmt === false) {
        trigger_error($this->dbConnection->error, E_USER_ERROR);
    }

    $status = $stmt->execute();

    if ($status === false) {
        trigger_error($stmt->error, E_USER_ERROR);
    }
    echo '<font color=black><b>Page Updated <br><br> Please Wait!!!!<br>';
    echo '<meta http-equiv="refresh" content="1;url=?id=Pages">';
}

function updateRow() {

    /*
     * Bring down the mysqli Connections 
     */
    global $dbConnection;
    global $secondaryDBConnection;

    /*
     * Set Values for use with in this function.
     */
    $setPageID = filter_input(INPUT_POST, 'pageID');
    $getModuleCode = '[' . filter_input(INPUT_POST, 'addModule') . ']';
    $getRowID = filter_input(INPUT_POST, 'rowID');
    $setRowCode = "pageRow" . $getRowID;

    #if Clear Value is set remove the data from the page.
    if ($getModuleCode == '[Clear]') {
        $getModuleCode = '';
    }

        $result = $conn->query("SELECT * FROM modules WHERE moduleName='$fileName'");

    if ($result->num_rows > 0) {

    } else {

    }
    
    
    if (empty($getModuleCode)) {

        $deleteRow = $dbConnection->prepare("DELETE FROM page_settings WHERE pageID='$setPageID' AND rowID='$getRowID' ");
        $deleteRow->execute();
        $deleteRow->close();
        
    } else {

        $SettingRef = $secondaryDBConnection->prepare("SELECT moduleCode FROM page_settings WHERE pageID='$setPageID' AND rowID='$getRowID' ");
        $SettingRef->execute();
        $SettingRef->bind_result($moduleCode);
        $SettingRef->fetch();

        if ($SettingRef) {

            if (!$moduleCode) {

                $createRow = $dbConnection->prepare("INSERT INTO page_settings (pageID, rowID, moduleCode) VALUES ('$setPageID', '$getRowID', '$getModuleCode')");
                $createRow->execute();
                $createRow->close();
                
            } else {
              
                $updateRow = $dbConnection->prepare("UPDATE page_settings SET moduleCode=? WHERE pageID=? AND rowID=?");
                $updateRow->bind_param('sii', $getModuleCode, $setPageID, $getRowID);

                if ($updateRow === false) {
                    trigger_error($dbConnection->error, E_USER_ERROR);
                }

                $status = $updateRow->execute();

                if ($status === false) {
                    trigger_error($updateRow->error, E_USER_ERROR);
                }
                
                echo 'RowCode: '.$setRowCode.'<br>';
                
                $updatePageData = $dbConnection->prepare("UPDATE pages SET $setRowCode=? WHERE pageID=?");
                $updatePageData->bind_param('si', $getModuleCode, $setPageID);

                if ($updatePageData === false) {
                    trigger_error($dbConnection->error, E_USER_ERROR);
                }

                $status2 = $updatePageData->execute();

                if ($status2 === false) {
                    trigger_error($updatePageData->error, E_USER_ERROR);
                }
                
            }
        }

    }

    echo '<font color=black><b>Page Row Updated <br><br> Please Wait!!!!<br>';
   echo '<meta http-equiv="refresh" content="1;url=?id=Pages&&moduleID=EditPage&&pageID=' . $setPageID . '">';
}

function editpage() {

    global $dbConnection;
    global $secondLink;

    $setPageID = filter_input(INPUT_GET, 'pageID');


    echo '<table border=0 cellpadding=10 width=50% cellspacing=0>';

    echo '<tr><td colspan=4><h2>Page Panel</h2></td></tr>';
    echo '<tr class="headerMenu"><td>Row No. </td><td>Module Code</td><td>Modules</td><td>Content Editor</td></tr>';

    if ($stmt = $dbConnection->prepare("SELECT pageRow1, pageRow2, pageRow3, pageRow4, pageRow5, pageRow6, pageRow7, pageRow8, pageRow9, pageRow10 FROM pages WHERE pageID=?")) {

        $stmt->bind_param("i", $setPageID);
        $stmt->execute();

        $stmt->bind_result($pageRow1, $pageRow2, $pageRow3, $pageRow4, $pageRow5, $pageRow6, $pageRow7, $pageRow8, $pageRow9, $pageRow10);
        $stmt->fetch();

        for ($x = 1; $x <= 10; $x++) {

            $setPageCode = 'pageRow' . $x;
            echo '<tr><td width=70>' . $x . ': </td><td width=150>' . $$setPageCode . '</td><td>';

            echo '<form method="POST" action="?id=Pages&&moduleID=UpdateRow">';
            echo '<input type="hidden" name="pageID" value="' . $setPageID . '">';
            echo '<input type="hidden" name="rowID" value="' . $x . '">';

            echo '<select name="addModule" onchange="this.form.submit()">';
            if ($$setPageCode) {
                echo '<option>' . $$setPageCode . '</option>';
            }
            echo '<option>Select Module</option>';
            $oneRef = $secondLink->prepare("SELECT settingsName FROM settings");
            $oneRef->execute();

            $oneRef->bind_result($settingsName);

            while ($checkRow = $oneRef->fetch()) {

                echo '<option>' . $settingsName . '</option>';
            }
            $oneRef->close();
            echo '<option>Clear</option>';
            echo '</select>';
            echo '</form>';

            echo '</td><td>';


            echo '<form method="POST" action="?id=Pages&&moduleID=UpdateRow">';
            echo '<input type="hidden" name="pageID" value="' . $setPageID . '">';
            echo '<input type="hidden" name="rowID" value="' . $x . '">';

            echo '<select name="addModule" onchange="this.form.submit()">';
            $twoRef = $secondLink->prepare("SELECT contentCode FROM content_editor");
            $twoRef->execute();

            $twoRef->bind_result($contentCode);
            echo '<option>Select From Editor</option>';
            while ($checkRow = $twoRef->fetch()) {

                echo '<option>' . substr(ucfirst($contentCode), 1, -1) . '</option>';
            }
            $twoRef->close();
            echo '</select>';
            echo '</form>';

            echo '</td></tr>';
        }
    }



    echo '</table>';
    echo '</form>';
}

function have_row($pageID, $rowNumber) {

    global $secondLink;

    echo '<table width=60% cellpadding=10 border=1>';
    $x = 1;

    if ($stmt = $secondLink->prepare("SELECT pagesetID, pageID, rowID, rowCount, pagesetColumn1, pagesetColumn2, pagesetColumn3, pagesetColumn4 FROM page_settings WHERE pageID=? AND rowID=?")) {

        $stmt->bind_param("ii", $pageID, $rowNumber);
        $stmt->execute();

        $stmt->bind_result($pagesetID, $pageID, $rowID, $rowCount, $pagesetColumn1, $pagesetColumn2, $pagesetColumn3, $pagesetColumn4);
        $stmt->fetch();

        $setArray = array($pagesetColumn1, $pagesetColumn2, $pagesetColumn3, $pagesetColumn4);

        echo '<td width=50>' . $rowNumber . '</td><td width=150>Rows: ';

        echo '<form method="POST" action="index.php?id=RowCounter">';
        echo '<select onchange="this.form.submit()">';

        if ($rowCount) {
            echo '<option>' . $rowCount . '</option>';
        }

        if ($rowCount != 1) {
            echo '<option>1</option>';
        }

        if ($rowCount != 2) {
            echo '<option>2</option>';
        }

        if ($rowCount != 3) {
            echo '<option>3</option>';
        }

        if ($rowCount != 4) {
            echo '<option>4</option>';
        }

        echo '</select>';
        echo '</form>';

        echo '</td><td>';
        foreach ($setArray as $value) {

            if ($value) {
                echo '<td>' . $value . ' <a class="editpage" href="index.php?id=Pages&&moduleID=checkEdit&&pageID=' . $pageID . '&&pageCode=' . $value . '">edit</a></td>';
            }
        }

        unset($setArray);

        echo '</td></tr>';
    } else {
        echo 'No Connection!';
    }


    echo '</table>';
}
