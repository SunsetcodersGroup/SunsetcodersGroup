<style>
    #content-background
    {
        width: 100%;
        background-color: #fff;
        color: #00186c;
    }
    
    h1
    {
        color: #00186c;
    }
</style>

<?php

$dbTriConnection = databaseConnection();


/*
 * The Following Snippet is to insert the module table into the mysqli table. 
 */

$val = mysqli_query($dbTriConnection, 'select 1 from `content_editor` LIMIT 1');

if ($val !== FALSE) {
    
} else {
    $createTable = $dbTriConnection->prepare("CREATE TABLE content_editor (contentID INT(11) AUTO_INCREMENT PRIMARY KEY, contentBody VARCHAR(100) NOT NULL, contentCode VARCHAR(100) NOT NULL)");
    $createTable->execute();
    $createTable->close();
}

class content_editor {

    protected $dbConnection;

    function __construct($dbConnection) {

        $this->dbConnection = $dbConnection;
    }

    public function content_editor() {

        echo '<table border=0 cellpadding=15 width=50% cellspacing=0>';
        echo '<tr><td colspan=3><h2>Content Management Panel</h2></td></tr>';
        echo '<tr><td colspan=3><button><a href="?id=Content&&moduleID=addContentBox">Add New</a></button></td></tr>';
        echo '<tr><td class="headerMenu">Content Body</td><td class="headerMenu">Content Code</td><td class="headerMenu"></td></tr>';

        $stmt = $this->dbConnection->prepare("SELECT contentID, contentBody, contentCode FROM content_editor ORDER BY contentID");
        $stmt->execute();

        $stmt->bind_result($contentID, $contentBody, $contentCode);

        while ($checkRow = $stmt->fetch()) {

            echo '<tr><td>' . $contentBody . '</td><td width=180>' . $contentCode . '</td><td width=80><a href="?id=Content&&moduleID=editContent&&ContentID=' . $contentID . '">edit</a></td></tr>';
        }
        echo '</table>';
    }

    public function addContentBox() {

        echo '<form method="POST" action="?id=Content&&moduleID=uploadContent">';
        echo '<table border=1 cellpadding=10 width=50%>';
        echo '<tr><td colspan=3><h2>Content Management Panel</h2></td></tr>';
        echo '<tr><td></td></tr>';
        echo '<tr><td class="headerMenu">Content Code</td></tr>';
        echo '<tr><td><b>[ Content-<input type="text" name="contentCode"> ]</td></tr>';
        echo '<tr><td></td></tr>';
        echo '<tr><td class="headerMenu">Content Body</td></tr>';

        echo '<tr><td><textarea name="contentBody" rows="10" cols="150"></textarea></td></tr>';
        echo '<tr><td></td></tr>';
        echo '<tr><td><input type="Submit" name="Submit" value="Submit"></td></tr>';
        echo '</table>';
        echo '</form>';
    }

    public function uploadContent() {

        $contentDescription = filter_input(INPUT_POST, 'contentBody');
        $contentCode = '[Content-' . filter_input(INPUT_POST, 'contentCode') . ']';
        
        $stmt = $this->dbConnection->prepare("INSERT INTO `content_editor` ( 'contentBody', 'contentCode') VALUES (?,?)");
        $stmt->bind_param('ss', $contentDescription, $contentCode);

        if ($stmt === false) {
            trigger_error($this->dbConnection->error, E_USER_ERROR);
        }

        $status = $stmt->execute();

        if ($status === false) {
            trigger_error($stmt->error, E_USER_ERROR);
        }
        echo '<font color=black><b>Content Information Updated <br><br> Please Wait!!!!<br>';
        echo '<meta http-equiv="refresh" content="1;url=?id=Content">';
    }

    public function editContent() {

        $contentID = filter_input(INPUT_GET, "ContentID");

        echo '<form method="POST" action="?id=Content&&moduleID=UpdateContent">';
        echo '<input type="hidden" name="contentID" value="' . $contentID . '">';

        if ($stmt = $this->dbConnection->prepare("SELECT contentBody FROM content_editor WHERE contentID=? ")) {

            $stmt->bind_param('i', $contentID);
            $stmt->execute();
            $stmt->bind_result($contentBody);
            $stmt->fetch();

            echo '<table border=0 cellpadding=20>';
            echo '<tr><td><h2>Content: </h2></td></tr>';
            echo '<tr><td><textarea cols=100 rows=10 name="contentMatter">' . $contentBody . '</textarea></td></tr>';
            echo '<tr><td><input type="submit" name="submit" value="Update"></td></tr>';
        }
        echo '</form>';
    }

    public function updateContent() {

        $contentDescription = filter_input(INPUT_POST, 'contentMatter');
        $contentID = filter_input(INPUT_POST, 'contentID');

        $stmt = $this->dbConnection->prepare("UPDATE content_editor SET contentBody=? WHERE contentID=?");
        $stmt->bind_param('si', $contentDescription, $contentID);

        if ($stmt === false) {
            trigger_error($this->dbConnection->error, E_USER_ERROR);
        }

        $status = $stmt->execute();

        if ($status === false) {
            trigger_error($stmt->error, E_USER_ERROR);
        }
        echo '<font color=black><b>Content Information Updated <br><br> Please Wait!!!!<br>';
        echo '<meta http-equiv="refresh" content="1;url=?id=Content">';
    }

    public function callToFunction() {

        if ($stmt = $this->dbConnection->prepare("SELECT contentBody FROM content_editor WHERE contentCode=?")) {

            $stmt->bind_param('s', $_SESSION['contentString']);
            $stmt->execute();
            $stmt->bind_result($contentBody);
            $stmt->fetch();

            echo '<div id="content-background">';
            echo '<div class="body-content">';

            echo '<br><br><br><br>'.nl2br($contentBody);

            echo '<br><br></div>';
            echo '</div>';
        }
    }

}
