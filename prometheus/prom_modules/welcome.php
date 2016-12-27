<style>

    #welcome-admin-content
    {
        height: 300px;
        width: 700px;
        padding: 10px;

    }

    #welcome-background
    {
        background-image: url("../Images/stoneback.jpg");
        width: 100%;
        height: 400px;
    }

    .welcome-left
    {
        float: left;
        width: 35%;
    }

    .welcome-right
    {
        float: right; 
        text-align: left;
        width: 55%;
        padding-top: 70px;
    }

    .welcome-content
    {
        height: 400px;
    }

    h2
    {
        text-shadow: 2px 2px #333333;
        color: orange;
        font-size: 16pt;
    }


</style>

<?php
require_once dirname(dirname(__FILE__)) . '/SunLibraryModule.php';

class welcome extends SunLibraryModule {

    const ModuleDescription = "Welcome Screen Module";
    const ModuleAuthor = "Sunsetcoders Development Team";
    const ModuleVersion = "0.1";

    function __construct($dbConnection) {
        parent::__construct($dbConnection);
    }

    public function welcome() {

        echo '<form method="POST" action="?id=Welcome&&moduleID=UpdateContent">';

        if ($stmt = $this->objDB->prepare("SELECT welcomeContent FROM welcome WHERE welcomeID=1 ")) {

            $stmt->execute();
            $stmt->bind_result($welcomeContent);
            $stmt->fetch();

            echo '<table border=0 cellpadding=20>';
            echo '<tr><td><h2>Welcome Content Editor: </h2></td></tr>';
            echo '<tr><td><textarea name="contentMatter" id="welcome-admin-content">' . $welcomeContent . '</textarea></td></tr>';
            echo '<tr><td><input type="submit" name="submit" value="Update"></td></tr>';
        }
        echo '</form>';
    }

    public function updateContent() {

        $contentDescription = filter_input(INPUT_POST, 'contentMatter');

        $stmt = $this->objDB->prepare("UPDATE welcome SET welcomeContent=? WHERE welcomeID=1");
        $stmt->bind_param('s', $contentDescription);

        if ($stmt === false) {
            trigger_error($this->objDB->error, E_USER_ERROR);
        }

        $status = $stmt->execute();

        if ($status === false) {
            trigger_error($stmt->error, E_USER_ERROR);
        }
        echo '<font color=black><b>Content Information Updated <br><br> Please Wait!!!!<br>';
        echo '<meta http-equiv="refresh" content="1;url=?id=Welcome">';
    }

    public function callToFunction() {

        if ($stmt = $this->objDB->prepare("SELECT welcomeContent FROM welcome WHERE welcomeID=1")) {

            $stmt->execute();
            $stmt->bind_result($welcomeContent);
            $stmt->fetch();
            ?>
            <div id="welcome-background">
                <div class="body-content"> 
                    <div class="welcome-content">
                        <div class="welcome-left"><img class="welcome" src="../Images/stones.png"></div>
                        <div class="welcome-right"><?php echo nl2br($welcomeContent); ?></div>
                    </div> 
                </div> 
            </div>

            <?php
        }
    }

    protected function assertTablesExist() {

        $val = mysqli_query($this->objDB, 'select 1 from `welcome` LIMIT 1');

        if ($val !== FALSE) {
            
        } else {

            $createTable = $this->objDB->prepare("CREATE TABLE welcome (welcomeID INT(11) AUTO_INCREMENT PRIMARY KEY, welcomeContent VARCHAR(4000) NOT NULL)");
            $createTable->execute();
            $createTable->close();

            $createRow = $this->objDB->prepare("INSERT INTO `welcome` (`welcomeContent`) VALUES ('Example Text')");
            $createRow->execute();
            $createRow->close();
        }
    }

}
