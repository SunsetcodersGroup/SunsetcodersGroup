<?php

if (session_status() == PHP_SESSION_NONE)
    session_start();

define("IMAGE_PATH", "http://".filter_input(INPUT_SERVER,'SERVER_NAME')."/Images"); 
define("PATH_NAME",  "http://".filter_input(INPUT_SERVER,'SERVER_NAME')); 
define("DIR_NAME", filter_input(INPUT_SERVER,'SCRIPT_NAME')); 
define("CURRENT_SELF", filter_input(INPUT_SERVER,'PHP_SELF')); 

function databaseConnection() {
    
    $authConfig = Array("host" => "localhost", "user" => "root", "password" => "Aort101ms!", "catalogue" => "sunsetcodersgroup");
    $mysqli = mysqli_connect($authConfig["host"], $authConfig["user"], $authConfig["password"], $authConfig["catalogue"]);
    return $mysqli;
}

function datChange($datChange) {
    return date('d/m/Y', strtotime($datChange));
}

function datReturn($datChange) {
    $tempValue = explode('/', $datChange);

    return $tempValue[2] . '-' . $tempValue[1] . '-' . $tempValue[0];
}
