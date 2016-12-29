<?php

ini_set('date.timezone', 'Australia/Sydney');
error_reporting(E_ALL);
ini_set('display_errors', '1');

include ('auth.php');
include ('function_class.php');

echo '<div><h1>Sunsetcoders Project Management</h1></div>';
$localAction = filter_input(INPUT_GET, 'id');

Switch (strtoupper($localAction)) {

    case "STEPPROCESS":
        have_process();
        break;
    case "PROJECT":
        have_project();
        break;
    default:
        have_management();
}
