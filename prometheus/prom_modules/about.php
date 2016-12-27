<?php

$dbTriConnection = databaseConnection();


/*
 * The Following Snippet is to insert the module table into the mysqli table. 
 */



require_once dirname(dirname(__FILE__)) . '/SunLibraryModule.php';

class about extends SunLibraryModule {

    const ModuleDescription = "About Us Module";
    const ModuleAuthor = "Sunsetcoders Development Team";
    const ModuleVersion = "0.1";

    function __construct($dbConnection) {
        parent::__construct($dbConnection);
    }

    public function about() {

        echo '<form method="POST" action="?id=About&&moduleID=UpdateContent">';

        if ($stmt = $this->objDB->prepare("SELECT aboutContent FROM about WHERE aboutID=1 ")) {

            $stmt->execute();
            $stmt->bind_result($aboutContent);
            $stmt->fetch();

            echo '<table border=0 cellpadding=20>';
            echo '<tr><td><h2>Content: </h2></td></tr>';
            echo '<tr><td><textarea cols=100 rows=10 name="contentMatter">' . $aboutContent . '</textarea></td></tr>';
            echo '<tr><td><input type="submit" name="submit" value="Update"></td></tr>';
        }
        echo '</form>';
    }

    public function updateContent() {

        $contentDescription = filter_input(INPUT_POST, 'contentMatter');

        $stmt = $this->objDB->prepare("UPDATE about SET aboutContent=? WHERE aboutID=1");
        $stmt->bind_param('s', $contentDescription);

        if ($stmt === false) {
            trigger_error($this->objDB->error, E_USER_ERROR);
        }

        $status = $stmt->execute();

        if ($status === false) {
            trigger_error($stmt->error, E_USER_ERROR);
        }
        echo '<font color=black><b>Content Information Updated <br><br> Please Wait!!!!<br>';
        echo '<meta http-equiv="refresh" content="1;url=?id=About">';
    }

    public function callToFunction() {

        echo '<div class="moduleSpacer"></div>';

        echo '<div class="body-content">';

        if ($stmt = $this->objDB->prepare("SELECT aboutContent FROM about WHERE aboutID=1")) {

            $stmt->execute();
            $stmt->bind_result($aboutContent);
            $stmt->fetch();

            echo nl2br($aboutContent);
        }

        echo '</div>';
    }

    protected function assertTablesExist() {

        $val = mysqli_query($this->objDB, 'select 1 from `about` LIMIT 1');

        if ($val !== FALSE) {
            
        } else {
            $createTable = $this->objDB->prepare("CREATE TABLE about (aboutID INT(11) AUTO_INCREMENT PRIMARY KEY, aboutContent VARCHAR(5000) NOT NULL)");
            $createTable->execute();
            $createTable->close();

            $createRow = $this->objDB->prepare("INSERT INTO `about` (`aboutContent`) VALUES ('Example Text')");
            $createRow->execute();
            $createRow->close();
        }
    }

}
