<style>

    .moduleBox
    {
        margin-top: 30px;
        margin-left: 30px;
        float: left;
        width: 400px;
        border: 2px #999999 solid;
        padding: 20px;
        height: 150px;
    }

    a.rightLink
    {
        float: right;

    }
</style>

<?php

function appearence() {
    ?>

    <div>

        Appearence List

    </div>

    <?php
}

function media() {
    echo 'Media Part';
}

function stylesheet() {

    $styleSheet = file_get_contents('../css/style.css');
    ?>
    <form method="POST" action="?id=Settings&&moduleID=updateStylesheet">
        <div><textarea name="newStyleSheet" style="width: 80%; height: 600px; frameborder: 0; resize: no;" >
    <?php echo strip_tags($styleSheet); ?>
            </textarea>
        </div>
        <input type="submit" name="submit" value="Update">
    </form>

    <?php
}

function updateStylesheet() {

    $updatedStyle = filter_input(INPUT_POST, "newStyleSheet");

    $file = fopen("../../css/style.css", "w");
    $cssContent = $updatedStyle;
    fwrite($file, $cssContent);
    fclose($file);

    echo '<br><br><br><center><font color=black><b>Please Wait!!!!';
    echo '<meta http-equiv="refresh" content="1;url=index.php">';
}

function showModules() {
    global $dbConnection;

    if ($handle = opendir('prom_modules/')) {
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != ".." && $entry != "auth.css") {

                $moduleName = ucfirst(substr($entry, 0, - 4));
                $lowerCaseModuleName = strtolower($entry);

                call_to_update($moduleName);

                echo '<div class="moduleBox">';

                echo '' . $moduleName . '';

                if ($stmt = $dbConnection->prepare("SELECT settingsFilename FROM settings WHERE settingsFilename=? ")) {

                    $stmt->bind_param("s", $entry);
                    $stmt->execute();

                    $stmt->bind_result($settingsFilename);
                    $stmt->fetch();

                    if ($settingsFilename) {
                        echo ' <a class="rightLink" href="?id=Modules&&moduleID=DeactivateModule&&moduleName=' . $moduleName . '">Deactivate</a>';
                    } else {
                        echo ' <a class="rightLink" href="?id=Modules&&moduleID=ActivateModule&&moduleName=' . $moduleName . '">Activate</a>';
                    }
                }
                include ('prom_modules/' . $moduleName . '.php');
                echo '<br><br><b>Description: </b><br>' . $moduleName::ModuleDescription . '<br><br>';
                echo '<b>Author:</b> ' . $moduleName::ModuleAuthor . '<br>';
                echo '<b>Version:</b> ' . $moduleName::ModuleVersion . '<br>';
                unset($stmt, $settingsFilename);
                echo '</div>';
            }
        }
        closedir($handle);
    }
}

function call_to_update($moduleName) {
 
    
}

function activeModule() {

    global $dbConnection;

    $getModuleName = filter_input(INPUT_GET, 'moduleName');
    $getFileName = filter_input(INPUT_GET, 'moduleName');
    $setFileName = $getFileName . '.php';

    $moduleActivation = $dbConnection->prepare("INSERT INTO settings (settingsName, settingsFilename) VALUES (?,?)");
    $moduleActivation->bind_param('ss', $getModuleName, $setFileName);

    $status = $moduleActivation->execute();

    echo '<br><br><br>You have successfully Activated this module<br><br><font color=black><b>Please Wait!!!!';
    echo '<meta http-equiv="refresh" content="3;url=?id=Modules">';
}

function deactivateModule() {

    global $dbConnection;

    $getModuleName = filter_input(INPUT_GET, 'moduleName');

    $deleteRef = $dbConnection->prepare("DELETE FROM settings WHERE settingsName=?");
    $deleteRef->bind_param('s', $getModuleName);

    $status = $deleteRef->execute();

    echo '<br><br><br>You have successfully Deactivated This Module<br><br><font color=black><b>Please Wait!!!!';
    echo '<meta http-equiv="refresh" content="3;url=?id=Modules">';
}

function modules() {

    $setGetModuleID = filter_input(INPUT_GET, 'moduleID');
    $setPostModuleID = filter_input(INPUT_POST, 'moduleID');

    $localAction = NULL;

    if (isset($setPostModuleID)) {
        $localAction = $setPostModuleID;
    } elseif (isset($setGetModuleID)) {
        $localAction = urldecode($setGetModuleID);
    }

    Switch (strtoupper($localAction)) {

        case "ACTIVATEMODULE" :
            activeModule();
            break;
        case "DEACTIVATEMODULE" :
            deactivateModule();
            break;
        default :
            showModules();
    }
}
