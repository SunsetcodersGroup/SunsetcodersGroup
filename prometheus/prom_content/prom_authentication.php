<?php

if (session_status() == PHP_SESSION_NONE)
    session_start();


$servername = "localhost";
$username = "root";
$password = "Aort101ms!";

$conn = new mysqli($servername, $username, $password);

if (empty (mysqli_fetch_array(mysqli_query($conn, "SHOW DATABASES LIKE 'sunsetcodersgroup' ")))){

$conn->query("CREATE DATABASE sunsetcodersgroup");
    
} 

function databaseConnection() {
    $authConfig = Array("host" => "localhost", "user" => "root", "password" => "Aort101ms!", "catalogue" => "sunsetcodersgroup");

    $mysqli = mysqli_connect($authConfig["host"], $authConfig["user"], $authConfig["password"], $authConfig["catalogue"]);

    return $mysqli;
}

$dbConnnection = databaseConnection();
$secondLink = databaseConnection();

$val = mysqli_query($dbConnnection, 'select 1 from `users` LIMIT 1');

if ($val !== FALSE) {
    
} else {

    $createUsersTable = $dbConnnection->prepare("CREATE TABLE users (userID INT(11) AUTO_INCREMENT PRIMARY KEY, userFullName VARCHAR(100) NOT NULL, userUsername VARCHAR(100) NOT NULL, userPassword VARCHAR(100) NOT NULL, userStatus VARCHAR(20) NOT NULL)");
    $createUsersTable->execute();
    $createUsersTable->close();

    $insertUsersRow = $dbConnnection->prepare("INSERT INTO `users` (`userID`, `userFullName`, `userUsername`, `userPassword`, `userStatus`) VALUES (NULL, 'System Administrator', 'Administrator', 'werdwerd', 'Administrator')");
    $insertUsersRow->execute();
    $insertUsersRow->close();

    $createTable = $dbConnnection->prepare("CREATE TABLE pages (pageID INT(11) AUTO_INCREMENT PRIMARY KEY, pageName VARCHAR(100) NOT NULL, pageRow1 VARCHAR(20) NOT NULL, pageRow2 VARCHAR(20) NOT NULL, pageRow3 VARCHAR(20) NOT NULL, pageRow4 VARCHAR(20) NOT NULL, pageRow5 VARCHAR(20) NOT NULL, pageRow6 VARCHAR(20) NOT NULL, pageRow7 VARCHAR(20) NOT NULL, pageRow8 VARCHAR(20) NOT NULL, pageRow9 VARCHAR(20) NOT NULL, pageRow10 VARCHAR(20) NOT NULL)");
    $createTable->execute();
    $createTable->close();

    $insertRow = $dbConnnection->prepare("INSERT INTO `pages` (`pageID`, `pageName`, `pageRow1`, `pageRow2`, `pageRow3`, `pageRow4`, `pageRow5`, `pageRow6`, `pageRow7`, `pageRow8`, `pageRow9`, `pageRow10`) VALUES (NULL, 'Home', '', '', '', '', '', '', '', '', '', '')");
    $insertRow->execute();
    $insertRow->close();

    $createPageTable = $dbConnnection->prepare("CREATE TABLE page_settings (pagesetID INT(11) AUTO_INCREMENT PRIMARY KEY, pageID INT(11) NOT NULL, rowID DECIMAL(4,0)	 NOT NULL, moduleCode VARCHAR(100) NOT NULL)");
    $createPageTable->execute();
    $createPageTable->close();

    $createSettingsTable = $dbConnnection->prepare("CREATE TABLE settings (settingsID INT(11) AUTO_INCREMENT PRIMARY KEY, settingsName VARCHAR(100) NOT NULL, settingsFileName VARCHAR(100) NOT NULL)");
    $createSettingsTable->execute();
    $createSettingsTable->close();

    $createMenuTable = $dbConnnection->prepare("CREATE TABLE menus (menuID INT(11) AUTO_INCREMENT PRIMARY KEY, menuLocation VARCHAR(100) NOT NULL, menuLabel VARCHAR(100) NOT NULL, menuOrder DECIMAL(1,0) NOT NULL)");
    $createMenuTable->execute();
    $createMenuTable->close();
}

function processLogin() {

    global $dbConnection;

    $userUsername = filter_input(INPUT_POST, 'setUsername');
    $userPassword = filter_input(INPUT_POST, 'setPassword');

    if ($stmt = $dbConnection->prepare("SELECT userUsername, userPassword FROM users WHERE userUsername=? AND userPassword=? AND userStatus='Administrator' ")) {

        $stmt->bind_param("ss", $userUsername, $userPassword);
        $stmt->execute();

        $stmt->bind_result($userUsername, $userPassword);
        $stmt->fetch();

        if ($userUsername) {
            $_SESSION ['userUsername'] = $userUsername;
            $_SESSION ['userPassword'] = $userPassword;

            echo '<tr><td><center>Access Granted!</td></tr>';
        } else {
            echo '<tr><td><center>Access Denied!</td></tr>';
        }
    }
    echo '<tr><td><center><font color=black><b>Please Wait!!!!</td></tr>';
    echo '<meta http-equiv="refresh" content="1;url=index.php">';
}

function loginScreen() {

    echo '<center><table width=1024 height=400px;>';
    echo '<tr><td><br></td></tr>';
    echo '<tr><td><img src="Images/logo.png"></td></tr>';
    echo '<tr><td><br><br>&nbsp;<center>';

    echo '<form method="post" action="?id=processLogin">';
    echo '<table width=450 cellpadding=10 style="border-radius: 5px; border: 3px solid #615f5e;">';

    echo '<tr><td align=right style="color: #615f5e;"><b>USER NAME</td><td colspan=2 style="padding-right: 50px;"><center><input type="text" name="setUsername" placeholder="enter your username..." size=25></td></tr>';
    echo '<tr><td align=right style="color: #615f5e;"><b>PASSWORD</td><td colspan=2 style="padding-right: 50px;"><center><input type="password" name="setPassword" placeholder="****" size=25></td></tr>';
    echo '<tr><td ></td><td colspan=2 style="padding-right: 50px;"><center><input id="quoteSubmit" type="image" src="Images/login.png" alt="" onmouseover="javascript:this.src=\'Images/login-over.png\'" onmouseout="javascript:this.src=\'Images/login.png\'"/></td></tr>';
    echo '<tr><td colspan=3></td></tr>';
    echo '<tr><td colspan=2 align=right ><font style="font-weight:bold;  color: #615f5e;">Forgetten your password or username?</td><td width=85 style="padding-right: 50px;"><a href="?id=Recover"><img src="Images/recover.png"></a></td></tr>';
    echo '</table>';
    echo '</form>';


    echo '</td></tr>';
    echo '</table>';
}

function is_admin() {

    global $dbConnection;

    if ($stmt = $dbConnection->prepare("SELECT userUsername, userPassword FROM users WHERE userUsername=? AND userPassword=? AND userStatus='Administrator' ")) {

        $stmt->bind_param("ss", $_SESSION ['userUsername'], $_SESSION ['userPassword']);
        $stmt->execute();

        $stmt->bind_result($userUsername, $userPassword);
        $stmt->fetch();

        if ($userUsername == TRUE)
            return TRUE;
    }
}
