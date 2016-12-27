<style>
    #triWindow-Background { width: 100%; height: 500px;  }
    .triWindow-content { padding: 10px; padding-top: 50px; } 
    .triWindow 
    { 
        float: left; 
        background-color: #fff; 
        text-align: center; 
        height: 400px; 
        position: relative; 
        margin-left: 10px; 
        margin-right: 10px; 
        width: 28%; 
        color: #000; 
        padding: 15px; 
        font-size: 10pt;
    }

    .triWindow img.readmore
    {
        position: absolute;
        right: 0;
        bottom: 0;
    }
</style>

<?php

require_once dirname(dirname(__FILE__)) . '/SunLibraryModule.php';

class triwindow extends SunLibraryModule {

    const ModuleDescription = "Tri-window Screen, Images with Description underneath Module";
    const ModuleAuthor = "Sunsetcoders Development Team";
    const ModuleVersion = "0.1";

    function __construct($dbConnection) {
        parent::__construct($dbConnection);
    }


    public function triwindow() {

        if ($stmt = $this->objDB->prepare("SELECT windowOne, windowTwo, windowThree FROM triWindow WHERE triWindowID=1")) {

            $stmt->execute();
            $stmt->bind_result($windowOne, $windowTwo, $windowThree);
            $stmt->fetch();

            echo '<br><br><table cellpadding=10 cellspacing=0 width=50%>';
            echo '<tr><td colspan=2><h2>Tri-Window Panel</h2></td></tr>';
            echo '<tr><td colspan=2></td></tr>';
            echo '<tr><td colspan=2 class="headerMenu">Left Window</td></tr>';
            echo '<tr><td>' . $windowOne . '</td><td width=75><a href="?id=Triwindow&&moduleID=editContent&&ContentID=windowOne">edit</a></td></tr>';
            echo '<tr><td colspan=2 class="headerMenu">Middle Window</td></tr>';
            echo '<tr><td>' . $windowTwo . '</td><td width=75><a href="?id=Triwindow&&moduleID=editContent&&ContentID=windowTwo">edit</a></td></tr>';
            echo '<tr><td colspan=2 class="headerMenu">Right Window</td></tr>';
            echo '<tr><td>' . $windowThree . '</td><td width=75><a href="?id=Triwindow&&moduleID=editContent&&ContentID=windowThree">edit</a></td></tr>';
            echo '</table>';
        }
    }

    public function editContent() {

        $contentCode = filter_input(INPUT_GET, "ContentID");

        $query = "SELECT $contentCode FROM triWindow WHERE triWindowID=1 ";

        echo '<form method="POST" action="?id=TriWindow&&moduleID=UpdateContent">';
        echo '<input type="hidden" name="contentCode" value="' . $contentCode . '">';

        if ($stmt = $this->objDB->prepare($query)) {

            $stmt->execute();
            $stmt->bind_result($contentCode);
            $stmt->fetch();

            echo '<table border=0 cellpadding=20>';
            echo '<tr><td><h1>Content: </h1></td></tr>';
            echo '<tr><td><textarea cols=100 rows=10 name="contentMatter">' . $contentCode . '</textarea></td></tr>';
            echo '<tr><td><input type="submit" name="submit" value="Update"></td></tr>';
        }
        echo '</form>';
    }

    public function updateContent() {

        $contentDescription = filter_input(INPUT_POST, 'contentMatter');
        $contentCode = filter_input(INPUT_POST, 'contentCode');

        $stmt = $this->objDB->prepare("UPDATE triwindow SET $contentCode=? WHERE triwindowID=1");
        $stmt->bind_param('s', $contentDescription);

        if ($stmt === false) {
            trigger_error($this->objDB->error, E_USER_ERROR);
        }

        $status = $stmt->execute();

        if ($status === false) {
            trigger_error($stmt->error, E_USER_ERROR);
        }
        echo '<font color=black><b>Content Information Updated <br><br> Please Wait!!!!<br>';
        echo '<meta http-equiv="refresh" content="1;url=?id=TriWindow">';
    }

    public function callToFunction() {

        echo '<div class="moduleSpacer"></div>';
        if ($stmt = $this->objDB->prepare("SELECT windowOne, windowTwo, windowThree FROM triWindow WHERE triWindowID=1")) {

            $stmt->execute();
            $stmt->bind_result($windowOne, $windowTwo, $windowThree);
            $stmt->fetch();
            ?>
            <div id="triWindow-Background">
                <div class="body-content">

                    <div class="triWindow"><?php echo nl2br($windowOne); ?></div>
                    <div class="triWindow"><?php echo nl2br($windowTwo); ?></div>
                    <div class="triWindow"><?php echo nl2br($windowThree); ?></div>

                </div>
            </div>
            <div class="moduleSpacer"></div>
            <?php
        }
    }

    protected function assertTablesExist() {


        $val = mysqli_query($this->objDB, 'select 1 from `triwindow` LIMIT 1');

        if ($val !== FALSE) {
            
        } else {
            $createTable = $this->objDB->prepare("CREATE TABLE triwindow (triwindowID INT(11) AUTO_INCREMENT PRIMARY KEY, windowOne VARCHAR(3000) NOT NULL, windowTwo VARCHAR(3000) NOT NULL, windowThree VARCHAR(3000) NOT NULL)");
            $createTable->execute();
            $createTable->close();

            $createRecord = $this->objDB->prepare("INSERT INTO `triwindow` (`windowOne`, `windowTwo`, `windowThree`) VALUES ('Example Body Content', 'Example Body Content', 'Example Body Content')");
            $createRecord->execute();
            $createRecord->close();

            $currentfile = file_get_contents("../css/style.css");

            $myfile = fopen("../css/style.css", "w") or die("Unable to open file!");
            $newContent = "#triWindow-Background { width: 100%; height: 400px; } .triWindow-content { padding: 10px; padding-top: 50px;} .triWindow { float: left; background-color: #fff; margin-left: 10px; margin-right: 10px; width: 27%; color: #000; border-left: 6px chocolate solid; padding: 15px; font-size: 8pt;}";
            $newBody = $currentfile . nl2br($newContent);
            fwrite($myfile, $newBody);
            fclose($myfile);
        }
    }

}
