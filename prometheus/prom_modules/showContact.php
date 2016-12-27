<?php
require_once dirname(dirname(__FILE__)) . '/SunLibraryModule.php';

class showContact extends SunLibraryModule {

    const ModuleDescription = "Show Contact Screen Module";
    const ModuleAuthor = "Sunsetcoders Development Team";
    const ModuleVersion = "0.1";

    function __construct($dbConnection) {
        parent::__construct($dbConnection);
    }

    public function showContact() {

        if ($stmt = $this->objDB->prepare("SELECT topBox, topBoxBackground, topBoxBorder, frameBox, addressBox FROM showcontact WHERE showID=1")) {

            $stmt->execute();
            $stmt->bind_result($topBox, $topBoxBackground, $topBoxBorder, $frameBox, $addressBox);
            $stmt->fetch();

            echo '<form method="POST" action="?id=ShowContact&&moduleID=updateContact">';
            echo '<table border=0 cellpadding=10 cellspacing=0 width=50%>';
            echo '<tr><td colspan=3><h2>Show Contact Panel</h2></td></tr>';
            echo '<tr><td></td></tr>';
            echo '<tr class="headerMenu"><td>Top Box Content</td><td width=150>Background Color</td><td width=100>Border Width</td></tr>';
            echo '<tr><td><textarea name="topBox">' . $topBox . '</textarea></td><td><input type="text" name="topBoxBackground" value="' . $topBoxBackground . '" size=10></td><td><input type="text" name="topBoxBorder" value="' . $topBoxBorder . '" size=5></td></tr>';
            echo '<tr><td></td></tr>';
            echo '<tr class="headerMenu"><td colspan=3>Frame Box</td></tr>';
            echo '<tr><td colspan=3><textarea name="frameBox">' . $frameBox . '</textarea></td></tr>';
            echo '<tr><td></td></tr>';
            echo '<tr class="headerMenu"><td colspan=3>Address Box</td></tr>';
            echo '<tr><td colspan=3><textarea name="addressBox">' . $addressBox . '</textarea></td></tr>';
            echo '<tr><td><input type="submit" name="submit" value="Submit"></td></tr>';
            echo '</table>';
            echo '</form>';
        }
    }

    public function updateContact() {

        $topBoxContent = filter_input(INPUT_POST, 'topBoxContent');
        $topBoxBackground = filter_input(INPUT_POST, 'topBoxBackground');
        $topBoxBorder = filter_input(INPUT_POST, 'topBoxBorder');
        $frameBox = filter_input(INPUT_POST, 'frameBox');
        $addressBox = filter_input(INPUT_POST, 'addressBox');

        $stmt = $this->objDB->prepare("UPDATE showcontact SET topBox=?, topBoxBackground=?, topBoxBorder=?, frameBox=?, addressBox=? WHERE showID=1");
        $stmt->bind_param('sssss', $topBoxContent, $topBoxBackground, $topBoxBorder, $frameBox, $addressBox);

        if ($stmt === false) {
            trigger_error($this->objDB->error, E_USER_ERROR);
        }

        $status = $stmt->execute();

        if ($status === false) {
            trigger_error($stmt->error, E_USER_ERROR);
        }
        echo '<font color=black><b>Content Information Updated <br><br> Please Wait!!!!<br>';
        echo '<meta http-equiv="refresh" content="1;url=?id=ShowContact">';
    }

    public function callToFunction() {

        if ($stmt = $this->objDB->prepare("SELECT topBox, topBoxBackground, topBoxBorder, frameBox, addressBox FROM showcontact WHERE showID=1")) {

            $stmt->execute();
            $stmt->bind_result($topBox, $topBoxBackground, $topBoxBorder, $frameBox, $addressBox);
            $stmt->fetch();
            ?>
            <div id="showContact-background">
                <div class="body-content">
                    <div class="greenBox"><?php echo $topBox; ?></div>
                    <div class="googleMaps"><iframe src="<?php echo $frameBox; ?>" width="900" height="250" frameborder="0" style="border:0" allowfullscreen></iframe></div>

                    <div class="addressText"><?php echo nl2br($addressBox); ?></div>
                </div>
            </div>
            <?php
        }
    }

    protected function assertTablesExist() {

        $val = mysqli_query($this->objDB, 'select 1 from `showContact` LIMIT 1');

        if ($val !== FALSE) {
            
        } else {
            $createTable = $this->objDB->prepare("CREATE TABLE showContact (showID INT(11) AUTO_INCREMENT PRIMARY KEY, topBox VARCHAR(100) NOT NULL, topBoxBackground VARCHAR(100) NOT NULL, topBoxBorder VARCHAR(100) NOT NULL, frameBox VARCHAR(1000) NOT NULL, addressBox VARCHAR(500) NOT NULL)");
            $createTable->execute();
            $createTable->close();

            $createRow = $this->objDB->prepare("INSERT INTO showContact (topBox, topBoxBackground, topBoxBorder, frameBox, addressBox) VALUES ('Example Text', '2px', 'Green', 'Google Maps', 'Here ')");
            $createRow->execute();
            $createRow->close();
        }
    }

}
