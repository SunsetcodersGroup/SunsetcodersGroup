<?php
echo '<link rel="stylesheet" type="text/css" href="prometheus.css">';
foreach (glob("prom_content/*.php") as $filename) {
    require_once $filename;
}

$dbConnection = databaseConnection();

$authClass = new prometheus($dbConnection);
$authClass->landingPage();

class prometheus {

    protected $dbConnection;
    private $setPostID;
    private $setGetID;

    function __construct($dbConnection) {

        $this->dbConnection = $dbConnection;

        $this->setPostID = filter_input(INPUT_POST, 'id');
        $this->setGetID = filter_input(INPUT_GET, 'id');

        if ($this->setGetID == "processLogin") {
            processLogin();
            exit();
        }

        if (!is_admin()) {
            loginScreen();
            exit();
        }
    }

    public function landingPage() {

        echo '<table border=0 width=100% height=100% cellspacing=0 cellpadding=0>';
        echo '<tr><td class="profileClass" colspan=2><img src="Images/logo.png" width=200px></td></tr>';
        echo '<tr><td class="dashboard">';

        echo '<table width=100% cellspacing=0 cellpadding=0>';
        echo '<tr><td class="headerMenu"><a href="?id=Pages">PAGES</a></td></tr>';
        $pageRef = $this->dbConnection->prepare("SELECT pageID, pageName FROM pages");
        $pageRef->execute();
        $pageRef->bind_result($pageID, $pageName);

        while ($checkRow = $pageRef->fetch()) {
            echo '<tr><td class="subHeaderMenu"><a class="setPageName" href="?id=Pages&&moduleID=EditPage&&pageID=' . $pageID . '">' . $pageName . '</a></td></tr>';
        }
        echo '</table>';

        echo '<table width=100% cellspacing=0 cellpadding=0>';
        echo '<tr><td class="headerMenu"><a href="?id=Modules">MODULES</a></td></tr>';
        $modRef = $this->dbConnection->prepare("SELECT settingsName, settingsFilename FROM settings");
        $modRef->execute();
        $modRef->bind_result($settingsName, $settingsFilename);

        while ($checkRow = $modRef->fetch()) {
            $tempValue = explode('.', $settingsFilename);
            $setModuleName = $tempValue [0];
            echo '<tr><td class="subHeaderMenu"><a class="setPageName" href="?id=' . $setModuleName . '">' . $settingsName . '</a></td></tr>';
        }
        echo '</table>';

        echo '<table width=100% cellspacing=0 cellpadding=0>';
        echo '<tr><td class="headerMenu">SETTINGS</td></tr>';
        echo '<tr><td class="subHeaderMenu"><a class="setPageName" href="?id=Media"> Media</a></td></tr>';
        echo '<tr><td class="subHeaderMenu"><a class="setPageName" href="?id=Content"> Content Editor</a></td></tr>';
        echo '<tr><td class="subHeaderMenu"><a class="setPageName" href="?id=Users"> Users</a></td></tr>';
        echo '<tr><td class="subHeaderMenu"><a class="setPageName" href="?id=Settings&&moduleID=StyleSheet"> Stylesheet</td></tr>';
        echo '<tr><td class="subHeaderMenu"><a class="setPageName" href="?id=Settings&&moduleID=Menu"> Menus</td></tr>';
        echo '</table>';

        echo '</td><td class="bodyContent">';

        echo $this->switchMode();

        echo '</td></tr>';
        echo '</table>';
    }


    public function defaultScreen() {
        echo '<table>';
        echo '<tr><td>Page Stats</td></tr>';
        echo '</table>';
    }

    public function switchMode() {

        $moduleID = filter_input(INPUT_GET, 'moduleID');
        $localAction = NULL;

        if (isset($this->setPostID)) {
            $localAction = $this->setPostID;
        } elseif (isset($this->setGetID)) {
            $localAction = urldecode($this->setGetID);
        }

        $lowerName = strtolower($localAction);
        $checkName = strtoupper($localAction);
        $lowerModule = strtolower($moduleID);

        Switch (strtoupper($localAction)) {

            case "USERS":
                if ($moduleID) {
                    $moduleID();
                } else {
                    showUserScreen();
                }
                break;
            case "LOGOUT" :
                session_destroy();
                header("LOCATION:index.php");
                exit();
            case "PROCESSLOGIN" :
                $this->processLogin();
                break;
            case "SETTINGS" :
                if ($moduleID) {
                    $moduleID();
                }
                break;
            case "MODULES" :
                modules();
                break;
            case "MEDIA":
                media();
                break;
            case "CONTENT":
                if ($moduleID) {
                    $content = new content_editor($this->dbConnection);
                    $content->$moduleID();
                } else {
                    $content = new content_editor($this->dbConnection);
                    $content->content_editor();
                }
                break;
            case "PAGES" :
                if ($moduleID) {
                    $moduleID();
                } else {
                    pages();
                }
                break;

            case (NULL) :
                $this->defaultScreen();
                break;

            case ($checkName) :
                include_once ("prom_modules/" . $lowerName . '.php');

                if ($moduleID) {
                    $moduleClass = new $lowerName($this->dbConnection);
                    $moduleClass->$lowerModule();
                } elseif (!isset($moduleID)) {
                    $moduleClass = new $lowerName($this->dbConnection);
                    $moduleClass->$checkName();
                }
                break;
        }
    }

}
