<?php


require_once dirname(dirname(__FILE__)) . '/SunLibraryModule.php';

class dualwindow extends SunLibraryModule {

    const ModuleDescription = "Dual Window Module";
    const ModuleAuthor = "Sunsetcoders Development Team";
    const ModuleVersion = "0.1";

    function __construct($dbConnection) {
        parent::__construct($dbConnection);
    }


    public function dualwindow() {

        echo '<table border=0 cellpadding=10 cellspacing=0 width=50%>';
        echo '<tr><td><h2>Dual Window Panel</h2></td></tr>';
        echo '<tr class="headerMenu"><td>Left Side</td><td>Right Side</td></tr>';
        echo '<form method="POST" action="?id=DualWindow&&moduleID=UpdateContent">';
        
        if ($stmt = $this->objDB->prepare("SELECT leftSide, rightSide FROM dualwindow WHERE dualwindowID=1 ")) {

            $stmt->execute();
            $stmt->bind_result($leftSide, $rightSide);
            $stmt->fetch();

            echo '<tr>'
            . '<td><textarea cols=100 rows=10 name="leftContent">' . $leftSide . '</textarea></td>'
            . '<td><textarea cols=100 rows=10 name="rightContent">' . $rightSide . '</textarea></td></tr>';
            echo '<tr><td><input type="submit" name="submit" value="Update"></td></tr>';
        }
        echo '</form>';
    }

     public function updateContent() {

        $leftContent = filter_input(INPUT_POST, 'leftContent');
        $rightContent = filter_input(INPUT_POST, 'rightContent');

        $stmt = $this->objDB->prepare("UPDATE dualwindow SET leftSide=?, rightSide=? WHERE dualwindowID=1");
        $stmt->bind_param('ss', $leftContent, $rightContent);

        if ($stmt === false) {
            trigger_error($this->objDB->error, E_USER_ERROR);
        }

        $status = $stmt->execute();

        if ($status === false) {
            trigger_error($stmt->error, E_USER_ERROR);
        }
        echo '<font color=black><b>Content Information Updated <br><br> Please Wait!!!!<br>';
        echo '<meta http-equiv="refresh" content="1;url=?id=DualWindow">';
    }

    public function callToFunction() {
        
        if ($stmt = $this->objDB->prepare("SELECT leftSide, rightSide FROM dualwindow WHERE dualwindowID=1 ")) {

            $stmt->execute();
            $stmt->bind_result($leftSide, $rightSide);
            $stmt->fetch();
            ?>
<div class="moduleSpacer"></div>
            <div id="dualwindow-background">
                <div class="body-content">
                    <div class="leftWindow"><?php echo nl2br($leftSide); ?></div>
                    <div class="rightWindow">
                        <form method="POST" action="">
                        <div class="subHeaderMenu">Name</div>
                        <div class="padder"><input type="text" name="contactName" placeholder="enter name"></div>
                        <div></div>
                        <div class="subHeaderMenu">Email</div>
                        <div class="padder"><input type="text" name="contactName" placeholder="enter name"></div>
                        <div></div>
                        <div class="subHeaderMenu">Message</div>
                        <div class="padder"><textarea rows="10" cols="60" name="contactContent" placeholder="enter message"></textarea></div>
                        <div><input type="Submit" name="Submit" value="SEND"></div>
                        </form>
                    </div>
                </div>
            </div>

            <?php
        }
    }

        protected function assertTablesExist() {

$val = mysqli_query($this->objDB, 'select 1 from `dualwindow` LIMIT 1');

if ($val !== FALSE) {
    
} else {
    $createTable = $this->objDB->prepare("CREATE TABLE dualwindow (dualwindowID INT(11) AUTO_INCREMENT PRIMARY KEY, leftSide VARCHAR(1000) NOT NULL, rightSide VARCHAR(1000) NOT NULL)");
    $createTable->execute();
    $createTable->close();

    $createRow = $this->objDB->prepare("INSERT INTO `dualwindow` (`leftSide`, `rightSide`) VALUES ('Example Content', 'Example Content')");
    $createRow->execute();
    $createRow->close();
}
    }
    
}
